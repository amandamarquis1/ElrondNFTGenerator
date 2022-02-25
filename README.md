# ElrondNFTGenerator
NFT Generator for the Elrond Blockchain coded in PHP

## About the project
Hi there! This NFT generator was inspired by a personal desire to create a mintable NFT collection without the hassle of creating each individual NFTs by hand. It has **VERY** basic functionality at this time. I will add features periodically (usually on the weekends) in my free time. Check out the [Roadmap](#roadmap) below for planned additions to this project! :)

You are free to download this code and generate your NFTs directly. You will need to install a PHP server, or download PHP and run the code through task scheduler. Depending on the size of your collection, you may need to modify your php.ini file to increase the execution time of your script, as it can time out for larger collections. 

If you would like assistance with generating your Elrond NFT collection to avoid the hassle of PHP, please reach out to me at one of my [Contact Information](#About Amanda) links below! I would love to work with you and your team in creating a new NFT collection :)

## Need to Know
- This NFT Generator **only works** with PNG's at this time.
- The example uses layers 1000 x 1000 in size. The final size of the NFT can be different from the layer sizes uploaded. Layers uploaded do not have to be 1000 x 1000.
- There is ***SOME LAYER RARITY VALUE***. Layers are assigned a MAX appearance value. They will never exceed the max value, however for layers with larger appearance numbers (more common layers), they may not reach their max appearance value.
- Layer frequency is calculated and exported in a csv file.
- It currently works by stacking layers in the order of which they are entered into the $variation_layers array
- Metadata.json specs are currently available for Isengard and Trust markets. More are being added as I go.
- Places the finished NFTs in a zipped folder. It does not zip the json or csv files. 
- *It is not optimized to it's fullest potential and I am learning a lot as I go. Feedback and suggestions are always welcome!*

## Roadmap
- [ ] Create PHP & XAMPP Download instructions for a full tutorial
- Script
	- [x] Create Layer Rarity functionality
	- [ ] Add GIF support 
	- [x] Make sure Metadata.json file is universal
	- [x] Configure folder order in case BG is not first alphabetically
		* Layers currently entered into the $variation_layers array and will build in that order
- [x] Create a more complicated example with layer rarity

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
