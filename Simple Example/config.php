<?php

  /* Desired ratio for NFT final size */ 
  $nft_ratio['w'] = 1000;
  $nft_ratio['h'] = 1000;

  
  /* Layer combinations for each batch of NFTs */
  $variation_layers = [array('Background', 'Number', 'Stripes'), 
					   array('Background', 'Stripes', 'Letter'), 
					   array('Background', 'Number', 'Letter')];
  
  /* Number of NFTs in each batch */
  $variation_nfts = [5, 5, 5];
  
  $marketplace = 'TrustMarket';
  $nft_description = "This is a description of my fictional NFT collection!";
  
  /* If you are using ISENGARD MARKET, please fill out the NFT tags value */
  $nft_tags = ['tag1', 'tag2', 'tag3'];

?>