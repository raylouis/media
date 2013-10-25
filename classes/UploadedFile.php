<?php

/**
 * UploadedFile class
 *
 * @author      Nic Wortel <nic.wortel@nth-root.nl>
 * @copyright   Nic Wortel, 2013
 * 
 */

class UploadedFile extends File
{
    private $filename = '';
    private $error;
    private $mime_type = '';

    /**
     * Constructs a new UploadedFile object based on the $_FILES array.
     * 
     * @param   array   $file   $_FILES array
     */
    public function __construct($file)
    {
        if (is_array($file)) {
            $this->filename     = $file['name'];
            $this->location     = $file['tmp_name'];
            $this->error        = $file['error'];
            $this->mime_type    = $file['type'];
        }
    }

    /**
     * Returns the error code.
     * 
     * @return  integer         The error code (0 if no errors)
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Returns the original file extension, as all uploaded files get .tmp after their filenames.
     * @return  string          The file's extension
     */
    public function getExtension()
    {
        return strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
    }

    /**
     * Returns the file's filename.
     * @param   boolean     $strip_extension    Set to true if you want to strip the extension
     * @return  string                          The filename
     */
    public function getFilename($strip_extension = false)
    {
        if ($strip_extension) {
            $ext = '.' . $this->getExtension();
        } else {
            $ext = '';
        }

        return basename($this->filename, $ext);
    }

    /**
     * Returns the file's MIME type.
     *
     * Caution: the MIME type is provided by the client side, and is therefore not 100% reliable.
     * 
     * @return  string                          The MIME type
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Returns wether or not the uploaded file contains errors.
     * @return  boolean                         TRUE if errors, FALSE if no errors
     */
    public function hasErrors()
    {
        return (bool) $this->error;
    }


    /**
     * Moves the uploaded file from it's temporary location to permanent location.
     *
     * As it uses the PHP function move_uploaded_file, it will only work on files that have been uploaded
     * via PHP's upload mechanism - providing an extra layer of security.
     * 
     * @param   string  $location   The location where the file should be moved to
     * @return  boolean             TRUE on success, FALSE on failure
     */
    public function move($location)
    {
        if (!$this->hasErrors()) {
            if (move_uploaded_file($this->location, $location)) {
                $this->location = $location;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
