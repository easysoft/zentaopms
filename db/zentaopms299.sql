UPDATE `zt_doc` SET `type` = 'text' WHERE `type` = 'url';

UPDATE `zt_doclib` SET `acl` = 'private' WHERE `type` = 'custom' and `acl` = 'custom';
UPDATE `zt_doclib` SET `acl` = 'default' WHERE `type` = 'product' AND `acl` = 'custom';
