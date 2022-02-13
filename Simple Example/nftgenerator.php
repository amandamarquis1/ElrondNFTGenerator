<?php

  require_once('./config.php');
  
  /* Total NFTs in all batches */
  $total_nfts = array_sum($variation_nfts);

  /* Get all of the layer folders */
  $folders = array_filter(glob('*'), 'is_dir');

  /* Get all layers */
  $src_dirs = array();
  foreach($folders as $dir) {
    array_push($src_dirs, glob('./' . $dir .'/*.png'));
  }
  
  /* Collect the max appearance count for the layers */
  $layer_frequencies = calculate_frequencies($src_dirs, $total_nfts);
  
  /* Arrays to store the generated NFTs and their metadata to ensure no duplicates */
  $generated_nfts = array();
  $generated_metadata = array();
  $collectRarity = array();
  $collectFolders = array();
  
  /* Verify there are enough layer combinations to make NFTS TODO: Update error message */
  if(check_max_nfts($variation_nfts, $total_nfts, $variation_layers)) {
	$current_nft = 1;  
	mkdir('NFTGenerator');
	mkdir('Metadata');
	
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
          $image_src = $src_dirs[$key][rand(1,count($src_dirs[$key]))-1];
		
      	  $trait_name = basename($image_src, '.png');
	      array_push($attributes, array('trait_type' => substr(dirname($image_src),2), 'value' => explode('#',$trait_name)[0]));
          /* img size and dimensions */
          $img_size = getimagesize($image_src);
          $nft_layers[$layer_type]['img'] = imagecreatefrompng($image_src);
  	      $nft_layers[$layer_type]['w'] = $img_size[0];
          $nft_layers[$layer_type]['h'] = $img_size[1];
        }

	    /* Make sure the current generated NFT doesn't already exist */
        if(!in_array($attributes, $generated_nfts)) {
			
		  /* Track the frequency of the layer */
		  foreach ($attributes as $key => $val) {
            if (isset($collectRarity[$val['value']]))
              $collectRarity[$val['value']] += 1;
            else {
              $collectRarity[$val['value']] = 1;
              $collectFolders[$val['value']] = $val['trait_type'];
		    }
			/* Check if a layer has reached its max appearances */
		    if($collectRarity[$val['value']] === $layer_frequencies[$val['value']]) {
			  $key = $val['value'];
			  $remove_layer = './' . $collectFolders[$key] . '/' . $key . '#' . $layer_frequencies[$key] . '.png';
		      $folder_index = array_search($collectFolders[$key], $folders);
			  
			  /* Remove layer if it has reached max appearances, reindex the src_dirs array */
			  if(($remove_index = array_search($remove_layer, $src_dirs[$folder_index])) !== false) {
				unset($src_dirs[$folder_index][$remove_index]);
			  }
			  $src_dirs[$folder_index] = array_values($src_dirs[$folder_index]);
			}
          }

		  /* NFT base to combine all layers */
          $small_nft = imagecreatetruecolor($nft_layers[0]['w'], $nft_layers[0]['h']);
          foreach ($nft_layers as $layer){
            imagecopymerge($small_nft, $layer['img'], 0, 0, 0, 0, $layer['w'], $layer['h'], 100);
            imagedestroy($layer['img']);
          }
		  $new_nft = imagecreatetruecolor($nft_ratio['w'], $nft_ratio['h']);
		  imagecopyresized($new_nft, $small_nft, 0, 0, 0, 0, $nft_ratio['w'], $nft_ratio['h'], $nft_layers[0]['w'], $nft_layers[0]['h']);
          array_push($generated_nfts, $attributes);
          
		  $generated_metadata = add_metadata($generated_metadata, $attributes, $current_nft, $marketplace, $nft_description, $nft_tags);
		  
          imagepng($new_nft, "./NFTGenerator/$current_nft.png");
          imagedestroy($new_nft);
		  $current_nft++;
		
	    /* If NFT combo already exists, don't count this loop and try again */
        } else {
	      $nft_in_batch--;
        }
		$nft_in_batch++;
      }
	}
  } else {
    exit('There are not enough layers available to create the number of NFTs you want to make');
  }

  /* Write Metadata to json file */
  file_put_contents('./Metadata/metadata.json', json_encode(array('name'=>'test', 'nfts'=>$generated_metadata), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

  /* Write layer frequency information to a csv file */
  $frequency_to_csv = fopen('./Metadata/rarities.csv', 'w');
  fputcsv($frequency_to_csv, array('Category', 'Layer', 'Count', 'Frequency'));
  foreach($collectRarity as $field => $val) {
    fputcsv($frequency_to_csv, Array($collectFolders[$field], $field , $val, number_format(($val / $total_nfts) * 100, 2).'%'));
  }

  /* Zip NFTs and metadata in folder */
  $zip = new ZipArchive;
  if($zip -> open('NFTGenerator.zip', ZipArchive::CREATE ) === TRUE) {
    $dir = opendir('NFTGenerator/');
    while($file = readdir($dir)) {
      if(is_file('NFTGenerator/'.$file)) {
       $zip -> addFile('NFTGenerator/'.$file, $file);
      }
    }
    $zip ->close();
  }
  
  /* Remove unzipped folder & files */
  array_map('unlink', glob('NFTGenerator/*.*'));
  rmdir('NFTGenerator');

  /* Max NFT function to check enough layers are available */
  function check_max_nfts($variation_numbers, $total_nfts, $layer_combos) {
	/* Count layer combos */
	$max_nfts = 1;
	foreach($layer_combos as $index => $layers) {
	  $variation_max = 1;
	  foreach($layers as $dir) {
        $variation_max *= count(glob('./' . $dir .'/*.png'));
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
  
  function calculate_frequencies($src_dirs, $total_nfts) {
	$layer_frequencies = array();
	foreach ($src_dirs as $category) {
	  foreach($category as $key => $layer_name) {
		$trait_name = basename($layer_name, '.png');
		$frequency = isset(explode('#',$trait_name)[1]) ? explode('#',$trait_name)[1] : $total_nfts;
		$layer_frequencies[explode('#',$trait_name)[0]] = $frequency;
	  }
	}
	return $layer_frequencies;
  }
  
  function add_metadata($generated_metadata, $attributes, $current_nft, $marketplace, $nft_description, $nft_tags) {
	if($marketplace === 'TrustMarket') {
	  array_push($generated_metadata, array('description'=>$nft_description, 'attributes'=>$attributes));
	} else if ($marketplace === 'Isengard') {
	  array_push($generated_metadata, array('description'=>$nft_description, 'filename'=>"$current_nft.png", 'fileType'=>'image/png', 'tags'=>$nft_tags, 'attributes'=>$attributes));	
	}
    return $generated_metadata;
  }
 
?>