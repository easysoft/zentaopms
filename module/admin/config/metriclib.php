<?php
$config->admin->metricLib = new stdClass();
$config->admin->metricLib->updateSQLs[1]  = 'CREATE INDEX `metricCode_system_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `system`, `date`)';
$config->admin->metricLib->updateSQLs[2]  = 'CREATE INDEX `metricCode_program_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `program`, `date`)';
$config->admin->metricLib->updateSQLs[3]  = 'CREATE INDEX `metricCode_project_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `project`, `date`)';
$config->admin->metricLib->updateSQLs[4]  = 'CREATE INDEX `metricCode_product_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `product`, `date`)';
$config->admin->metricLib->updateSQLs[5]  = 'CREATE INDEX `metricCode_execution_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `execution`, `date`)';
$config->admin->metricLib->updateSQLs[6]  = 'CREATE INDEX `metricCode_user_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `user`(30), `date`)';
$config->admin->metricLib->updateSQLs[7]  = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX `metricID`';
$config->admin->metricLib->updateSQLs[8]  = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX `metricCode`';
$config->admin->metricLib->updateSQLs[9]  = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX `date`';
$config->admin->metricLib->updateSQLs[10] = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX `deleted`';
