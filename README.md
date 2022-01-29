# ElrondNFTGenerator
NFT Generator for the Elrond Blockchain coded in PHP

## About the project
Hi there! This NFT generator was inspired by a personal desire to create a mintable NFT collection without the hassle of creating each individual NFTs by hand. It has **VERY** basic functionality at this time. I will add features periodically (usually on the weekends) in my free time. Check out the [Roadmap](#roadmap) below for planned additions to this project! :)

You can view 

## Need to Know
- This NFT Generator **only works** with PNG's at this time.
- The example uses layers 1000 x 1000 in size. The base layer for the NFT generated is currently hard coded to 1000 x 1000. If you download the example and test it before this is fixed, make sure you change the base layer size beforehand.
- There is ***NO LAYER RARITY VALUE***. It randomly selects the layers with no regard to frequency, other than checking that a specific combination has been created previously or not.
- It currently works by stacking layers in the order of which they are entered into the $variation_layers array
- Metadata.json file is generated based on the specs for [Isengard Marketplace](https://isengard.market) mintable collections. If the file varies by marketplace, use caution if trying this code.
- Places the finished NFTs and metadata in a zipped folder.
- *It is not optimized to it's fullest potential and I am learning a lot as I go. Feedback and suggestions are always welcome!*

## Roadmap
- [ ] Create PHP & XAMPP Download instructions for a full tutorial
- Script
	- [ ] Create Layer Rarity functionality
	- [ ] Add GIF support 
	- [ ] Make sure Metadata.json file is universal
	- [x] Configure folder order in case BG is not first alphabetically
		* Layers currently entered into the $variation_layers array and will build in that order
	- [ ] Create a UI
- [ ] Create a more complicated example with layer rarity

## About Amanda
I am a web developer for the Cryptocurrency project Nexus. In late 2021 I got into the NFT space and released collections both on the Elrond and Nexus blockchains. Below is my contact information and other NFT collection details! 

- NFT
	- [MandasNFTs.com](https://mandasnfts.com)
	- [NFT Twitter](https://twitter.com/MandasNFTs)
	- [NFT Discord](https://t.co/89vPqmgrLk)
	- [My Elrond NFTs listed on Isengard](https://isengard.market/collection/WADDLE-9e36f0)
	- [My Email](mailto:mandasnfts@gmail.com)
- Personal
	- [Personal Twitter](https://twitter.com/Salamandacm)
	- [Telegram](https://t.me/Mandacm)
