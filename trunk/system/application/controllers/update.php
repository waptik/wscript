<?php

class Update extends Controller {

        public function __construct ()
        {
                parent::Controller ();
                if ( ! is_admin () ) {
                	die ();
                }
        }

        public function index ()
        {
        	/**
        	 * 	NU UITA!!
        	 * 	SA SCHIMBI VERSIUNEA SI DIN INSTALL/SCHEMA.TXT
        	 * 	CAND O SCHIMBI IN UPDATE
        	 */
        	defined ( 'WS_VERSION' ) or define ( 'WS_VERSION', get_setting ( 'WS_VERSION' ) );
                switch ( WS_VERSION ) {
                	case '3.3.9 RC3'	:
                		$this->update_to_340rc3 ();
                		set_setting ( 'WS_VERSION', '3.4.0 RC3' );
                		die ( "Update performed successfully to 3.4.0 RC3" );
                		break;
                	case '3.3.9 RC2'	:
                		set_setting ( 'WS_VERSION', '3.3.9 RC3' );
                		die ( "Update performed successfully to 3.3.9 RC3" );
                		break;
                	case '3.3.8 RC2'	:
                		set_setting ( 'WS_VERSION', '3.3.9 RC2' );
                		die ( "Update performed successfully to 3.3.9 RC2" );
                		break;
                	case '3.2.7 RC2'	:
                		$this->update_to_338rc2 ();
                		set_setting ( 'WS_VERSION', '3.3.8 RC2' );
                		die ( "Update performed successfully to 3.3.8 RC2" );
                		break;
                	default			:
                		die ( "You are running the latest version" );
                		break;
                }
        }
        
        private function update_to_340rc3 () {
        	$this->db->query ( 'UPDATE `' . DBPREFIX . 'permissions` SET `label` = \'Partners\', parent_id = 0, editable = 0 WHERE `ID` =42' );
        	$this->db->query ( 'UPDATE `' . DBPREFIX . 'permissions` SET `label` = \'add\', parent_id = 42, editable = 0 WHERE `ID` =43' );
        	$this->db->query ( 'UPDATE `' . DBPREFIX . 'permissions` SET `label` = \'edit\', parent_id = 42, editable = 0 WHERE `ID` =44' );
        	$this->db->query ( 'UPDATE `' . DBPREFIX . 'permissions` SET `label` = \'delete\', parent_id = 42, editable = 0 WHERE `ID` =45' );
        	$this->db->query ( 'DROP TABLE `' . DBPREFIX . 'tag_exclusion`' );
        	$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'tags` ADD `exclude` TINYINT( 1 ) NULL DEFAULT \'0\'' );
        	$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'tags` ADD INDEX ( `exclude` )' );
        	clear_cache ();	
        }

        private function update_to_338rc2 ()
        {
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` CHANGE `type` `type` ENUM( \'normal\', \'wide\', \'iphone\', \'psp\', \'hd\', \'multi\', \'other\' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT \'normal\'' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` DROP `wallpaper`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` DROP INDEX `parent_id`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` ADD INDEX ( `parent_id` , `active` )' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` DROP INDEX `height`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` DROP INDEX `width`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` ADD INDEX ( `height` , `width` )' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers` CHANGE `file_title` `file_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `title_alias` `title_alias` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'categories` CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `meta_description` `meta_description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `meta_keywords` `meta_keywords` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL' );
//	nu au fost adaugate in install
		$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'hits` DROP INDEX `item_id`' );
		$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'hits` ADD PRIMARY KEY ( `item_id` , `ip` )' );
		$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'downloads` DROP INDEX `item_id`' );
		$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'downloads` ADD PRIMARY KEY ( `item_id` , `ip` )' );

		$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'votes` DROP INDEX `item_id`' );
		$this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'votes` ADD PRIMARY KEY ( `item_id` , `visitor_ip` ) ;' );

                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'categories` DROP INDEX `rgt`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'categories` DROP INDEX `lft`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'categories` ADD INDEX ( `lft` , `rgt` )' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'colors_rel` DROP INDEX `color_id`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'colors_rel` ADD PRIMARY KEY ( `color_id` , `item_id` )' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'tags_rel` DROP INDEX `tag_id`' );
                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'tags_rel` ADD PRIMARY KEY ( `tag_id` , `item_id` )' );
                $this->db->query ( 'UPDATE `' . DBPREFIX . 'wallpapers` set file_title =\'\', title_alias=\'\',description=\'\' WHERE parent_id>0' );

                $this->db->query ( 'ALTER TABLE `' . DBPREFIX . 'wallpapers`  ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci' );
                
                $this->db->query ( '
CREATE TABLE IF NOT EXISTS `' . DBPREFIX . 'schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `interval` int(11) NOT NULL,
  `last_run` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;' );
                
                $this->db->query ( '
CREATE TABLE IF NOT EXISTS `' . DBPREFIX . 'scheduled_wallpapers` (
  `schedule_id` int(11) NOT NULL,
  `wallpaper_id` int(11) NOT NULL,
  PRIMARY KEY (`schedule_id`,`wallpaper_id`),
  KEY `fk_scheduled_wallpapers_schedule` (`schedule_id`),
  KEY `fk_scheduled_wallpapers_wallpaper` (`wallpaper_id`)
) ENGINE=InnoDB;' );
                
                $this->db->query ( '
ALTER TABLE `' . DBPREFIX . 'scheduled_wallpapers`
  ADD CONSTRAINT `fk_scheduled_wallpapers_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `' . DBPREFIX . 'schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scheduled_wallpapers_wallpaper` FOREIGN KEY (`wallpaper_id`) REFERENCES `' . DBPREFIX . 'wallpapers` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE' );
                clear_cache ();
        }
        
}
//END