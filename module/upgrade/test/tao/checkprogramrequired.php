#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->createNewProgram();
timeout=0
cid=1

- 测试项目集名称不能为空第programName条的0属性 @『项目集名称』不能为空。
- 测试结束日期不能为空第end条的0属性 @『结束日期』不能为空。
- 测试必填项都已填写 @1
- 测试项目名称不能为空第projectName条的0属性 @『项目名称』不能为空。
- 测试必填项都已填写 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

su('admin');

$data = new stdclass();
$data->projectType   = 'project';
$data->programName   = '';
$data->programStatus = '';
$data->begin         = '2023-12-21';
$data->end           = '';

$objectTypeList = array('project', 'execution');

$upgrade = new upgradeTest();
r($upgrade->checkProgramRequiredTest($data, $objectTypeList[0])) && p('programName:0') && e('『项目集名称』不能为空。'); // 测试项目集名称不能为空

$data->programName = '项目集1';
r($upgrade->checkProgramRequiredTest($data, $objectTypeList[0])) && p('end:0') && e('『结束日期』不能为空。'); // 测试结束日期不能为空

$data->end = '2023-12-31';
r($upgrade->checkProgramRequiredTest($data, $objectTypeList[0])) && p('') && e('1'); // 测试必填项都已填写

$data->projectName = '';
r($upgrade->checkProgramRequiredTest($data, $objectTypeList[1])) && p('projectName:0') && e('『项目名称』不能为空。'); // 测试项目名称不能为空

$data->projectName = '项目1';
r($upgrade->checkProgramRequiredTest($data, $objectTypeList[1])) && p('') && e('1'); // 测试必填项都已填写