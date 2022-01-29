<?php

  /* Desired ratio for NFT final size */ 
  $nft_ratio['w'] = 1000;
  $nft_ratio['h'] = 1000;

  /* Get all of the layer folders */
  $folders = array_filter(glob('*'), 'is_dir');

  /* Get & count all layers */
  $src_dirs = array();
  
  /*TODO: Change max_nfts logic */
  $max_nfts = 1;
  foreach($folders as $dir) {
    array_push($src_dirs, glob("./" . $dir ."/*.png"));
    $max_nfts *= count(glob("./" . $dir ."/*.png"));
  }
  
  /* Layer combinations for each batch of NFTs */
  $variation_layers = [array('Background', 'Number'), array('Background', 'Stripes', 'Letter'), array('Background', 'Number', 'Letter')];

  /* Count batches to be created */
  $variations = count($variation_layers);
  
  /* number of NFTs in each batch */
  $variation_nfts = [10, 15, 7];
  
  /* Total NFTs in all batches */
  $total_nfts = array_sum($variation_nfts);

  /* Verify enough layers were added to make desired number of NFTs */
  if($max_nfts < $total_nfts) {
	 echo "Max number of NFT's you can create is $max_nfts. You are trying to make $total_nfts. Please add more layers or lower your \$total_nfts value.";
  } else {
	$current_nft = 1;  
	mkdir("NFTGenerator");
	
    /* Arrays to store the generated NFTs and their metadata to ensure no duplicates */
	$generated_nfts = array();
    $generated_metadata = array();
	
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

  file_put_contents('./NFTGenerator/metadata.json',
                     json_encode(array("name"=>"test", "nfts"=>$generated_metadata), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)); 
 
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
?>