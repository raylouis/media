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
<h1><?php echo __('Edit'); ?></h1>

<div id="attachment">

<form id="attachment_edit" method="post" action="<?php echo get_url('plugin/media/edit/'.$attachment->id); ?>">
<p><table class="attachment">
    <tr>
        <td class="label"><label for="attachment_title"><?php echo __('Title'); ?></label></td>
        <td class="field"><input class="textbox" type="text" name="attachment[title]" id="attachment_title" value="<?php echo $attachment->title; ?>" /></td>
    </tr>
    <tr>
        <td class="label"><label for="attachment_description"><?php echo __('Description'); ?></label></td>
        <td class="field"><textarea name="attachment[description]" id="attachment_description"><?php echo $attachment->description; ?></textarea></td>
    </tr>
</table></p>

<p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
    <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
    <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/media'); ?>"><?php echo __('Cancel'); ?></a>
</p>
</form>

</div
