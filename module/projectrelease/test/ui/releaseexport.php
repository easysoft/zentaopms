#!/usr/bin/env php
<?php

/**

title=项目发布导出HTML
timeout=0
cid=73

- 项目发布导出时文件名必填项检查
 - 测试结果 @项目发布导出必填提示信息正确
 - 最终测试状态 @SUCCESS
- 项目发布导出所有数据
 - 测试结果 @项目发布导出成功
 - 最终测试状态 @SUCCESS
- 项目发布导出指定数据
 - 测试结果 @项目发布导出成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/releaseexport.ui.class.php';

zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);

$tester = new releaseExportTester();
$tester->login();

//设置导出发布数据
$release = array(
    array('filename' => ''),
    array('filename' => '导出文件1'),
    array('filename' => '导出文件2', 'exportdata' => '解决的Bug'),
);

r($tester->exportWithNoFilename($release['0'])) && p('message,status') && e('项目发布导出必填提示信息正确,SUCCESS');   // 项目发布导出时文件名必填项检查
r($tester->releaseExport($release['1']))        && p('message,status') && e('项目发布导出成功,SUCCESS');               // 项目发布导出所有数据
r($tester->releaseExport($release['2']))        && p('message,status') && e('项目发布导出成功,SUCCESS');               // 项目发布导出指定数据

$tester->closeBrowser();
