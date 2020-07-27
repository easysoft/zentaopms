ALTER TABLE `zt_doccontent` CHANGE `content` `content` longtext NOT NULL AFTER `digest`;

update zt_storystage as a, zt_story as b set a.stage = 'closed', b.stage = 'closed' where a.story = b.id and b.status = 'closed';

update zt_story set stage = 'closed' where status = 'closed';
