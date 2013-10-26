<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Media
 * 
 * Use the media plugin to manage medias and other attachments in Wolf CMS.
 * 
 * @package     Plugins
 * @subpackage  media
 * 
 * @author      Nic Wortel <nic.wortel@nth-root.nl>
 * @copyright   Nic Wortel, 2013
 * @version     0.2.0
 */

?>
<h1><?php echo __('Upload new file'); ?></h1>

<div id="media">

<form id="media_edit" method="post" action="<?php echo get_url('plugin/media/upload'); ?>" enctype="multipart/form-data">
<p><input class="textbox" type="file" multiple="multiple" name="file[]" id="media_file" /></p>

<p><?php echo __('Tip: you can select multiple files at the same time.'); ?></p>

<p class="buttons">
    <input class="button" name="upload" type="submit" value="<?php echo __('Upload'); ?>" />
    <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/media'); ?>"><?php echo __('Cancel'); ?></a>
</p>
</form>

</div>
