<html>
<head></head>
<body bgcolor="grey">
Original image:<br>
<img src="img.jpg">
<?php
require "removeBorderFromImage.php";
$rbfi = new removeBoderFromImage("img.jpg");
//$rbfi = new removeBoderFromImage("img.jpg",0.95); //Color restrict factor, bigger multiplier = bigger crop area

//$rbfi->saveImage("c:\\\\cropped", "png");       	//Saves cropped image; available formats: png,gif,jpg
//$rbfi->showImage();                             	//Sends cropped picture to browser
//$cropped = $rbfi->getImage();                     //Returns gd2 internal format image

echo "<br>Cropped image:<br>";
echo '<img src="'.$rbfi->getInlineImage().'">';
?>
</body>
</html>