<?php
$config->bi = new stdclass();
$config->bi->builtin = new stdclass();
$config->bi->duckSQLTemp = <<<EOT
LOAD '{EXTENSIONPATH}';
ATTACH 'host={HOST} user={USER} password={PASSWORD} port={PORT} database={DATABASE}' as mysqldb(TYPE MYSQL);
USE mysqldb;
{COPYSQL}
EOT;

$config->bi->driverNames = array('mysql', 'duckdb');

$config->bi->columnTypes = new stdclass();
$config->bi->columnTypes->mysql['TINY']       = 'number';
$config->bi->columnTypes->mysql['SHORT']      = 'number';
$config->bi->columnTypes->mysql['LONG']       = 'number';
$config->bi->columnTypes->mysql['FLOAT']      = 'number';
$config->bi->columnTypes->mysql['DOUBLE']     = 'number';
$config->bi->columnTypes->mysql['TIMESTAMP']  = 'string';
$config->bi->columnTypes->mysql['LONGLONG']   = 'string';
$config->bi->columnTypes->mysql['INT24']      = 'number';
$config->bi->columnTypes->mysql['DATE']       = 'date';
$config->bi->columnTypes->mysql['TIME']       = 'string';
$config->bi->columnTypes->mysql['DATETIME']   = 'date';
$config->bi->columnTypes->mysql['YEAR']       = 'date';
$config->bi->columnTypes->mysql['ENUM']       = 'string';
$config->bi->columnTypes->mysql['SET']        = 'string';
$config->bi->columnTypes->mysql['TINYBLOB']   = 'string';
$config->bi->columnTypes->mysql['MEDIUMBLOB'] = 'string';
$config->bi->columnTypes->mysql['LONG_BLOB']  = 'string';
$config->bi->columnTypes->mysql['BLOB']       = 'string';
$config->bi->columnTypes->mysql['VAR_STRING'] = 'string';
$config->bi->columnTypes->mysql['STRING']     = 'string';
$config->bi->columnTypes->mysql['NULL']       = 'null';
$config->bi->columnTypes->mysql['NEWDATE']    = 'date';
$config->bi->columnTypes->mysql['INTERVAL']   = 'string';
$config->bi->columnTypes->mysql['GEOMETRY']   = 'string';
$config->bi->columnTypes->mysql['NEWDECIMAL'] = 'number';

/* Dameng native_type. */
$config->bi->columnTypes->mysql['int']       = 'number';
$config->bi->columnTypes->mysql['varchar']   = 'string';
$config->bi->columnTypes->mysql['text']      = 'string';
$config->bi->columnTypes->mysql['timestamp'] = 'string';
$config->bi->columnTypes->mysql['date']      = 'date';
$config->bi->columnTypes->mysql['time']      = 'string';
$config->bi->columnTypes->mysql['double']    = 'number';
$config->bi->columnTypes->mysql['number']    = 'number';
$config->bi->columnTypes->mysql['bigint']    = 'number';

/* DuckDB native_type. */
$config->bi->columnTypes->duckdb['BIGINT']    = 'number';
$config->bi->columnTypes->duckdb['UBIGINT']   = 'number';
$config->bi->columnTypes->duckdb['HUGEINT']   = 'number';
$config->bi->columnTypes->duckdb['UHUGEINT']  = 'number';
$config->bi->columnTypes->duckdb['INTEGER']   = 'number';
$config->bi->columnTypes->duckdb['UINTEGER']  = 'number';
$config->bi->columnTypes->duckdb['SMALLINT']  = 'number';
$config->bi->columnTypes->duckdb['USMALLINT'] = 'number';
$config->bi->columnTypes->duckdb['TINYINT']   = 'number';
$config->bi->columnTypes->duckdb['UTINYINT']  = 'number';
$config->bi->columnTypes->duckdb['REAL']      = 'number';
$config->bi->columnTypes->duckdb['DOUBLE']    = 'number';
$config->bi->columnTypes->duckdb['BOOLEAN']   = 'number';
$config->bi->columnTypes->duckdb['BLOB']      = 'string';
$config->bi->columnTypes->duckdb['VARCHAR']   = 'string';
$config->bi->columnTypes->duckdb['DATE']      = 'date';
$config->bi->columnTypes->duckdb['TIMESTAMP'] = 'date';
$config->bi->columnTypes->duckdb['TIME']      = 'date';

