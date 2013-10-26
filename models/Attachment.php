<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Media
 * 
 * The Media plugin is a third-party plugin for Wolf CMS that provides an easy way to upload files and resize images.
 * 
 * @package     Plugins
 * @subpackage  media
 * 
 * @author      Nic Wortel <nic.wortel@nth-root.nl>
 * @copyright   Nic Wortel, 2013
 * @version     0.1.0
 */

use_helper('ActiveRecord');

class Attachment extends ActiveRecord
{
    const TABLE_NAME = 'media_attachment';

    public $id;
    public $title = '';
    public $description;
    public $slug = '';

    public $filename = '';
    public $mime_type = '';
    
    protected $created_on;
    protected $updated_on;
    public $created_by_id;
    public $updated_by_id;

    private $file = null;

    public function beforeDelete()
    {
        $this->getFile()->delete();
        Observer::notify('media_attachment_before_delete', $this);
        return true;
    }
    
    public function beforeInsert()
    {
        if ($this->slug == '') {
            $this->slug         = Node::toSlug($this->title);
        }
        
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave()
    {
        $this->updated_on       = date('Y-m-d H:i:s');
        $this->updated_by_id    = AuthUser::getRecord()->id;
        
        return true;
    }

    public function date($format='%a, %e %b %Y', $which_one='created')
    {

        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
        }
        
        if ($which_one == 'update' || $which_one == 'updated') {
            return strftime($format, strtotime($this->updated_on));
        }
        else {
            return strftime($format, strtotime($this->created_on));
        }
    }
    
    public static function findAll()
    {
        return self::find(array(
            'order' => 'created_on DESC'
        ));
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => array(
                'id = :id',
                ':id' => $id
            ),
            'limit' => 1
        ));
    }

    public static function findBySlug($slug)
    {
        return self::find(array(
            'where' => array(
                'slug = :slug',
                ':slug' => $slug
            ),
            'limit' => 1
        ));
    }

    public static function findImages()
    {
        return self::find(array(
            'order' => 'created_on DESC',
            'where' => 'mime_type LIKE "image%"'
        ));
    }
    
    public function getColumns()
    {
        return array(
            'id',
            'title',
            'description',
            'slug',
            'filename',
            'mime_type',
            'width',
            'height',
            'created_on',
            'updated_on',
            'created_by_id',
            'updated_by_id'
        );
    }

    public function getExtension()
    {
        return $this->getFile()->getExtension();
    }

    public function getFile()
    {
        if (is_null($this->file)) {
            if (!empty($this->filename)) {
                $this->file = File::open(MEDIA_UPLOAD_DIR . $this->filename);
            }
        }

        return $this->file;
    }

    public function isImage()
    {
        return (boolean) File::isImage($this->getFile()->getLocation());
    }

    public static function create($uploaded_file, $title = null)
    {
        if ($uploaded_file instanceOf UploadedFile && !$uploaded_file->hasErrors()) {
            if (is_null($title) || trim($title) == '') {
                $title = $uploaded_file->getFilename(true);
            }

            $original_slug = Node::toSlug($title);
            $slug = $original_slug;
            $suffix_number = null;

            while ($existing = Attachment::findBySlug($slug)) {
                if ($uploaded_file->getHash() != $existing->getFile()->getHash()) {
                    if (is_null($suffix_number)) {
                        $suffix_number = 2;
                    } else {
                        $suffix_number++;
                    }

                    $slug = $original_slug . '-' . $suffix_number;
                } else {
                    return $existing;
                }
            }

            $filename = $slug . '.' . $uploaded_file->getExtension();

            $uploaded_file->move(MEDIA_UPLOAD_DIR . $filename);
            $attachment = new Attachment();
            $attachment->title = $title;
            $attachment->slug = $slug;
            $attachment->file = $uploaded_file;
            $attachment->filename = $filename;
            $attachment->mime_type = $uploaded_file->getMimeType();
            $attachment->save();

            return $attachment;
        } else {
            return false;
        }
    }

    private function resize($resize_method, $width, $height = null)
    {
        if (!$this->isImage()) {
            return $this->getFile();

        } elseif ($resize_method == 'crop') {
            if (is_null($height)) {
                $height = $width;
            }

            $new_location = MEDIA_CACHE_DIR . $this->getFile()->getFilename(true) . '-cropped-' . $width . 'x' . $height . '.' . $this->getExtension();

            if (File::exists($new_location)) {
                return File::open($new_location);
            } else {
                $cropped = $this->getFile()->crop($width, $height);
                $cropped->save($new_location, $this->getFile()->getImageType());

                return $cropped;
            }

        } elseif ($resize_method == 'thumbnail') {
            if (is_null($height)) {
                $height = $width;
            }

            $new_location = MEDIA_CACHE_DIR . $this->getFile()->getFilename(true) . '-thumbnail-' . $width . 'x' . $height . '.' . $this->getExtension();
            
            if (File::exists($new_location)) {
                return File::open($new_location);
            } else {
                $thumbnail = $this->getFile()->thumbnail($width, $height);
                $thumbnail->save($new_location, $this->getFile()->getImageType());

                return $thumbnail;
            }
        }
    }

    public function url($resize_method = null, $width = null, $height = null)
    {
        if ($this->isImage() && (!is_null($width) || !is_null($height))) {
            $file = $this->resize($resize_method, $width, $height);

        } else {
            $file = $this->getFile();

        }

        return URL_PUBLIC . ltrim(str_replace("\\", '/', str_replace(CMS_ROOT, '', $file->getLocation())), '/');
    }

    public function html_img($resize_method = null, $width = null, $height = null)
    {
        return '<img src="' . $this->url($resize_method, $width, $height) . '">';
    }
}
