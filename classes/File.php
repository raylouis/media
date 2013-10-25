<?php

/**
 * File class
 *
 * @author      Nic Wortel <nic.wortel@nth-root.nl>
 * @copyright   Nic Wortel, 2013
 * 
 */

class File
{
    protected $location = '';

    /**
     * Returns the directory path of the file.
     * 
     * @return  string      Full path of the file's directory
     */
    public function getDirectory()
    {
        return dirname($this->location);
    }

    /**
     * Returns the extension of the file.
     * 
     * @return  string      The file's extension (without preceeding dot)
     */
    public function getExtension()
    {
        return pathinfo($this->location, PATHINFO_EXTENSION);
    }

    /**
     * Returns the filename of the file.
     *
     * Removes the extension when $remove_extension is true.
     * 
     * @param   boolean     $remove_extension   Flag for leaving out the extension
     * @return  string                          The file's filename
     */
    public function getFilename($remove_extension = false)
    {
        if ($remove_extension) {
            $ext = '.' . $this->getExtension();
        } else {
            $ext = '';
        }

        return basename($this->location, $ext);
    }

    /**
     * Returns the full path of the file.
     * 
     * @return  string      The file's location (path)
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Returns a hash of the file's contents.
     * 
     * @param   string  $algorithm  The hashing algorithm that should be used
     * @return  string              The hash
     */
    public function getHash($algorithm = 'md5')
    {
        return hash_file($algorithm, $this->location);
    }

    /**
     * Returns the file's size in bytes.
     * 
     * @return  mixed               The file's size
     */
    public function getSize()
    {
        $size = filesize($this->location);

        return $size;
    }

    /**
     * Returns TRUE if file is an image, and FALSE if it is not.
     * 
     * @param   string      $location   The file's location
     * @return  boolean                 TRUE if file is an image, and FALSE if it is not
     */
    public static function isImage($location)
    {
        if (file_exists($location)) {
            if (getimagesize($location)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns TRUE if file exists, and FALSE if it does not.
     * 
     * @param   string      $location   The file's location
     * @return  boolean                 TRUE if file exists, FALSE if it does not
     */
    public static function exists($location)
    {
        return file_exists($location);
    }

    /**
     * Returns a File (or Image) object.
     * 
     * @param   string  $location   The location for the file
     * @return  mixed               File / Image object or false
     */
    public static function open($location)
    {
        $location = realpath($location);

        if (File::exists($location)) {
            if (File::isImage($location)) {
                $file = new Image();
            } else {
                $file = new File();
            }

            $file->location = realpath($location);

            return $file;
        } else {
            return false;
        }
    }

    /**
     * Copies the current file to another location.
     * 
     * @param   string  $location   The location where the file should be copied to
     *
     * @throws  Exception If the provided $location argument is empty.
     * 
     * @return  mixed               File / Image object or false
     */
    public function copy($location)
    {
        if ($location == '') {
            throw new Exception('$location is required.');
        }

        if (copy($this->location, $location)) {
            return File::open($location);
        } else {
            return false;
        }
    }

    /**
     * Moves the current file to another location.
     * 
     * @param   string  $location   The location where the file should be moved to
     * @return  boolean             TRUE on success, FALSE on failure
     */
    public function move($location)
    {
        $new_location = $location;

        if (rename($this->location, $new_location)) {
            $this->location = $new_location;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Renames the current file.
     * Similar to move(), but only moves the file within the same directory.
     * 
     * @param   string  $filename   The new filename (including extension)
     * @return  boolean             TRUE on success, FALSE on failure
     */
    public function rename($filename)
    {
        $new_location = $this->getDirectory() . DIRECTORY_SEPARATOR . $filename;
        return (boolean) $this->move($new_location);
    }

    /**
     * Saves the current file.
     * @param   string  $location   The location where the file is to be saved
     * @return  boolean             TRUE on success, FALSE on failure
     *
     * @todo implement
     */
    public function save($location = null)
    {
        return false;
    }

    /**
     * Deletes the current file.
     * 
     * @return  boolean             TRUE on success, FALSE on failure 
     */
    public function delete()
    {
        return (boolean) unlink($this->location);
    }
}
