#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareStartExtras();
timeout=0
cid=0

- 执行projectTest模块的prepareStartExtrasTest方法，参数是$postData1
 - 属性status @doing
 - 属性lastEditedBy @guest
- 执行projectTest模块的prepareStartExtrasTest方法，参数是$postData2
 - 属性status @doing
 - 属性lastEditedBy @guest
- 执行projectTest模块的prepareStartExtrasTest方法，参数是$postData3
 - 属性status @doing
 - 属性lastEditedBy @guest
 - 属性name @Extended Project
 - 属性PM @user1
- 执行projectTest模块的prepareStartExtrasTest方法，参数是$postData4
 - 属性status @doing
 - 属性lastEditedBy @guest
- 执行projectTest模块的prepareStartExtrasTest方法，参数是$postData5
 - 属性status @doing
 - 属性lastEditedBy @guest

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 创建fixer类数据用于测试
global $tester;
$tester->app->loadClass('filter', true);

su('admin');

$projectTest = new projectTest();

// 测试步骤1：正常输入包含基础字段的postData对象
$_POST = array('name' => 'Test Project', 'desc' => 'Test Description');
$postData1 = fixer::input('post');
r($projectTest->prepareStartExtrasTest($postData1)) && p('status,lastEditedBy') && e('doing,guest');

// 测试步骤2：空的postData对象
$_POST = array();
$postData2 = fixer::input('post');
r($projectTest->prepareStartExtrasTest($postData2)) && p('status,lastEditedBy') && e('doing,guest');

// 测试步骤3：包含额外属性的postData对象
$_POST = array('name' => 'Extended Project', 'PM' => 'user1', 'budget' => 10000);
$postData3 = fixer::input('post');
r($projectTest->prepareStartExtrasTest($postData3)) && p('status,lastEditedBy,name,PM') && e('doing,guest,Extended Project,user1');

// 测试步骤4：测试postData包含已存在status字段的情况（应被覆盖为doing）
$_POST = array('status' => 'wait', 'name' => 'Override Status Project');
$postData4 = fixer::input('post');
r($projectTest->prepareStartExtrasTest($postData4)) && p('status,lastEditedBy') && e('doing,guest');

// 测试步骤5：测试postData包含已存在lastEditedBy字段的情况（应被覆盖为guest）
$_POST = array('lastEditedBy' => 'user2', 'name' => 'Override Editor Project');
$postData5 = fixer::input('post');
r($projectTest->prepareStartExtrasTest($postData5)) && p('status,lastEditedBy') && e('doing,guest');