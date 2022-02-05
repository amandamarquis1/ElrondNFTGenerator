<?php

  /* Desired ratio for NFT final size */ 
  $nft_ratio['w'] = 1000;
  $nft_ratio['h'] = 1000;

  /* Get all of the layer folders */
  $folders = array_filter(glob('*'), 'is_dir');

  /* Get all layers */
  $src_dirs = array();
  foreach($folders as $dir) {
    array_push($src_dirs, glob("./" . $dir ."/*.png"));
  }
  
  /* CHANGE ME: Layer combinations for each batch of NFTs */
  $variation_layers = [array('Background', 'Number'), array('Background', 'Stripes', 'Letter'), array('Background', 'Number', 'Letter')];

  /* Count batches to be created */
  $variations = count($variation_layers);
  
  /* CHANGE ME: Number of NFTs in each batch */
  $variation_nfts = [15, 15, 15];
  
  /* Verify there are enough layer combinations to make NFTS TODO: Update error message */
  if(!check_max_nfts($variation_nfts, $variation_layers, $src_dirs)) {
	 echo "There are not enough layers available to create the number of NFTs you want to make";
  } else {
	$current_nft = 1;  
	mkdir("NFTGenerator");
	
    /* Arrays to store the generated NFTs and their metadata to ensure no duplicates */
	$generated_nfts = array();
    $generated_metadata = array();
	$collectRarity = array();
	$collectFolders = array();
	
	/* Loop through the batches */
	for ($batch = 0; $batch < $variations; $batch++) {
	  $layers = count($variation_layers[$batch]); 
	  
	  /* Loop through the NFTs in the batch */
	  for($nft = 0; $nft < $variation_nfts[$batch]; $nft++) {
	
        $attributes = array();
		
		/* Loop through the layers for the specific NFT */ 
        for($layer_type = 0; $layer_type < $layers; $layer_type ++) {			
		  /* Get layers from the specific folder */
		  $key = array_search($variation_layers[$batch][$layer_type], $folders);
          $image_src = $src_dirs[$key][rand(1,count($src_dirs[$key]))-1]; 
      	  $trait_name = basename($image_src, ".png");
	      array_push($attributes, array("trait_type" => substr(dirname($image_src),2), "value" => $trait_name));

          /* img size and dimensions */
          $img_size = getimagesize($image_src); 
          $nft_layers[$layer_type]['img'] = imagecreatefrompng($image_src); 
  	      $nft_layers[$layer_type]['w'] = $img_size[0]; 
          $nft_layers[$layer_type]['h'] = $img_size[1];		 
        }
	  
	    /* Make sure the current generated NFT doesn't already exist */
        if(!in_array($attributes, $generated_nfts)) {
			
		  /* Count the frequency of the layer */
		  foreach ($attributes as $key => $val) { 
            if (isset($collectRarity[$val['value']]))
              $collectRarity[$val['value']] += 1;
            else
              $collectRarity[$val['value']] = 1;
              $collectFolders[$val['value']] = $val['trait_type'];
          }
			
		  /* NFT base to combine all layers CHANGE THIS: to be the dimensions of your NFTs */
          $small_nft = imagecreatetruecolor($nft_layers[0]['w'], $nft_layers[0]['h']);
          foreach ($nft_layers as $layer){ 
            imagecopymerge($small_nft, $layer['img'], 0, 0, 0, 0, $layer['w'], $layer['h'], 100);
            imagedestroy($layer['img']); 
          }
		  $new_nft = imagecreatetruecolor($nft_ratio['w'], $nft_ratio['h']);
		  imagecopyresized($new_nft, $small_nft, 0, 0, 0, 0, $nft_ratio['w'], $nft_ratio['h'], $nft_layers[0]['w'], $nft_layers[0]['h']);
          array_push($generated_nfts, $attributes);
          array_push($generated_metadata, 
	  	            array("description"=>"This is a description of NFT #$current_nft in my fictional collection example!",
		                  "filename"=>"$current_nft.png", 
						  "fileType"=>"image/png",
						  "tags"=>array("tag1", "tag2", "tag3"), 
						  "attributes"=>$attributes));
          imagepng($new_nft, "./NFTGenerator/$current_nft.png"); 
          imagedestroy($new_nft);
		  $current_nft++;
		
	    /* If NFT combo already exists, don't count this loop and try again */
        } else {
	      $nft--;
        }
      }
	}
  }

  /* Write Metadata to json file */
  file_put_contents('./metadata.json',
                     json_encode(array("name"=>"test", "nfts"=>$generated_metadata), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)); 

  /* Write layer frequency information to a csv file */
  $rarity_to_csv = fopen('./rarities.csv', 'w');
	 fputcsv($rarity_to_csv, array("Category", "Layer", "Count"));
	 foreach($collectRarity as $field => $val) {
	 fputcsv($rarity_to_csv, Array($collectFolders[$field], $field , $val));
	 }

  /* Zip NFTs and metadata in folder */
  $zip = new ZipArchive;
  if($zip -> open("NFTGenerator.zip", ZipArchive::CREATE ) === TRUE) {

    $dir = opendir("NFTGenerator/");       
   while($file = readdir($dir)) {
      if(is_file("NFTGenerator/".$file)) {
       $zip -> addFile("NFTGenerator/".$file, $file);
      }
    }
    $zip ->close();
  }
  /* Remove unzipped folder & files */
  array_map('unlink', glob("NFTGenerator/*.*"));
  rmdir("NFTGenerator");

  /* Max NFT function to check enough layers are available */
  function check_max_nfts($variation_numbers, $layer_combos, $src_dirs) {
	/* Total NFTs in all batches */
    $total_nfts = array_sum($variation_numbers);
	
	/* Count layer combos */
	$max_nfts = 1;
	foreach($layer_combos as $index => $layers) {
	  $variation_max = 1;
	  foreach($layers as $dir) {
        $variation_max *= count(glob("./" . $dir ."/*.png"));
	  }
	  if($variation_max < $variation_numbers[$index]) {
		  return false;
	  }
	  $max_nfts += $variation_max;
    }
	if($max_nfts < $total_nfts) {
	  return false;
	}
	
	return true;	
  }

?>