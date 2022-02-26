<?php

  /* Desired ratio for NFT final size */ 
  $nft_ratio['w'] = 400;
  $nft_ratio['h'] = 400;

  
  /* Layer combinations for each batch of NFTs */
  $variation_layers = [array('Background', 'NextLayer', 'Trait1/Subfolder')];
  
  /* Number of NFTs in each batch */
  $variation_nfts = [1];
  
  /* Marketplace Metadata standards available: TrustMarket and IsengardMarket */
  $marketplace = 'TrustMarket';
  $nft_description = '';
  
  /* If you are using ISENGARD MARKET, please fill out the NFT tags and collection name values */
  $nft_tags = ['tag1', 'tag2'];
  $collection_name = '';
  
  /* Duplicate all NFTs without the background layer. it TRUE, create new folder for organization */
  $make_transparent_BG = false;
  
  $duplicate_nft_folder = '';

?>