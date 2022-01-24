<?php

  /* Get all of the layer folders */
  $folders = array_filter(glob('*'), 'is_dir');

  /* Get & count all layers */
  $src_dirs = array();
  $max_nfts = 1;
  foreach($folders as $dir) {
    array_push($src_dirs, glob("./" . $dir ."/*.png"));
    $max_nfts *= count(glob("./" . $dir ."/*.png"));
  }
 
  /* CHANGE THIS: total_nfts = total number in collection */
  $total_nfts = 5; 

  /* Verify enough layers were added to make desired number of NFTs */
  if($max_nfts < $total_nfts) {
	 echo "Max number of NFT's you can create is $max_nfts. You are trying to make $total_nfts. Please add more layers or lower your \$total_nfts value.";
  } else {
	  
    /* Arrays to store the generated NFTs and their metadata to ensure no duplicates */
    $generated_nfts = array();
    $generated_metadata = array();
    mkdir("NFTGenerator");
	  
	$layers = count($src_dirs); 
    for($i = 0; $i < $total_nfts; $i++) {
	
      $attributes = array();
      for($j = 0; $j < $layers; $j ++) {
        $image_src = $src_dirs[$j][rand(1,count($src_dirs[$j]))-1]; 
    	$trait_name = basename($image_src, ".png");
	    array_push($attributes, array("trait_type" => substr(dirname($image_src),2), "value" => $trait_name));
		
        /* img size and dimensions */
        $img_size = getimagesize($image_src); 
        $nft_layers[$j]['img'] = imagecreatefrompng($image_src); 
  	    $nft_layers[$j]['w'] = $img_size[0]; 
        $nft_layers[$j]['h'] = $img_size[1];
      }
	  
	  /* Make sure the current generated NFT doesn't already exist */
      if(!in_array($attributes, $generated_nfts)) {
		/* NFT base to combine all layers CHANGE THIS: to be the dimensions of your NFTs*/
        $new_nft = imagecreatetruecolor(1000, 1000);
        foreach ($nft_layers as $layer){ 
          imagecopymerge($new_nft, $layer['img'], 0, 0, 0, 0, $layer['w'], $layer['h'], 100);
          imagedestroy($layer['img']); 
        } 
        array_push($generated_nfts, $attributes);
        array_push($generated_metadata, 
		            array("description"=>"This is a description of NFT #$i in my fictional collection example!",
		                  "filename"=>"$i.png", 
						  "fileType"=>"image/png",
						  "tags"=>array("tag1", "tag2", "tag3"), 
						  "attributes"=>$attributes));
        imagepng($new_nft, "./NFTGenerator/$i.png"); 
        imagedestroy($new_nft);
		
	  /* If NFT combo already exists, don't count this loop and try again */
      } else {
	    $i--;
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