$config->bi->columnTypes->INTEGER   = 'number';
$config->bi->columnTypes->UINTEGER  = 'number';
$config->bi->columnTypes->UTINYINT  = 'number';
$config->bi->columnTypes->SMALLINT  = 'number';
$config->bi->columnTypes->FLOAT     = 'number';
$config->bi->columnTypes->BOOLEAN   = 'number';
$config->bi->columnTypes->VARCHAR   = 'string';
$config->bi->columnTypes->TIMESTAMP = 'date';
$config->bi->columnTypes->DATE      = 'date';

$config->bi->duckdb = new stdclass();
$config->bi->duckdb->tables = array();
$config->bi->duckdb->tables['action'] = <<<EOT
SELECT id,objectType,objectID,product,project,execution,actor,action,date,read,vision,efforted FROM zt_action
EOT;
$config->bi->duckdb->tables['account'] = <<<EOT
SELECT * FROM zt_account
EOT;
$config->bi->duckdb->tables['attend'] = <<<EOT
SELECT * FROM zt_attend
EOT;
$config->bi->duckdb->tables['bug'] = <<<EOT
SELECT * FROM zt_bug
EOT;
$config->bi->duckdb->tables['build'] = <<<EOT
SELECT * FROM zt_build
EOT;
$config->bi->duckdb->tables['burn'] = <<<EOT
SELECT * FROM zt_burn
EOT;
$config->bi->duckdb->tables['case'] = <<<EOT
SELECT * FROM zt_case
EOT;
$config->bi->duckdb->tables['casestep'] = <<<EOT
SELECT * FROM zt_casestep
EOT;
$config->bi->duckdb->tables['config'] = <<<EOT
SELECT * FROM zt_config
EOT;
$config->bi->duckdb->tables['demand'] = <<<EOT
SELECT * FROM zt_demand
EOT;
$config->bi->duckdb->tables['dept'] = <<<EOT
SELECT * FROM zt_dept
EOT;
$config->bi->duckdb->tables['doc'] = <<<EOT
SELECT * FROM zt_doc
EOT;
$config->bi->duckdb->tables['effort'] = <<<EOT
SELECT * FROM zt_effort
EOT;
$config->bi->duckdb->tables['feedback'] = <<<EOT
SELECT * FROM zt_feedback
EOT;
$config->bi->duckdb->tables['file'] = <<<EOT
SELECT * FROM zt_file
EOT;
$config->bi->duckdb->tables['group'] = <<<EOT
SELECT * FROM zt_group
EOT;
$config->bi->duckdb->tables['history'] = <<<EOT
SELECT id,action,field FROM zt_history
EOT;
$config->bi->duckdb->tables['issue'] = <<<EOT
SELECT * FROM zt_issue
EOT;
$config->bi->duckdb->tables['meeting'] = <<<EOT
SELECT * FROM zt_meeting
EOT;
$config->bi->duckdb->tables['metric'] = <<<EOT
SELECT * FROM zt_metric
EOT;
$config->bi->duckdb->tables['metriclib'] = <<<EOT
SELECT * FROM zt_metriclib
EOT;
$config->bi->duckdb->tables['module'] = <<<EOT
SELECT * FROM zt_module
EOT;
$config->bi->duckdb->tables['opportunity'] = <<<EOT
SELECT * FROM zt_opportunity
EOT;
$config->bi->duckdb->tables['pivot'] = <<<EOT
SELECT * FROM zt_pivot
EOT;
$config->bi->duckdb->tables['product'] = <<<EOT
SELECT * FROM zt_product
EOT;
$config->bi->duckdb->tables['productplan'] = <<<EOT
SELECT * FROM zt_productplan
EOT;
$config->bi->duckdb->tables['planstory'] = <<<EOT
SELECT * FROM zt_planstory
EOT;
$config->bi->duckdb->tables['project'] = <<<EOT
SELECT * FROM zt_project
EOT;
$config->bi->duckdb->tables['projectcase'] = <<<EOT
SELECT * FROM zt_projectcase
EOT;
$config->bi->duckdb->tables['projectproduct'] = <<<EOT
SELECT * FROM zt_projectproduct
EOT;
$config->bi->duckdb->tables['projectstory'] = <<<EOT
SELECT * FROM zt_projectstory
EOT;
$config->bi->duckdb->tables['release'] = <<<EOT
SELECT * FROM zt_release
EOT;
$config->bi->duckdb->tables['risk'] = <<<EOT
SELECT * FROM zt_risk
EOT;
$config->bi->duckdb->tables['story'] = <<<EOT
SELECT * FROM zt_story
EOT;
$config->bi->duckdb->tables['task'] = <<<EOT
SELECT * FROM zt_task
EOT;
$config->bi->duckdb->tables['team'] = <<<EOT
SELECT * FROM zt_team
EOT;
$config->bi->duckdb->tables['testreport'] = <<<EOT
SELECT * FROM zt_testreport
EOT;
$config->bi->duckdb->tables['testresult'] = <<<EOT
SELECT * FROM zt_testresult
EOT;
$config->bi->duckdb->tables['ticket'] = <<<EOT
SELECT * FROM zt_ticket
EOT;
$config->bi->duckdb->tables['todo'] = <<<EOT
SELECT * FROM zt_todo
EOT;
$config->bi->duckdb->tables['user'] = <<<EOT
SELECT * FROM zt_user
EOT;

