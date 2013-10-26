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

$PDO = Record::getConnection();

$PDO->exec("CREATE  TABLE IF NOT EXISTS `".TABLE_PREFIX."media_attachment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NULL ,
  `description` TEXT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `filename` VARCHAR(255) NOT NULL ,
  `mime_type` VARCHAR(255) NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `updated_on` DATETIME NOT NULL ,
  `created_by_id` INT UNSIGNED NOT NULL ,
  `updated_by_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB");
