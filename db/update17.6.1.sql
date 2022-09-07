ALTER TABLE `zt_host` ADD `type` varchar(30) NOT NULL DEFAULT 'normal' AFTER `admin`;
ALTER TABLE `zt_vmtemplate` ADD `type` varchar(30) NOT NULL DEFAULT 'normal' AFTER `hostID`;
