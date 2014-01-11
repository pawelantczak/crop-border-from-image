Rmove Border From Image
======================

This class can be used to crop images by removing common color borders.

It can load a picture and scan its border pixels to determine how thick is the border.

The sensibility level that is used to determine which pixels are in the border is configurable.

The class can crop the image be keeping on the interior inside the image border.

The resulting cropped image can be displayed in JPEG, PNG or GIF format, saved to a file or embedded in an HTML page. 

Award
-------

Class won "[PHP Programming Innovation Award](http://www.phpclasses.org/award/innovation/)" on phpclasses.org. [Link](http://www.phpclasses.org/package/5181-PHP-Crop-images-by-removing-common-color-borders.html). 

Usage
-----
    require "removeBorderFromImage.php";
    $rbfi = new removeBoderFromImage("img.jpg",0.95); //Color restrict factor, bigger multiplier = bigger crop area
    $rbfi->saveImage("c:\\\\cropped", "png");         //Saves cropped image; available formats: png,gif,jpg
    $rbfi->showImage();                               //Sends cropped picture to browser
    $cropped = $rbfi->getImage();                     //Returns gd2 internal format image

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/pawelantczak/crop-border-from-image/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

