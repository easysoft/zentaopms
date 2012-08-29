# zt-task table.
ALTER TABLE `zt_task` ADD INDEX ( `project` );
ALTER TABLE `zt_task` ADD INDEX ( `status` );

# product table.
ALTER TABLE `zt_product` ADD INDEX ( `order` );

# projectProduct table.
ALTER TABLE `zt_projectProduct` DROP PRIMARY KEY;
ALTER TABLE `zt_projectProduct` ADD INDEX ( `product` );
ALTER TABLE `zt_projectProduct` ADD INDEX ( `project` );

# zt_team 
 ALTER TABLE `zt_team` ADD INDEX ( `project` );

# zt_module
ALTER TABLE `zt_module` ADD INDEX ( `root` );
ALTER TABLE `zt_module` ADD INDEX ( `type` );

# zt_user
ALTER TABLE `zt_user` DROP INDEX `company`;
ALTER TABLE `zt_user` ADD INDEX ( `company` );
ALTER TABLE `zt_user` ADD INDEX ( `dept` );

# zt_action
ALTER TABLE `zt_action` ADD INDEX ( `date` );

# zt_history
ALTER TABLE `zt_history` ADD INDEX ( `action` ); 

# zt_file
ALTER TABLE `zt_file` ADD INDEX ( `objectType` );
ALTER TABLE `zt_file` ADD INDEX ( `objectID` );
