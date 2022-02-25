<?php

  /* Desired ratio for NFT final size */ 
  $nft_ratio['w'] = 400;
  $nft_ratio['h'] = 400;

  
  /* Layer combinations for each batch of NFTs */
  $variation_layers = [array('Background', 'Stripes', 'Letter'), 
					   array('Background', 'Number', 'Letter')];
  
   /* Number of NFTs in each batch */
  $variation_nfts = [2,2];
  
  $marketplace = 'TrustMarket';
  $collection_name = '';
  $nft_description = 'aabbcc';
  
  /* If you are using ISENGARD MARKET, please fill out the NFT tags value */
  $nft_tags = ['tag1', 'tag2', 'tag3'];
  
  /* Duplicate all NFTs without the background layer. it TRUE, create new folder for organization */
  $make_transparent_BG = false;
  
  $duplicate_nft_folder = 'Transparent';

?>