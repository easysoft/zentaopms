#!/usr/bin/env php
<?php

/**

title=项目发布导出HTML
timeout=0
cid=73

- 按照默认设置导出项目
 - 测试结果 @项目导出成功
 - 最终测试状态 @SUCCESS
- 项目导出csv-GBK-选中记录
 - 测试结果 @项目导出成功
 - 最终测试状态 @SUCCESS
- 项目导出xml-全部记录
 - 测试结果 @项目导出成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/projectexport.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);

$tester = new projectExportTester();
$tester->login();

//设置项目导出数据
$project = array(
    array('filename' => ''),
    array('filename' => '项目导出文件1', 'encoding' => 'GBK', 'data' => '选中记录'),
    array('filename' => '项目导出文件2', 'format' => 'xml', 'data' => '全部记录'),
);

r($tester->projectExport($project['0'])) && p('message,status') && e('项目导出成功,SUCCESS');   // 按照默认设置导出项目
r($tester->projectExport($project['1'])) && p('message,status') && e('项目导出成功,SUCCESS');   // 项目导出csv-GBK-选中记录
r($tester->projectExport($project['2'])) && p('message,status') && e('项目导出成功,SUCCESS');   // 项目导出xml-全部记录

$tester->closeBrowser();
