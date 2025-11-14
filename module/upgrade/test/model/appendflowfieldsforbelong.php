#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zenData('workflowfield')->gen(0);
zenData('workflow')->loadYaml('workflow')->gen(1);

/**

title=upgradeModel->appendflowfieldsforbelong();
cid=19500
pid=1

- 检查是否已经插入 program 字段。属性program @program
- 检查是否已经插入 product 字段。属性product @product
- 检查是否已经插入 project 字段。属性project @project
- 检查是否已经插入 execution 字段。属性execution @execution
- 没有工作流表 @1
- 没有工作流数据 @1

*/

global $tester;
$upgrade = $tester->loadModel('upgrade');

$table = 'zt_flow_test';
$upgrade->dao->exec("DROP TABLE IF EXISTS `{$table}`");
$upgrade->dao->exec("CREATE TABLE `{$table}` ( `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

$upgrade->appendFlowFieldsForBelong();
$desc   = $upgrade->dao->query("DESC `{$table}`")->fetchAll();
$fields = array_column($desc, 'Field', 'Field');

r($fields) && p('program')   && e('program');    // 检查是否已经插入 program 字段。
r($fields) && p('product')   && e('product');    // 检查是否已经插入 product 字段。
r($fields) && p('project')   && e('project');    // 检查是否已经插入 project 字段。
r($fields) && p('execution') && e('execution');  // 检查是否已经插入 execution 字段。

$upgrade->dao->exec("DROP TABLE IF EXISTS `{$table}`");
r((int)$upgrade->appendFlowFieldsForBelong()) && p() && e(1); //没有工作流表

$upgrade->dao->exec("DELETE FROM  `zt_workflow`");
r((int)$upgrade->appendFlowFieldsForBelong()) && p() && e(1); //没有工作流数据