<?php
$config->admin->metriclib = new stdClass();
$config->admin->metriclib->updateSQLs[1]  = 'CREATE INDEX IF NOT EXISTS `metricCode_system_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `system`, `date`)';
$config->admin->metriclib->updateSQLs[2]  = 'CREATE INDEX IF NOT EXISTS `metricCode_program_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `program`, `date`)';
$config->admin->metriclib->updateSQLs[3]  = 'CREATE INDEX IF NOT EXISTS `metricCode_project_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `project`, `date`)';
$config->admin->metriclib->updateSQLs[4]  = 'CREATE INDEX IF NOT EXISTS `metricCode_product_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `product`, `date`)';
$config->admin->metriclib->updateSQLs[5]  = 'CREATE INDEX IF NOT EXISTS `metricCode_execution_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `execution`, `date`)';
$config->admin->metriclib->updateSQLs[6]  = 'CREATE INDEX IF NOT EXISTS `metricCode_user_date` ON ' . TABLE_METRICLIB . ' (`metricCode`, `user`(30), `date`)';
$config->admin->metriclib->updateSQLs[7]  = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX IF EXISTS `metricID`';
$config->admin->metriclib->updateSQLs[8]  = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX IF EXISTS `metricCode`';
$config->admin->metriclib->updateSQLs[9]  = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX IF EXISTS `date`';
$config->admin->metriclib->updateSQLs[10] = 'ALTER TABLE ' . TABLE_METRICLIB . ' DROP INDEX IF EXISTS `deleted`';