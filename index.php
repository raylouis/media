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

if (!defined('MEDIA')) {
    define('MEDIA', PLUGINS_ROOT.'/media');
}
if (!defined('MEDIA_IMAGES')) {
    define('MEDIA_IMAGES', URL_PUBLIC . 'wolf/plugins/media/images/');
}
if (!defined('MEDIA_UPLOAD_DIR')) {
    define('MEDIA_UPLOAD_DIR', CMS_ROOT . DS . 'public' . DS . 'media_uploads' . DS);
}
if (!defined('MEDIA_CACHE_DIR')) {
    define('MEDIA_CACHE_DIR', MEDIA_UPLOAD_DIR . 'cache' . DS);
}

Plugin::setInfos(array(
    'id'                    =>    'media',
    'title'                 =>    __('Media'),
    'description'           =>    __('Provides an easy way to upload files and resize images.'),
    'type'                  =>    'both',
    'author'                =>    'Nic Wortel',
    'version'               =>    '0.1.0',
    'website'               =>    'http://www.wolfcms.org/',
    'require_wolf_version'  =>    '0.7.4'
));

Plugin::addController('media', __('Media'), 'admin_view', true);

AutoLoader::addFolder(MEDIA.'/models');
AutoLoader::addFolder(MEDIA.'/classes');
