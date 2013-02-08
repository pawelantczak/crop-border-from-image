<?php
/*******************************************************************************
* Software: removeBoderFromImage                                               *
* Version:  1.1                                                                *
* Date:     2009-04-16                                                         *
* Author:   Pawel Antczak                                                      *
* License:  Freeware                                                           *
* You may use, modify and redistribute this software as you wish.              *
*                                                                              *
* Change log:                                                                  *
* 1.1 - added validation, if image is corrupted, saveImage will return false   *
*******************************************************************************/

class removeBoderFromImage {
    /* Original image */
    private $image;
    /* Image MIME type */
    private $imageType;
    /* Peak border color*/
    private $peakColor;
    /* Image height */
    private $imageHeight;
    /* Image weight */
    private $imageWidth;
    /*Image name*/
    private $imageName;
    /* Cropped image*/
    private $croppedImage;

    /** Class constructor
    * @param string $image - original image path/URL
    * @param string $colorFactor - color mulitiplier; lower factor = smaller crop area
    */
    public function __construct($image, $colorMultiplier = 0.95) {
        $this->imageName = $image;
        if (getimagesize($this->imageName)) {
            $this->image = imagecreatefromstring(file_get_contents($image));
            $imageDetails = GetImageSize($image);
            $this->imageHeight = $imageDetails[1];
            $this->imageWidth = $imageDetails[0];
            $this->imageType = $imageDetails['mime'];
            $this->peakColor = $this->getPeakColor() * $colorMultiplier;
            $this->removeBorder(); }
        else
            $this->croppedImage = false;
    }

    /*
     * Internal function
     * Calculates crop area dimension
     */
    private function removeBorder() {
        $newStartX = $this->imageHeight;
        $newStartY = $this->imageWidth;
        $newStopX = 0;
        $newStopY = 0;

        for ($i = 0 ; $i < $this->imageWidth ; $i++) {
            for ($ii = 0 ; $ii < $this->imageHeight ; $ii++) {
                if ($this->getPixelColor($this->image, $i, $ii) < $this->peakColor) {
                    if ($i > $newStopX) $newStopX = $i;
                    if ($ii > $newStopY) $newStopY = $ii; }
            }
        }
        for ($i = 0 ; $i < $this->imageWidth ; $i++) {
            for ($ii = 0 ; $ii < $this->imageHeight ; $ii++) {
                if ($this->getPixelColor($this->image, $i, $ii) < $this->peakColor) {
                    if ($i < $newStartX) $newStartX = $i;
                    if ($ii < $newStartY) $newStartY = $ii; }
            }
        }

        $this->cropImage($newStartX,$newStartY,$newStopX,$newStopY);
    }
    /*
     * Internal function
     * Returns color at pixel
     */
    private function getPixelColor($image, $x, $y) {
        return imagecolorat($image, $x, $y);
    }

    /*
     * Internal function
     * Copy area from image to new one
     */
    private function cropImage($newStartX, $newStartY, $newStopX, $newStopY) {
        $newwidth = $this->imageWidth;
        $newheight = $this->imageHeight;
        $cropped = imagecreatetruecolor($newStopX - $newStartX, $newStopY - $newStartY);
        imagecopyresized($cropped, $this->image, 0, 0, $newStartX, $newStartY, $newStopX - $newStartX, $newStopY - $newStartY, $newStopX - $newStartX, $newStopY - $newStartY);
        $this->croppedImage = $cropped;
    }

    /*
     * Internal function
     * Retuns image avarage color
     */
    private function getPeakColor() {
        $palette = array();
        for ($i = 0 ; $i < $this->imageWidth ; $i++) {
            for ($ii = 0 ; $ii < $this->imageHeight ; $ii++) {
                $palette[] += $this->getPixelColor($this->image, $i, $ii);
            }
        }
        return round(array_sum($palette)/count($palette));
    }

    /*
     * Removes border and send image to browser
     */
    public function showImage() {
        header('Content-type: image/jpeg');
        imagejpeg($this->croppedImage);
    }

    /*
     * Removes border and save file
     * @param string $newImagePath - path to save new file
     * @param string $imageType - "jpg", "gif", "png"
     */
    public function saveImage($newImagePath, $imageType = "jpg") {
        if ($this->croppedImage == false)
            return false;
        $newFileNameTemp = explode(".",$newImagePath);
        $newFileName = $newFileNameTemp[0].".".$imageType;
        switch($imageType) {
            case "jpg":
                return imagejpeg($this->croppedImage,$newFileName);
            case "png":
                return imagepng($this->croppedImage,$newFileName);
            case "gif":
                return imagegif($this->croppedImage,$newFileName);
            default:
                return imagejpeg($this->croppedImage,$newFileNameTemp[0].".jpg");
            }

        }

    /*
     * Removes border and returns image
     */
    public function getImage() {
        return $this->croppedImage;
    }

    /*
     * Returns image coded in base64 to show it inline
     */
    public function getInlineImage() {
        ob_start();
        imagejpeg($this->croppedImage);
        $inlineImage = ob_get_clean();
        return "data:image/jpeg;base64,".base64_encode($inlineImage);

    }
}
?>
