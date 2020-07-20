ALTER TABLE `zt_doccontent` CHANGE `content` `content` longtext NOT NULL AFTER `digest`;

update zt_story set stage = 'closed' where status = 'closed';
