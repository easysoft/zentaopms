<?php
$config->bi = new stdclass();
$config->bi->builtin = new stdclass();
$config->bi->duckSQLTemp = <<<EOT
LOAD '{EXTENSIONPATH}';
ATTACH 'host={HOST} user={USER} password={PASSWORD} port={PORT} database={DATABASE}' as mysqldb(TYPE MYSQL);
USE mysqldb;
{COPYSQL}
EOT;

$config->bi->duckdb = new stdclass();
$config->bi->duckdb->tables = array();
$config->bi->duckdb->tables['action'] = <<<EOT
SELECT * FROM zt_action
EOT;
$config->bi->duckdb->tables['account'] = <<<EOT
SELECT * FROM zt_account
EOT;
$config->bi->duckdb->tables['bug'] = <<<EOT
SELECT * FROM zt_bug
EOT;
$config->bi->duckdb->tables['build'] = <<<EOT
SELECT * FROM zt_build
EOT;
$config->bi->duckdb->tables['case'] = <<<EOT
SELECT * FROM zt_case
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
SELECT * FROM zt_history
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