$config->bi->duckdb->ztvtables['dayactions'] = <<<EOT
SELECT * FROM ztv_dayactions
EOT;
$config->bi->duckdb->ztvtables['dayuserlogin'] = <<<EOT
SELECT * FROM ztv_dayuserlogin
EOT;
$config->bi->duckdb->ztvtables['dayeffort'] = <<<EOT
SELECT * FROM ztv_dayeffort
EOT;
$config->bi->duckdb->ztvtables['daystoryopen'] = <<<EOT
SELECT * FROM ztv_daystoryopen
EOT;
$config->bi->duckdb->ztvtables['daystoryclose'] = <<<EOT
SELECT * FROM ztv_daystoryclose
EOT;
$config->bi->duckdb->ztvtables['daytaskopen'] = <<<EOT
SELECT * FROM ztv_daytaskopen
EOT;
$config->bi->duckdb->ztvtables['daytaskfinish'] = <<<EOT
SELECT * FROM ztv_daytaskfinish
EOT;
$config->bi->duckdb->ztvtables['daybugopen'] = <<<EOT
SELECT * FROM ztv_daybugopen
EOT;
$config->bi->duckdb->ztvtables['daybugresolve'] = <<<EOT
SELECT * FROM ztv_daybugresolve
EOT;
$config->bi->duckdb->ztvtables['productstories'] = <<<EOT
SELECT * FROM ztv_productstories
EOT;
$config->bi->duckdb->ztvtables['productbugs'] = <<<EOT
SELECT * FROM ztv_productbugs
EOT;
$config->bi->duckdb->ztvtables['projectsummary'] = <<<EOT
SELECT * FROM ztv_projectsummary
EOT;
$config->bi->duckdb->ztvtables['executionsummary'] = <<<EOT
SELECT * FROM ztv_executionsummary
EOT;
$config->bi->duckdb->ztvtables['projectstories'] = <<<EOT
SELECT * FROM ztv_projectstories
EOT;
$config->bi->duckdb->ztvtables['projectbugs'] = <<<EOT
SELECT * FROM ztv_projectbugs
EOT;
$config->bi->duckdb->ztvtables['projectteams'] = <<<EOT
SELECT * FROM ztv_projectteams
EOT;
