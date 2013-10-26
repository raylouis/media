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
 * @version     0.2.0
 */

?>
<h1><?php echo __('Media library'); ?></h1>

<table class="attachment list">
    <thead>
        <tr>
            <th class="thumbnail">
            </th>
            <th class="title">
                <?php echo __('Title'); ?>
            </th>
            <th class="author">
                <?php echo __('Author'); ?>
            </th>
            <th class="date">
                <?php echo __('Date'); ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($attachments as $attachment): ?>
        <tr>
            <td class="thumbnail">
                <?php if ($attachment->isImage()): ?>
                <?php echo $attachment->html_img('thumbnail', 60); ?>
                <?php endif; ?>
            </td>
            <td class="title">
                <a href="<?php echo get_url('plugin/media/edit', $attachment->id); ?>"><?php echo (strlen($attachment->title) > 0) ? $attachment->title : __('(no title)'); ?></a><br />
                <?php echo __(':filetype-file', array(':filetype' => strtoupper($attachment->getExtension()))); ?><br />
                <span class="actions">
                    <a href="<?php echo get_url('plugin/media/edit', $attachment->id); ?>"><?php echo __('Edit'); ?></a> |
                    <a href="<?php echo get_url('plugin/media/delete', $attachment->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :title?', array(':title' => $attachment->title)); ?>');"><?php echo __('Delete'); ?></a> |
                    <a href="<?php echo $attachment->url(); ?>"><?php echo __('View'); ?></a>
                </span>
            </td>
            <td class="author">
                <?php echo User::findById($attachment->created_by_id)->name; ?>
            </td>
            <td class="date">
                <?php echo $attachment->date('%e %B %Y %H:%M', 'created_on'); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
