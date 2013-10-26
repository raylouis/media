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

?>
<h1><?php echo __('Documentation'); ?></h1>

<p><?php echo __("The Media plugin is a third-party plugin for Wolf CMS. The idea is based on Wordpress' Media Library. The Media plugin provides an easy way to upload files. It also provides some hooks for plugins that need to upload images and/or resize them."); ?></p>

<h2><?php echo __('Post-installation instructions'); ?></h2>

<p><?php echo __("The plugin saves uploaded files inside the 'public' folder in a folder called 'media_uploads'. Make sure the folder exists and your web server has the appropriate rights to write new files into it."); ?></p>

<p><?php echo __("Resized images are saved in a folder named 'cache' inside the 'media_uploads' folder. Again, make sure it exists and your web server has the appropriate rights to write into it."); ?></p>

<p><?php echo __('The Status-box in the sidebar will display green status messages if everything is good to go. If there are things that have to be fixed, instructions will be displayed in red.'); ?></p>

<h2><?php echo __('Contributing'); ?></h2>

<p><?php echo __('Do you want to contribute to the development of the Media plugin?'); ?></p>

<p><?php echo __('You can report bugs and submit patches at the :github_link.', array(
    ':github_link' => '<a href="https://github.com/nicwortel/wolfcms-media" target="_blank">' . __('GitHub repository') . '</a>'
)); ?></p>

<p><?php echo __('You can translate the plugin into your language using :transifex_link.', array(
    ':transifex_link' => '<a href="https://www.transifex.com/projects/p/wolfcms-form-plugin/" target="_blank">Transifex.com</a>'
)); ?></p>
