<?php

  /* Desired ratio for NFT final size */ 
  $nft_ratio['w'] = 0;
  $nft_ratio['h'] = 0;

  
  /* Layer combinations for each batch of NFTs */
  $variation_layers = [array()];
  
  /* Number of NFTs in each batch */
  $variation_nfts = [];
  
  /* Marketplace Metadata standards available: TrustMarket and IsengardMarket */
  $marketplace = 'TrustMarket';
  $collection_name = '';
  $nft_description = '';
  
  /* If you are using ISENGARD MARKET, please fill out the NFT tags value */
  $nft_tags = [''];
  
  /* Duplicate all NFTs without the background layer. it TRUE, create new folder for organization */
  $make_transparent_BG = false;
  
  $duplicate_nft_folder = '';

?>