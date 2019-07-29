ALTER TABLE `zt_translation`
CHANGE `refer` `referer` text COLLATE 'utf8_general_ci' NOT NULL AFTER `value`,
CHANGE `translationTime` `translatedTime` datetime NOT NULL AFTER `translator`,
CHANGE `reviewTime` `reviewedTime` datetime NOT NULL AFTER `reviewer`;
