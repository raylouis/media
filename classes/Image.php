<?php

/**
 * Image class
 *
 * @author      Nic Wortel <nic.wortel@nth-root.nl>
 * @copyright   Nic Wortel, 2013
 * 
 */

class Image extends File
{
    private $image = null;
    private $image_type = null;

    /**
     * Returns the image's width.
     * 
     * @return  int     Image width in pixels
     */
    public function getWidth()
    {
        if (is_null($this->image)) {
            $this->load();
        }
        return imagesx($this->image);
    }

    /**
     * Returns the image's height.
     * 
     * @return  int     Image height in pixels
     */
    public function getHeight()
    {
        if (is_null($this->image)) {
            $this->load();
        }
        return imagesy($this->image);
    }

    public function getImageType()
    {
        if (!isset($this->image_type)) {
            $image_info = getimagesize($this->location);
            $this->image_type = $image_info[2];
        }

        return $this->image_type;
    }

    /**
     * Loads the image resource.
     * @return  boolean     TRUE on success, FALSE on failure
     */
    public function load()
    {
        $info = getimagesize($this->getLocation());
        $this->type = $info[2];

        if ($this->type == IMAGETYPE_JPEG) {
            if ($this->image = imagecreatefromjpeg($this->location)) {
                return true;
            }
        } elseif ($this->type == IMAGETYPE_GIF) {
            if ($this->image = imagecreatefromgif($this->location)) {
                return true;
            }
        } elseif ($this->type == IMAGETYPE_PNG) {
            if ($this->image = imagecreatefrompng($this->location)) {
                return true;
            }
        }
    }

    /**
     * Returns a cropped Image based on the provided parameters.
     *
     * This Image object can be saved or rendered directly.
     * 
     * @param   int     $width  The desired width
     * @param   int     $height The desired height
     * @return  Image           The cropped Image object
     */
    public function crop($width, $height)
    {
        $width = (int) $width;
        $height = (int) $height;

        $cur_width  = $this->getWidth();
        $cur_height = $this->getHeight();
        
        $cur_aspect = $cur_width / $cur_height;
        $new_aspect = $width / $height;

        if ($cur_aspect >= $new_aspect) {
            $new_height = $height;
            $new_width = $cur_width / ($cur_height / $height);
        } else {
            $new_width = $width;
            $new_height = $cur_height / ($cur_width / $width);
        }

        $cropped_image = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $cropped_image,
            $this->image,
            (int) 0 - ($new_width - $width) / 2, // Center the image horizontally
            (int) 0 - ($new_height - $height) / 2, // Center the image vertically
            0,
            0,
            (int) $new_width,
            (int) $new_height,
            (int) $cur_width,
            (int) $cur_height
        );

        $cropped = new Image();
        $cropped->image = $cropped_image;

        return $cropped;
    }

    /**
     * Returns a thumbnail of the Image based on the provided parameters.
     *
     * This Image object can be saved or rendered directly.
     * 
     * @param   int     $width  The desired width
     * @param   int     $height The desired height
     * @return  Image           The thumbnail Image
     */
    public function thumbnail($width, $height)
    {
        $width = (int) $width;
        $height = (int) $height;

        $cur_width  = $this->getWidth();
        $cur_height = $this->getHeight();
        
        $ratio_width = $width / $cur_width;
        $ratio_height = $height / $cur_height;
        
        if ($ratio_width < $ratio_height) {
            $new_width = $width;
            $new_height = round($cur_height / $cur_width * $width);;
            $offset_y = ceil(($new_width - $new_height) / 2);
            $offset_x = 0;
        } elseif ($ratio_width > $ratio_height) {
            $new_height = $height;
            $new_width = round($cur_width / $cur_height * $height);
            $offset_x = ceil(($new_height - $new_width) / 2);
            $offset_y = 0;
        } else {
            $new_width = $width;
            $new_height = $height;
            $offset_x = $offset_y = 0;
        }

        $offset_x = 0 - ($new_width - $width) / 2; // Center the image horizontally
        $offset_y = 0 - ($new_height - $height) / 2; // Center the image vertically
        
        $thumbnail_image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($thumbnail_image, 255, 255, 255);
        imagefill($thumbnail_image, 0, 0, $white);
        
        imagecopyresampled(
            $thumbnail_image,
            $this->image,
            $offset_x,
            $offset_y,
            0,
            0,
            $new_width, $new_height,
            $cur_width, $cur_height
        );

        $thumbnail = new Image();
        $thumbnail->image = $thumbnail_image;

        return $thumbnail;
    }

    /**
     * Saves the current Image file.
     * 
     * @param  string   $location   The location where the file is to be saved
     * @param  integer  $image_type The desired image_type
     * @param  integer  $quality    The image's quality (0 - 100, only for IMAGETYPE_JPEG)
     *
     * @throws Exception If $location is required but not provided.
     * @throws Exception If $image_type is required but not provided.
     * 
     * @return boolean              TRUE on success, FALSE on failure
     */
    public function save($location = null, $image_type = null, $quality = 100)
    {
        if (is_null($location)) {
            if ($this->location != '') {
                $location = $this->location;
            } else {
                throw new Exception('Location required!');
            }
        }

        if (is_null($image_type)) {
            if ($this->location != '') {
                $image_type = $this->getImageType();
            } else {
                throw new Exception('Define image type!');
            }
        }

        if ($image_type == IMAGETYPE_JPEG) {
            if (!imagejpeg($this->image, $location, $quality)) {
                return false;
            }
        } elseif ($image_type == IMAGETYPE_GIF) {
            if (!imagegif($this->image, $location)) {
                return false;
            }
        } elseif ($image_type == IMAGETYPE_PNG) {
            if (!imagepng($this->image, $location)) {
                return false;
            }
        } else {
            return false;
        }

        $this->location = $location;
        return true;
    }
}
