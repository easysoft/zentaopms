<?php
$lang->bi->binNotExists        = 'The DuckDB binary does not exist.';
$lang->bi->tmpPermissionDenied = 'The DuckDB tmp directory has no permissions, you need to change the permissions for the directory "%s". The <br /> command is: <br />chmod 777 -R %s.';

$lang->bi->driver = 'Driver';
$lang->bi->driverList = array();
$lang->bi->driverList['mysql'] = 'MySQL';

$lang->bi->sqlQuery   = 'SQL statements query';
$lang->bi->sqlBuilder = 'SQL builder';

$lang->bi->toggleSqlText    = 'Write SQL statements by hand';
$lang->bi->toggleSqlBuilder = 'SQL builder';

$lang->bi->builderStepList = array();
$lang->bi->builderStepList['table'] = 'Select tables';
$lang->bi->builderStepList['field'] = 'Select fields';
$lang->bi->builderStepList['func']  = 'add function field';
$lang->bi->builderStepList['where'] = 'Add where';
$lang->bi->builderStepList['query'] = 'Add query filter';
$lang->bi->builderStepList['group'] = 'set group by';
