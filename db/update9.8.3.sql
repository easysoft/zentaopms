ALTER TABLE `zt_release` ADD `marker` enum('0','1') NOT NULL default '0' AFTER `name`;
ALTER TABLE `zt_doc` ADD `collector` text NOT NULL AFTER `views`;
ALTER TABLE `zt_doclib` ADD `collector` text NOT NULL AFTER `main`;
ALTER TABLE `zt_module` ADD `collector` text NOT NULL AFTER `owner`;

UPDATE `zt_block` SET `grid` = 8 WHERE `source` in ('product', 'project', 'qa') OR `block` in ('flowchart', 'assigntome');
