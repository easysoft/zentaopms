#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getResponseInModal();
timeout=0
cid=18695

- 步骤1：不在弹窗中，返回false @0
- 步骤2：弹窗中+execution tab+kanban类型
 - 属性result @success
 - 属性callback @refreshKanban()
 - 属性closeModal @1
- 步骤3：弹窗中+execution tab+非kanban类型
 - 属性result @success
 - 属性load @1
 - 属性closeModal @1
- 步骤4：弹窗中+project tab
 - 属性result @success
 - 属性load @1
 - 属性closeModal @1
- 步骤5：弹窗中+story tab
 - 属性result @success
 - 属性load @1
 - 属性closeModal @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('project')->loadYaml('execution')->gen(10);
zenData('story')->gen(10);

su('admin');

$storyTest = new storyZenTest();

r($storyTest->getResponseInModalTest('', false, '', '', 0)) && p() && e('0'); // 步骤1：不在弹窗中，返回false
r($storyTest->getResponseInModalTest('', true, 'execution', 'kanban', 1)) && p('result,callback,closeModal') && e('success,refreshKanban(),1'); // 步骤2：弹窗中+execution tab+kanban类型
r($storyTest->getResponseInModalTest('', true, 'execution', 'stage', 1)) && p('result,load,closeModal') && e('success,1,1'); // 步骤3：弹窗中+execution tab+非kanban类型
r($storyTest->getResponseInModalTest('', true, 'project', '', 1)) && p('result,load,closeModal') && e('success,1,1'); // 步骤4：弹窗中+project tab
r($storyTest->getResponseInModalTest('', true, 'story', '', 1)) && p('result,load,closeModal') && e('success,1,1'); // 步骤5：弹窗中+story tab