#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraBuild();
timeout=0
cid=15856

- 步骤1：空数据列表处理属性message @Empty data list handled correctly
- 步骤2：单个有效版本数据导入属性validCount @1
- 步骤3：多个版本数据批量导入属性validCount @3
- 步骤4：包含无效数据的混合数据导入属性validCount @3
- 步骤5：大量数据批量导入测试属性dataCount @15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

global $app;
global $tester;

$sql = <<<EOT
CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

try
{
    $tester->dbh->exec($sql);
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch(Exception $e) {}

if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

$product = zenData('product');
$product->id->range('201-203');
$product->name->range('导入产品A,导入产品B,导入产品C');
$product->code->range('importA,importB,importC');
$product->status->range('normal{3}');
$product->gen(3);

$project = zenData('project');
$project->id->range('301-303');
$project->name->range('导入项目A,导入项目B,导入项目C');
$project->code->range('importProjectA,importProjectB,importProjectC');
$project->status->range('wait{3}');
$project->type->range('project{3}');
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('301-303');
$projectProduct->product->range('201-203');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->roadmap->range('0');
$projectProduct->gen(3);

$tester->dbh->exec("INSERT INTO jiratmprelation (`AType`, `AID`, `BType`, `BID`, `extra`) VALUES ('jproject', '1001', 'zproject', '301', ''), ('jproject', '1002', 'zproject', '302', ''), ('jproject', '1003', 'zproject', '303', '')");

$app->session->set('jiraMethod', 'json');

su('admin');

$convertTest = new convertTaoTest();

$data1 = (object)array('id' => 95001, 'project' => 1001, 'vname' => 'Version 1.0', 'startdate' => '2024-01-01', 'releasedate' => '2024-01-15', 'released' => '1', 'description' => '第一个版本');
$data2 = (object)array('id' => 95002, 'project' => 9999, 'vname' => 'Skipped Version', 'startdate' => '2024-02-01', 'releasedate' => '2024-02-15', 'released' => '0', 'description' => '未映射项目');
$data3 = (object)array('id' => 95003, 'project' => 1002, 'vname' => 'Version 2.0', 'startdate' => '2024-03-01', 'releasedate' => '2024-03-15', 'released' => '1', 'description' => '第二个版本');

r($convertTest->importJiraBuildTest(array())) && p() && e('1'); // 步骤1：空数据列表直接返回成功
r($convertTest->importJiraBuildTest(array($data1))) && p() && e('1'); // 步骤2：已映射项目版本导入成功
r($tester->dao->select('COUNT(*) AS count')->from(JIRA_TMPRELATION)->where('AType')->eq('jversion')->andWhere('AID')->eq('95001')->fetch('count')) && p() && e('1'); // 步骤3：成功写入版本关联
r($convertTest->importJiraBuildTest(array($data1, $data2))) && p() && e('1'); // 步骤4：重复版本和未映射项目都不会报错
r($tester->dao->select('COUNT(*) AS count')->from(JIRA_TMPRELATION)->where('AType')->eq('jversion')->andWhere('AID')->in('95001,95002')->fetch('count')) && p() && e('1'); // 步骤5：未映射项目不会新增关联
r($convertTest->importJiraBuildTest(array($data3))) && p() && e('1'); // 步骤6：第二个映射项目版本导入成功
r($tester->dao->select('COUNT(*) AS count')->from(JIRA_TMPRELATION)->where('AType')->eq('jversion')->andWhere('AID')->in('95001,95003')->fetch('count')) && p() && e('2'); // 步骤7：多个有效版本都会写入关联
