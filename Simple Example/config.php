<?php

  /* Desired ratio for NFT final size */ 
  $nft_ratio['w'] = 400;
  $nft_ratio['h'] = 400;

  /* Marketplace Metadata standards available: TrustMarket and IsengardMarket */
  $marketplace = 'TrustMarket';
  $nft_description = 'Here is an NFT description!';
  
  /* Do you want individual metadata files for each NFT */
  $individual_metadata = false;
  
  /* Layer combinations for each batch of NFTs */
  $variation_layers = [array('Background', 'Stripes', 'Letter'), array('Background', 'Number', 'Letter'), array('Background', 'Stripes', 'Number', 'Letter'), array('Background', 'Letter')];
  
  /* Number of NFTs in each batch */
  $variation_nfts = [25, 10, 40, 5];
  
  /* If you are using ISENGARD MARKET, please fill out the NFT tags and collection name values */
  $collection_name = '';
  $nft_tags = ['tag1', 'tag2', 'tag3'];
  
  /* Duplicate all NFTs without the background layer. it TRUE, create new folder for organization */
  $make_transparent_BG = false;
  
  $duplicate_nft_folder = 'Transparent';

?>