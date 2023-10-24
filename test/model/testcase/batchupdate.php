#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->batchUpdate();
cid=1
pid=1

测试批量修改用例标题 >> title,这个是测试用例1,修改后的测试用例1
测试批量修改用例优先级 >> pri,2,1
测试批量修改用例状态 >> status,blocked,nomal
测试批量修改用例模块 >> module,0,1822
测试批量修改用例颜色 >> color,#3da7f5,#2dbdb2
测试批量修改用例类型 >> type,performance,other
测试批量修改用例前置条件 >> precondition,这是前置条件3,修改后的前置条件3
测试批量修改用例关键词 >> keywords,这是关键词4,修改后的关键词4
测试批量修改用例阶段 >> stage,unittest,unittest,feature

*/

$changeTitle        = array('1' => '修改后的测试用例1', '2' => '这个是测试用例2', '3' => '这个是测试用例3', '4' => '这个是测试用例4');;
$changePris         = array('1' => 1, '2' => 1, '3' => 3, '4' => 4);
$changeStatuses     = array('1' => 'wait', '2' => 'normal', '3' => 'nomal', '4' => 'investigate');
$changeModules      = array('1' => 0, '2' => 0, '3' => 0, '4' => 1822);
$changeColor        = array('1' => '#2dbdb2', '2' => '#75c941', '3' => '#2dbdb2', '4' => '#797ec9');
$changeTypes        = array('1' => 'feature', '2' => 'other', '3' => 'config', '4' => 'install');
$changePrecondition = array('1' => '这是前置条件1', '2' => '这是前置条件2', '3' => '修改后的前置条件3', '4' => '这是前置条件4');
$changeKeywords     = array('1' => '这是关键词1', '2' => '这是关键词2', '3' => '这是关键词3', '4' => '修改后的关键词4');
$changeStages       = array('1' => array('unittest', 'feature'), '2' => array('feature'), '3' => array('intergrate'), '4' => array('system'));

$changeTitle        = array('title' => $changeTitle);
$changePris         = array('pris' => $changePris);
$changeStatuses     = array('statuses' => $changeStatuses);
$changeModules      = array('modules' => $changeModules);
$changeColor        = array('color' => $changeColor);
$changeTypes        = array('types' => $changeTypes);
$changePrecondition = array('precondition' => $changePrecondition);
$changeKeywords     = array('keywords' => $changeKeywords);
$changeStages       = array('stages' => $changeStages);

$testcase = new testcaseTest();

r($testcase->batchUpdateTest(1, $changeTitle))        && p('field,old,new') && e('title,这个是测试用例1,修改后的测试用例1');      // 测试批量修改用例标题
r($testcase->batchUpdateTest(2, $changePris))         && p('field,old,new') && e('pri,2,1');                                      // 测试批量修改用例优先级
r($testcase->batchUpdateTest(3, $changeStatuses))     && p('field,old,new') && e('status,blocked,nomal');                         // 测试批量修改用例状态
r($testcase->batchUpdateTest(4, $changeModules))      && p('field,old,new') && e('module,0,1822');                                // 测试批量修改用例模块
r($testcase->batchUpdateTest(1, $changeColor))        && p('field,old,new') && e('color,#3da7f5,#2dbdb2');                        // 测试批量修改用例颜色
r($testcase->batchUpdateTest(2, $changeTypes))        && p('field,old,new') && e('type,performance,other');                       // 测试批量修改用例类型
r($testcase->batchUpdateTest(3, $changePrecondition)) && p('field,old,new') && e('precondition,这是前置条件3,修改后的前置条件3'); // 测试批量修改用例前置条件
r($testcase->batchUpdateTest(4, $changeKeywords))     && p('field,old,new') && e('keywords,这是关键词4,修改后的关键词4');         // 测试批量修改用例关键词
r($testcase->batchUpdateTest(1, $changeStages))       && p('field,old,new') && e('stage,unittest,unittest,feature');              // 测试批量修改用例阶段
