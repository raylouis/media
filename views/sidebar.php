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
<p class="button">
    <a href="<?php echo get_url("plugin/media"); ?>">
        <img width="32" height="32" src="<?php echo MEDIA_IMAGES; ?>file-unknown-32.png" align="middle" alt="<?php echo __('Files'); ?>" />
        <?php echo __('Files'); ?>
    </a>
</p>
<p class="button">
    <a href="<?php echo get_url("plugin/media/upload"); ?>">
        <img width="32" height="32" src="<?php echo MEDIA_IMAGES; ?>action-upload-32.png" align="middle" alt="<?php echo __('Upload new file'); ?>" />
        <?php echo __('Upload new file'); ?>
    </a>
</p>
<p class="button">
    <a href="<?php echo get_url("plugin/media/clearCache"); ?>">
        <img width="32" height="32" src="<?php echo MEDIA_IMAGES; ?>action-delete-32.png" align="middle" alt="<?php echo __('Clear cache'); ?>" />
        <?php echo __('Clear cache'); ?>
    </a>
</p>

<?php if (isset($attachment)): ?>
<div class="box">
    <h2><?php echo __('File info'); ?></h2>
    <p><?php echo __('File name'); ?>: <?php echo $attachment->getFile()->getFilename(); ?></p>
    <p><?php echo __('File type'); ?>: <?php echo strtoupper($attachment->getFile()->getExtension()); ?></p>
    <p><?php echo __('File size'); ?>: <?php echo $attachment->getFile()->getSize(true); ?></p>
    <p><?php echo __('Uploaded on'); ?>: <?php echo $attachment->date('%e %B %Y %H:%M', 'created'); ?></p>
    <?php if ($attachment->isImage()): ?>
    <p><?php echo __('Resolution'); ?>: <?php echo $attachment->getFile()->getWidth(); ?> x <?php echo $attachment->getFile()->getHeight(); ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (isset($status_messages)): ?>
<div class="box">
    <h2><?php echo __('Status'); ?></h2>
    <?php foreach ($status_messages as $status_message): ?>
    <p class="status-<?php echo $status_message['type']; ?>"><?php echo $status_message['text']; ?></p>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="box">
    <h2><?php echo __('Media plugin'); ?></h2>
    <p><?php echo __('The Media plugin is a third-party plugin for Wolf CMS that provides an easy way to upload files and resize images.'); ?></p>
    <p><?php echo __('Created by :name', array(':name' => 'Nic Wortel')); ?></p>
</div>
