<?php
$config->action->objectTables['company']     = TABLE_COMPANY;
$config->action->objectTables['product']     = TABLE_PRODUCT;
$config->action->objectTables['story']       = TABLE_STORY;
$config->action->objectTables['productplan'] = TABLE_PRODUCTPLAN;
$config->action->objectTables['release']     = TABLE_RELEASE;
$config->action->objectTables['project']     = TABLE_PROJECT;
$config->action->objectTables['task']        = TABLE_TASK;
$config->action->objectTables['build']       = TABLE_BUILD;
$config->action->objectTables['bug']         = TABLE_BUG;
$config->action->objectTables['case']        = TABLE_CASE;
$config->action->objectTables['testtask']    = TABLE_TESTTASK;

$config->action->objectNameFields['company']     = 'name';
$config->action->objectNameFields['product']     = 'name';
$config->action->objectNameFields['story']       = 'title';
$config->action->objectNameFields['productplan'] = 'title';
$config->action->objectNameFields['release']     = 'name';
$config->action->objectNameFields['project']     = 'name';
$config->action->objectNameFields['task']        = 'name';
$config->action->objectNameFields['build']       = 'name';
$config->action->objectNameFields['bug']         = 'title';
$config->action->objectNameFields['case']        = 'title';
$config->action->objectNameFields['testtask']    = 'name';
