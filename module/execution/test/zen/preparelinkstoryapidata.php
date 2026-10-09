#!/usr/bin/env php
<?php

/**

title=测试 executionZen::prepareLinkStoryApiData();
timeout=0
cid=0

- 步骤1：执行关联需求，返回成功和需求ID列表 @success,1|2|3
- 步骤2：重复及字符串需求ID去重后返回成功 @success,1|2
- 步骤3：项目视图关联需求返回成功 @success,5
- 步骤4：对象类型与模块不匹配时返回404 @fail,yes
- 步骤5：对象不存在时返回404 @fail,yes
- 步骤6：需求ID非数字时返回失败提示 @fail,yes
- 步骤7：需求列表为空时返回成功且需求ID列表为空 @success,none

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('user')->gen(0);
$user = zenData('user');
$user->id->range('1');
$user->account->range('admin');
$user->realname->range('管理员');
$user->company->range('1');
$user->gen(1);

zenData('project')->loadYaml('project_preparelinkstoryapidata', false, 2)->gen(4);
su('admin');

$executionZenTest = new executionZenTest();

r($executionZenTest->prepareLinkStoryApiDataTest(1, 'execution', array(1, 2, 3)))      && p('status,storyList')  && e('success,1|2|3'); // 步骤1：执行关联需求，返回成功和需求ID列表
r($executionZenTest->prepareLinkStoryApiDataTest(1, 'execution', array('1', '1', 2))) && p('status,storyList')  && e('success,1|2');   // 步骤2：重复及字符串需求ID去重后返回成功
r($executionZenTest->prepareLinkStoryApiDataTest(2, 'projectstory', array(5)))        && p('status,storyList')  && e('success,5');     // 步骤3：项目视图关联需求返回成功
r($executionZenTest->prepareLinkStoryApiDataTest(2, 'execution', array(1)))           && p('status,hasMessage') && e('fail,yes');      // 步骤4：对象类型与模块不匹配时返回404
r($executionZenTest->prepareLinkStoryApiDataTest(99, 'execution', array(1)))          && p('status,hasMessage') && e('fail,yes');      // 步骤5：对象不存在时返回404
r($executionZenTest->prepareLinkStoryApiDataTest(1, 'execution', array('abc')))       && p('status,hasMessage') && e('fail,yes');      // 步骤6：需求ID非数字时返回失败提示
r($executionZenTest->prepareLinkStoryApiDataTest(1, 'execution', array()))            && p('status,storyList')  && e('success,none');   // 步骤7：需求列表为空时返回成功且需求ID列表为空
