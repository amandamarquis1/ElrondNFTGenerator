# ElrondNFTGenerator
NFT Generator for the Elrond Blockchain coded in PHP

## About the project
Hi there! This NFT generator was inspired by a personal desire to create a mintable NFT collection without the hassle of creating each individual NFTs by hand. It has **VERY** basic functionality at this time. I will add features periodically (usually on the weekends) in my free time. Check out the [Roadmap](#roadmap) below for planned additions to this project! :)

You are free to download this code and generate your NFTs directly. You will need to install a PHP server, or download PHP and run the code through task scheduler. Depending on the size of your collection, you may need to modify your php.ini file to increase the execution time of your script, as it can time out for larger collections. View [Tutorial](#Tutorial) for a walkthrough below!

If you would like assistance with generating your Elrond NFT collection to avoid the hassle of PHP, please reach out to me at one of my [Contact Information](#About) links below! I would love to work with you and your team in creating a new NFT collection :)

## Need to Know
- This NFT Generator **only works** with PNG's at this time.
- The Simple example uses layers 1000 x 1000 in size. The final size of the NFT can be different from the layer sizes uploaded. Layers uploaded for personal use do not have to be 1000 x 1000. Starting small with layer size and scaling up will produce quicker execution.
- Layer frequency is calculated and exported in a csv file.
- It currently works by stacking layers in the order of which they are entered into the $variation_layers array.
- There is an option for a blank NONE png to be added to a layer folder, to randomize if an NFT gets a specific attribute or not. **NONE layers do not show up in the Metadata. That attribute is skipped entirely. If you want a blank layer and for it to show up in the Metadata, do NOT name it "NONE". Name it something else so the attribute is not skipped!**
- Metadata.json specs are currently available for Isengard and Trust markets. More are being added as I go.
- Places the finished NFTs in a zipped folder. It does not zip the json or csv files. Those are found in the Metadata folder.
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
	- [My Elrond NFTs listed on Isengard](https://isengard.market/collection/WADDLE-9e36f0)
	- [My Email](mailto:mandasnfts@gmail.com)
- Personal
	- [Personal Twitter](https://twitter.com/Salamandacm)
	- [Telegram](https://t.me/Mandacm)

## Tutorial
### 1. Prepping your files
- The **files** in each subfolder should use underscores "_" in any place they would like a space in the attribute field in the metadata.
  > An attribute you would like to show up as "Blue Sky" in the metadata would be named `Blue_Sky.png`

- The generator works by using a weighted rarity system for the layers. This is included by adding a pound "#" symbol and a DECIMAL value percentage of how often you want the trait to show up.
  > You have three backgrounds: Blue Sky, Sunset, and Night Sky. If you wanted Blue Sky to be most common, you could name the layer `Blue_Sky#75.png` for the layer to appear in roughly 75% of the NFTs. If you wanted the Sunset to be rare, you could name it `Sunset#20.png` for it to appear in roughly 20% of the NFTs. Finally, Night Sky being most rare, it would be named `Night_Sky#5.png` and show up in roughly 5% of your NFTs. **Notice how all of these values add up to 100.** If you do not assign the layer a rarity value, it will default to 100% (the most common).

- Say you wanted some of your NFTs to have NO background, and it be just as rare as the Night Sky. What you could do is ***create a blank layer the same size as your other layers, and name it `NONE#5.png`***. Now, lower the frequency of another background (Drop that Blue Sky down to 70%) so all of the layers still add up to 100. This will give 5% of your NFTs no background, and just skip over adding a background value to the metadata for that 5%. 
  > This feature comes in handy with special accessories or other layers, to prevent all NFTs from having them. Use it in as few or as many folders as you want for your NFT layers!

- Folders should be named how you want the attribute value to be named. We named all of the background files above, so we would want to put them in a subfolder named "Background". Capitalization is important. Name it exactly how you want it in the metadata.
  > Metadata for a file with path `Background/Blue_Sky#70.png` would look like `Background: Blue Sky`
- Nesting folders is also an option. It comes in handy when you want certain layers to combine, and others to not. Think daytime backgrounds with daytime characters. If you wanted to make sure that no night time backgrounds got used with the day time characters, you could put them in a different subfolder underbackgrounds.
  > All daytime backgrounds could be in teh `Background` folder, but you could nest the nighttime backgrounds in a `Night` folder, with a path looking like `Background/Night/Night_Sky#5.png`. The subfolder name is not included in the metadata. the attribute for using night sky in this nested folder situation would still look like `Background: Night Sky`
- Make sure ALL files in ALL folders are the same dimensions.
- Make sure ALL files are named accordingly as you want them to appear in the metadata, following the guidelines above.
- Make sure NO files across all folders share the same name. This will make the exported CSV file return weird numbers if you have a background named Blue and a hat named Blue, it will not know which blue layer it is counting.
- Place all of these Folders in the same directory as the nftgenerator.php file you clone in step 2.

### 2. Prepping your environment
1. Install PHP on your device. To use this code, you will need to download a PHP web server on your computer. I personally use XAMPP. I highly recommend following [THIS TUTORIAL on how to install Xampp](https://www.simplilearn.com/tutorials/php-tutorial/php-using-xampp)
2. After installation, navigate to `xampp/php/` and open the `php.ini` file in an editor of choice. Search for `extension=` in the file, and uncomment the `extension=gd` line by removing the `;`. If it is not in the file, paste it in on a new line.
3. Search for `max_execution_time=` in the file and increase the time significantly. If you are generating a large collection, you can enter 600 (seconds, which equals 10 minutes). This will prevent the script from timing out.
4. In the `htdocs` folder, clone this repository to download the `config.php` and `nftgenerator.php` files.
5. Open `config.php` in the editor of your choice. 
  - `$nft_ratio['h']` & `$nft_ratio['w']` : enter the dimensions of what you want the FINAL NFT created to have, once all of the layers are combined.
  - `$marketplace` : Choose one of the available marketplace metadata standards (`TrustMarket` or `Isengard`) listed in the config file to generate the correctly formatted metadata.
  - `$nft_description` : The description you want listed for all of your NFTs.
  - `$nft_tags` : If you are using Isengard, specify the tags you want the NFTs to have (On TrustMarket you enter them manually during upload)
  - `$collection_name` : If you are using Isengard, specify the Collection name you are creating (On TrustMarket you enter it manually during collection creation)
  -`$make_transparent_BG` : A **true** or **false** (lowercase) option of if you would like to create a duplicate NFT ***without*** the background layer, making it a transparent PNG.
  - `$duplicate_nft_folder` : If you set the previous value to **true**, then choose a name for the folder you would like the transparent copies to generate in. 
  - `$variation_layers` : This is a list of arrays that hold the layer combinations you want to use for the NFTs. If there are no special batches being created and the layers are on a free-for-all combination plan: you will only create 1 array. Create the list ***in the order of how the layers SHOULD stack, from bottom to top.*** The below example would have 1 batch of layers, and they would stack (bottom to top) Background, then NextLayer, and then Subfolder, but the attribute value name would be Trait1. 
    > `$variation_layers = [array('Background', 'NextLayer', 'Trait1/Subfolder')];`
    
  - If you wanted to create multiple batches but not have to restart the generator, you can add multiple arrays within the brackets `[ ]` in the code, separating each one with a comma.
    > `$variation_layers = [array('Background', 'NextLayer', 'Trait1/Subfolder'), array('Background/Night', 'HappyLayer', 'FunLayer')];`

  - `$variation_nfts` : **For each array specified in `$variation_layers`, you want numbers in a list for this value.** This tells the code how many NFTs of each layer combination you want to make. Say you wanted to make 50 NFTs, in the first example you would use:
    > `$variation_layers = [array('Background', 'NextLayer', 'Trait1/Subfolder')];`
    > `$variation_nfts = [50];`
        
  - Or, if you had two variation of layer values, you could split them like this to make 25 of each layer combination:
    > `$variation_layers = [array('Background', 'NextLayer', 'Trait1/Subfolder'), array('Background/Night', 'HappyLayer', 'FunLayer')];`
    > `$variation_nfts = [25, 25];`
- ***Make sure the number of arrays in (`$variation_layers`) is the same as the number of NFTs per layer combination (`$variation_nfts`), or the code will crash.***
 
### 3. Running the generator

1. Search for the Xampp Control panel and launch it. Under the *Actions* column, click on `Start` for Apache (like how you tested it after download in step 1).
2. Open up a browser of your choice (Chrome, Edge, Firefox, etc). 
3. In your browser, you want to navigate to your localhost environment (the xampp server you turned on in step 1). It is launched out of the `htdocs` folder where you cloned the repository, so you won't need to add the `C:/xampp/htdocs/` portion. You are able to run the code by simply navigating to:
  > `http://localhost/ElrondNFTGenerator/nftgenerator.php`
4. If you have the code in a subfolder, you will add that subfolder to the URL as well. For example, if you wanted to run the `Simple Example` first to see how it worked, you would navigate to:
  > `http://localhost/ElrondNFTGenerator/Simple%20Example/nftgenerator.php`
5. If everthing was configured correctly, the page should return a blank screen. Open the NFTGenerator folder back up, and there should be a new Metadata folder and a zipped NFTGenerator folder that contains your NFTs!

I recommend taking a look at the Simple Example to see how the folders are organized in the config file, as well as how the rarities are organized on the image layers themselves. You can either replace your images directly in the Simple Example folder, or add them to the main folder as well. Both copies of the nft generator are the same, one with a filled out config file and one without :)
