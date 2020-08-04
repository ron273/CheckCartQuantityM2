# CheckCartQuantityM2
## Introduction
This is a Magento 2 module that allows you to limit checkout only when the contents of the cart are a multiple of X. This is very useful when you are selling stuff like beer or wine, where your shipping is in boxes with fixed number of items (for wine usually 6). You also have the ability to exclude some Categories from this rule.

## Screenshots
<img src="https://vecino.skyberatedev.nl/pub/media/screenshot1.jpg" alt="Screenshot Backend" />

<img src="https://vecino.skyberatedev.nl/pub/media/screenshot2.jpg" alt="Screenshot Frontend" />

## Installation
1. Download package from Github
2. Unzip and copy to your Magento 2 root (usually public_html)
3. Open up a terminal and SSH into your Magento root folder and use the following commands:
   - `$ bin/magento setup:upgrade`
   - `$ bin/magento setup:static-content:deploy`
   - `$ bin/magento cache:clean`
   - `$ bin/magento cashe:flush`
4. Your module should now be activated.

## Credits
The original M1 version of this module was developed by [ceckoslab](https://github.com/ceckoslab) in 2015. This M1 module was used to create the M2 version.

## Disclaimer
This is a fully functioning Magento 2 Module. However as I am not a professional developer, please review the code before using this in production. If there are any suggestions to improve the functionality or security of this code, please let me know.
