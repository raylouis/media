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

class MediaController extends PluginController
{
    const PLUGIN_NAME = 'media';
    
    public function __construct()
    {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/media/views/sidebar', array(
            'status_messages' => $this->getStatus()
        )));
    }

    public function clearCache()
    {
        $cache_dir = MEDIA_CACHE_DIR . '*';
        $files = glob($cache_dir);

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        redirect(get_url('plugin/media'));
    }
    
    public function delete($id)
    {
        if (!is_numeric($id)) {
            Flash::set('error', __('The file could not be found!'));
            redirect(get_url('plugin/media'));
        }

        if ($attachment = Attachment::findById($id)) {
            if ($attachment->delete()) {
                Observer::notify('media_delete', $attachment);
                Flash::set('success', __(":title has been deleted!", array(':title' => $attachment->title)));
            } else {
                Flash::set('error', __("An error has occured, therefore ':title' could not be deleted!", array(':title' => $attachment->title)));
            }
        } else {
            Flash::set('error', __('The file could not be found!'));
        }

        redirect(get_url('plugin/media'));
    }
    
    public function edit($id)
    {
        if (!is_numeric($id)) {
            Flash::set('error', __('The file could not be found!'));
            redirect(get_url('plugin/media'));
        }
        
        if (get_request_method() == 'POST') {
            return $this->_store($id);
        }

        if ($attachment = Attachment::findById($id)) {
            $this->assignToLayout('sidebar', new View('../../plugins/media/views/sidebar', array(
                'attachment' => $attachment
            )));
            $this->display('media/views/attachment/edit', array(
                'action' => 'edit',
                'attachment' => $attachment
            ));
        } else {
            Flash::set('error', __('The file could not be found!'));
            redirect(get_url('plugin/media'));
        }
        
    }
    
    public function files($order_by = NULL, $order_direction = 'asc', $page = 1)
    {
        $attachments = Attachment::findAll();
        
        $this->display('media/views/attachment/index', array(
            'attachments' => $attachments
        ));
    }
    
    public function index()
    {
        $this->files();
    }
    
    public function upload()
    {
        if (get_request_method() == 'POST') {
            return $this->_upload();
        }

        $data = Flash::get('post_data');
        $attachment = new Attachment();

        $this->display('media/views/attachment/upload');
    }
    
    private function _upload()
    {
        $errors = false;
        
        if (isset($_FILES['file'])) {
            $uploaded_files = array();

            foreach ($_FILES['file']['name'] as $key => $name) {
                $file = array(
                    'name'      => $_FILES['file']['name'][$key],
                    'type'      => $_FILES['file']['type'][$key],
                    'tmp_name'  => $_FILES['file']['tmp_name'][$key],
                    'error'     => $_FILES['file']['error'][$key],
                    'size'      => $_FILES['file']['size'][$key]
                );

                try {
                    $file = new UploadedFile($file);
                    $uploaded_files[] = $file;
                } catch (UploadException $e) {
                    switch($e->getCode()) {
                        case UPLOAD_ERR_INI_SIZE:
                            $errors[] = __('The uploaded file exceeds the maximum file size.');
                            break;
                        case UPLOAD_ERR_INI_SIZE:
                            $errors[] = __('The uploaded file exceeds the maximum file size.');
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $errors[] = __('Please select a file to upload.');
                            break;
                        default:
                            $errors[] = __('File upload failed.');
                            break;
                    }
                } catch (Exception $e) {
                    $errors[] = __('File upload failed.');
                }
            }
        }
        
        if ($errors !== false) {
            Flash::setNow('error', implode('<br />', $errors));
            
            $this->display('media/views/attachment/upload');
        } else {
            foreach ($uploaded_files as $uploaded_file) {
                Attachment::create($uploaded_file);
            }

            Flash::set('success', __('File upload successful!'));

            redirect(get_url('plugin/media'));
        }
    }
    
    private function _store($id)
    {
        $data = $_POST['attachment'];
        Flash::set('post_data', (object) $data);
        
        $errors = false;
        
        use_helper('Validate');
        use_helper('Kses');
        
        $data['title'] = kses(trim($data['title']), array());
        $data['description'] = kses(trim($data['description']), array());
        
        $attachment = Attachment::findById($id);
        $attachment->setFromData($data);
        
        if ($errors !== false) {
            Flash::setNow('error', implode('<br />', $errors));
            
            $this->display('media/views/attachment/edit', array(
                'action' => 'edit',
                'attachment' => (object) $attachment
            ));
        }
        
        Observer::notify('media_edit_before_save', $attachment);
        
        if ($attachment->save()) {
            Flash::set('success', __('Attachment has been saved!'));
        } else {
            Flash::set('error', __('Attachment has not been saved!'));
            
            $url = 'plugin/media/edit/' . $id;
            redirect(get_url($url));
        }
        
        Observer::notify('media_edit_after_save', $attachment);
        
        // save and quit or save and continue editing ?
        if (isset($_POST['commit'])) {
            redirect(get_url('plugin/media'));
        }
        else {
            redirect(get_url('plugin/media/edit/' . $attachment->id));
        }
    }

    private function getStatus()
    {
        $upload_dir = MEDIA_UPLOAD_DIR;
        $cache_dir = MEDIA_CACHE_DIR;

        $messages = array();

        if (!file_exists($upload_dir)) {
            $messages[] = array(
                'text' => __("Upload directory does not exist. Create a directory called 'media_uploads' in your public folder."),
                'type' => 'failure'
            );
        } elseif (!is_writable($upload_dir)) {
            $messages[] = array(
                'text' => __('Upload directory is not writable. Make sure the directory is writable for PHP.'),
                'type' => 'failure'
            );
        } else {
            $messages[] = array(
                'text' => __('Upload directory exists and is writable.'),
                'type' => 'success'
            );

            if (!file_exists($cache_dir)) {
                $messages[] = array(
                    'text' => __("Cache directory does not exist. Create a directory called 'cache' in your media_uploads folder."),
                    'type' => 'failure'
                );
            } elseif (!is_writable($cache_dir)) {
                $messages[] = array(
                    'text' => __('Cache directory is not writable. Make sure the directory is writable for PHP.'),
                    'type' => 'failure'
                );
            } else {
                $messages[] = array(
                    'text' => __('Cache directory exists and is writable.'),
                    'type' => 'success'
                );
            }
        }

        return $messages;
    }
}
