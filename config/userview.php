<?php
$config->userview = new stdClass();
$config->userview->relatedTables           = [TABLE_PRODUCT, TABLE_PROJECT, TABLE_TEAM, TABLE_ACL, TABLE_STAKEHOLDER, TABLE_PROJECTADMIN, TABLE_PROJECTPRODUCT]; // userview 相关表。userview related tables.
$config->userview->relatedTablesUpdateTime = 0; // userview 相关表的更新时间。The last update time of userview related tables.