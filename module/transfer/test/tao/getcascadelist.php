#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$story = zenData('story');
$story->id->range('1-3');
$story->module->range('1,1,2');
$story->gen(3);
su('admin');

/**

title=测试 transfer->getCascadeList();
timeout=0
cid=19332

- 测试bug模块无级联配置时保留typeList原值 @代码错误(#codeerror)
- 测试bug模块无级联配置时保留projectList数量 @5
- 测试bug模块无级联配置时保留listStyle数量 @10
- 测试testcase模块级联后生成3组storyList数据 @3
- 测试testcase模块级联后module 1下包含2条story @2

*/
$transfer = new transferTaoTest();

$bugLists = array
(
    'typeList'    => array('codeerror' => '代码错误(#codeerror)', 'config' => '配置相关(#config)'),
    'projectList' => array(11 => '项目11(#11)', 12 => '项目12(#12)', 13 => '项目13(#13)', 14 => '项目14(#14)', 15 => '项目15(#15)'),
    'listStyle'   => array(1 => 'module', 2 => 'project', 3 => 'execution', 4 => 'story', 5 => 'severity', 6 => 'pri', 7 => 'type', 8 => 'os', 9 => 'browser', 10 => 'openedBuild')
);
$result1 = $transfer->getCascadeListTest('bug', $bugLists);

r($result1) && p('typeList:codeerror') && e('代码错误(#codeerror)'); // 测试bug模块无级联配置时保留typeList原值

r(count($result1['projectList'])) && p('') && e('5');  // 测试bug模块无级联配置时保留projectList数量
r(count($result1['listStyle']))   && p('') && e('10'); // 测试bug模块无级联配置时保留listStyle数量

$testcaseLists = array
(
    'moduleList' => array(1 => '模块1(#1)', 2 => '模块2(#2)'),
    'storyList'  => array(1 => '需求1(#1)', 2 => '需求2(#2)', 3 => '需求3(#3)')
);
$result2 = $transfer->getCascadeListTest('testcase', $testcaseLists);
r(count($result2['storyList']))    && p('') && e('3'); // 测试testcase模块级联后生成3组storyList数据
r(count($result2['storyList'][1])) && p('') && e('2'); // 测试testcase模块级联后module 1下包含2条story
