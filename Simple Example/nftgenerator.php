<?php

  require_once('./config.php');
  $location = getcwd();

  /* Get all of the layer folders */
  $folders = getDirContents($location, getcwd());

  /* Return to main directory after identifying all files */
  chdir($location);

  /* Collect links to all png layers */
  $src_dirs = array();
  $src_dir_rarities = array();
  foreach($folders as $dir) {
	$rarity_array = array();
	$folder_dir = glob('./' . $dir .'/*.png');
    array_push($src_dirs, $folder_dir);
	/* Collect all layer rarities for each folder */
	foreach($folder_dir as $file_name) {
		/* x100 to account for the very small percentage of large collections */
		$layer_rarity = isset(explode('#',basename($file_name, '.png'))[1]) ? (explode('#',basename($file_name, '.png'))[1])*100 : 100 ;
		array_push($rarity_array, $layer_rarity);
	}
	array_push($src_dir_rarities, $rarity_array);
  }

  /* Arrays to store the generated NFTs and their metadata to ensure no duplicates and count appearances*/
  $generated_nfts = array();
  $generated_metadata = array();
  
  /* Verify there are enough layer combinations to make NFTS TODO: Update Max NFT again */
  $current_nft = 1;  
  mkdir('NFTGenerator');
  mkdir('Metadata');
  if($make_transparent_BG) {		
    mkdir($duplicate_nft_folder);
  }
	
	/* Loop through the batches of NFTs*/
	foreach ($variation_layers as $batch_index => $batch_variations) {
	  $nft_in_batch = 0;
		
	  /* Loop through the NFTs in the batch */
	  while($nft_in_batch < $variation_nfts[$batch_index]) {
      $attributes = array();
      
		  /* Loop through the layers for the specific NFT */ 
      foreach($batch_variations as $layer_type => $layer_category) {
        /* Get layers from the specific folder */
		    $key = array_search($layer_category, $folders);
		    $rarity_key = getLayer($src_dir_rarities[$key]);
        $image_src = $src_dirs[$key][$rarity_key];
      	$trait_name = basename($image_src, '.png');
	      
        /* img size and dimensions */
        $img_size = getimagesize($image_src);
        $nft_layers[$layer_type]['img'] = imagecreatefrompng($image_src);
  	    $nft_layers[$layer_type]['w'] = $img_size[0];
        $nft_layers[$layer_type]['h'] = $img_size[1];
		  
		    if(!str_contains($image_src, 'NONE')) {
		      array_push($attributes, array('trait_type' => explode('/', substr(dirname($image_src),2))[0], 'value' => str_replace('_', ' ', explode('#',$trait_name)[0])));
		    }
		 
      }

	    /* Make sure the current generated NFT doesn't already exist */
      if(!in_array($attributes, $generated_nfts)) {
		    build_nft($nft_layers, $nft_ratio, 'NFTGenerator', $current_nft);
		  
		    /* Create a background-less duplicate for a transparent copy if desired */
		    if($make_transparent_BG) {
			    array_shift($nft_layers);
			    build_nft($nft_layers, $nft_ratio, $duplicate_nft_folder, $current_nft);
		    }
        array_push($generated_nfts, $attributes);
          
		    $generated_metadata = add_metadata($generated_metadata, $attributes, $current_nft, $marketplace, $nft_description, $nft_tags);
        
        if($individual_metadata) {
          file_put_contents("./Metadata/$current_nft.json", json_encode(array('description'=>$nft_description, 'attributes'=>$attributes), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
		  
		    $current_nft++;
		    $nft_layers = null;
	      /* If NFT combo already exists, don't count this loop and try again */
      } else {
	      $nft_in_batch--;
      }
      
		  $nft_in_batch++;
    }
	}
  
  /* Write Metadata to json file */
  if($marketplace === 'TrustMarket') {
	  file_put_contents('./Metadata/metadata.json', json_encode($generated_metadata, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	} else if ($marketplace === 'Isengard') {
	  file_put_contents('./Metadata/metadata.json', json_encode(array("name"=>$collection_name, "nfts"=>$generated_metadata), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	}
  
  
  function add_metadata($generated_metadata, $attributes, $current_nft, $marketplace, $nft_description, $nft_tags) {
	  if($marketplace === 'TrustMarket') {
	    array_push($generated_metadata, array('description'=>$nft_description, 'attributes'=>$attributes));
	  } else if ($marketplace === 'Isengard') {
	    array_push($generated_metadata, array('description'=>$nft_description, 'filename'=>"$current_nft.png", 'fileType'=>'image/png', 'tags'=>$nft_tags, 'attributes'=>$attributes));	
	  }
    return $generated_metadata;
  }
  
  function build_nft($nft_layers, $nft_ratio, $desired_directory, $current_nft) {
	  /* NFT base to combine all layers */
    $small_nft = imagecreatetruecolor($nft_layers[0]['w'], $nft_layers[0]['h']);
	  imagesavealpha($small_nft, true);
    $color = imagecolorallocatealpha($small_nft, 0, 0, 0, 127);
    imagefill($small_nft, 0, 0, $color);
	  /* Combine all layers */
    foreach ($nft_layers as $layer){
      imagecopy($small_nft, $layer['img'], 0, 0, 0, 0, $layer['w'], $layer['h']);
      imagedestroy($layer['img']);
    }
	
	  /* Resize the image and keep transparency if desired */
	  $new_nft = imagecreatetruecolor($nft_ratio['w'], $nft_ratio['h']);
	  imagesavealpha($new_nft, true);
    $color = imagecolorallocatealpha($new_nft, 0, 0, 0, 127);
    imagefill($new_nft, 0, 0, $color);
	  imagecopyresampled($new_nft, $small_nft, 0, 0, 0, 0, $nft_ratio['w'], $nft_ratio['h'], $nft_layers[0]['w'], $nft_layers[0]['h']);
	
	  imagepng($new_nft, './'.$desired_directory."/$current_nft.png");
    imagedestroy($new_nft);
  }
  
  /* Recursively navigate through the sub folders to find all folders */
  function getDirContents($location, $dir, &$results = array()) {
	  chdir($dir);
    $files = scandir($dir);

    foreach ($files as $key => $value) {
      $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
      if (is_dir($path) && ($value != '.' && $value != '..')) {
        getDirContents($location, $path, $results);
        $results[] = str_replace('\\', '/',substr($path, strlen($location)+1));
      }
    }

    return $results;
  }

  /* Randomly select attribute to use based on rarity weight */
  function getLayer(array $weightedValues) {
    $rand = rand(1, array_sum($weightedValues));

    foreach ($weightedValues as $key => $value) {
      $rand -= $value;
      if ($rand <= 0) {
        return $key;
      }
    }
  
  }
  
  function noRepeats($attributes, $dont_repeat_word, $dont_repeat_layers, $max_repeats, $current_nft) {
    $counter = 0;
    foreach($attributes as $trait => $values) {
        if(in_array($values['trait_type'], $dont_repeat_layers) && str_contains($values['value'], $dont_repeat_word)) {
          $counter++;
        }          
    }
    if ($counter == count($dont_repeat_layers) && $current_nft > $max_repeats) {
      file_put_contents("./Metadata/$current_nft.json", json_encode($attributes, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
      return false;
    }
    else
      return true;
  }

?>

