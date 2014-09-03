-- schools
ALTER TABLE `schools` CHANGE `lat` `lat` DECIMAL( 11, 8 ) NULL DEFAULT NULL;
ALTER TABLE `schools` CHANGE `lng` `lon` DECIMAL( 11, 8 ) NULL DEFAULT NULL;
ALTER TABLE `schools` CHANGE `county` `county` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `schools` CHANGE `district` `district` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `schools` CHANGE `district` `district` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `schools` CHANGE `zip` `zip` VARCHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
-- sessions
ALTER TABLE `sessions` CHANGE `session_id` `session_id` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
ALTER TABLE `sessions` ADD `session_fingerprint` VARCHAR( 255 ) NOT NULL AFTER `session_id`;
ALTER TABLE `sessions` CHANGE `member_id` `member_id` INT( 10 ) UNSIGNED NOT NULL;
-- tars
ALTER TABLE `tars` ADD `school_id` INT( 10 ) UNSIGNED NULL DEFAULT NULL AFTER `teacher_id`;
ALTER TABLE `tars` CHANGE `county_name` `county_name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `tars` CHANGE `district_name` `district_name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `tars` CHANGE `school_name` `school_name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `tars` CHANGE `school_city` `school_city` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `tars` CHANGE `school_zip` `school_zip` VARCHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `tars` CHANGE `category_name` `category_name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `tars` CHANGE `volunteer` `volunteer` INT( 10 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `tars` CHANGE `complete_time` `complete_time` DATETIME NULL DEFAULT NULL; 
-- tars_emails
ALTER TABLE `tars_emails` CHANGE `county_name` `county_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `district_name` `district_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `school_name` `school_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `school_city` `school_city` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `school_zip` `school_zip` VARCHAR(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `subject` `subject` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `category` `category` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `category_name` `category_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `best_times` `best_times` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `months` `months` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `details` `details` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `grades` `grades` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL, CHANGE `students` `students` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `tars_emails` CHANGE `complete_time` `complete_time` DATETIME NULL DEFAULT NULL;
ALTER TABLE `tars_emails` CHANGE `email_status` `email_status` ENUM( 'pending', 'complete', 'admin deleted', 'teacher deleted', 'canceled' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'pending';
-- teachers
ALTER TABLE `teachers` CHANGE `password` `password` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `teachers` CHANGE `activation_code` `activation_code` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
-- volunteers
ALTER TABLE `volunteers` CHANGE `password` `password` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `volunteers` CHANGE `activation_code` `activation_code` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `volunteers` CHANGE `address` `address` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `volunteers` ADD `city` VARCHAR( 255 ) NOT NULL AFTER `address` ,
ADD `state` VARCHAR( 100 ) NOT NULL AFTER `city` ,
ADD `zip` VARCHAR( 5 ) NOT NULL AFTER `state` ,
ADD `lat` DECIMAL( 11, 8 ) NOT NULL AFTER `zip` ,
ADD `lon` DECIMAL( 11, 8 ) NOT NULL AFTER `lat`;