# Changelog
This will be an informal change log to document features worked on at each update.

## [2.1.1] 2022-5-09
- Added:
	- .gitignore
  - rearranged some of the script in nftgenerator.php
## [2.1.0] 2022-03-03
- Added:
	- Option for individual metadata files to be generated.

## [2.0.0] 2022-02-25
- Added:
	- Subfolder parsing for nested folders to organize batches
	- CWD navigation to return to main code location after parse
	- Rarity for NFTs by weight
	- Allow for creation of a transparent copy of NFT
- Removed:
	- Frequency of NFT being a hard stop number
- TODO:
	- Readd max NFT logic down the road
	- Add more marketplace metadata specs
## [1.1.1] 2022-02-12
- Added:
	- Better optimized code
	- Ability to choose what marketplace standard you want for metadata (currently trust and isengard)
	- Move Metadata information into the config file
	- Put metadata files generated in a metadata folder
- Removed:
	- Unused functions
	- Repetitve code 
	- Hard code metadata standard
- TODO:
	- Add ability to generate multiple metadata standards at once

## [1.1.0] 2022-02-09
- Added:
	- Config.php file to separate the changing values
	- Added Frequency % to the exported csv
	- Added function to check desired max appearance
	- Removes lataer from $src_dirs when max appearance has been met 
	- Renamed all images to include rarities
	- Added additional images
- Removed:
	- Single file that included changing values by user
- TODO: 
	- Clean up and optimize code
	- Stress test the optimization
	
## [1.0.3] 2022-02-04
- Added:
	- Function to check the max nft counts for each combination
	- Added layer frequency calculations
	- Export layer frequencies to a csv file
- Removed:
	- Old max NFT method
- TODO:
	- Stress test large NFT quantities with layers for optimization
	- Create Layer Rarity functionality

## [1.0.2] 2022-01-29
- Added:
	- final image size support to let users choose the final resolution of the NFT
- Removed:
	- Hard coded layer size that limited sizing ability
- TODO:
	- Stress test large NFT quantities with layers for optimization
	- Fix max_nft logic to account for different batches of combinations
	- Create Layer Rarity functionality
	- Make sure Metadata.json file is universal


## [1.0.1] 2022-01-28
- Added:
	- Added arrays to be able to create NFT batches to customize layers
		- Removes issue of folder name / order
- Removed:
	- x
- TODO:
	- Fix max_nft logic to account for different batches of combinations
	- Optimize! start small and scale up img
	
	
## [1.0.0] 22-01-23
- Added:
	- All base code
	- Simple example
- TODO:
	- Create Layer Rarity functionality
	- Add GIF support 
	- Make sure Metadata.json file is universal
	- Configure folder order in case BG is not first alphabetically
	- Create a UI
	- Create a more complicated example with layer rarity