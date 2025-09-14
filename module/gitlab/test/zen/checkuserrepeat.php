#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::checkUserRepeat();
timeout=0
cid=0

- 执行gitlabTest模块的checkUserRepeatTest方法，参数是$zentaoUsers, $userPairs 属性result @success
- 执行gitlabTest模块的checkUserRepeatTest方法，参数是$zentaoUsers, $userPairs 属性result @fail
- 执行gitlabTest模块的checkUserRepeatTest方法，参数是$zentaoUsers, $userPairs 属性result @success
- 执行gitlabTest模块的checkUserRepeatTest方法，参数是$zentaoUsers, $userPairs 属性result @success
- 执行gitlabTest模块的checkUserRepeatTest方法，参数是$zentaoUsers, $userPairs 
 - 属性result @fail
 - 属性message @不能重复绑定用户 用户一

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$gitlabTest = new gitlabTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况，无重复用户
$zentaoUsers = array('openid1' => 'user1', 'openid2' => 'user2', 'openid3' => 'user3');
$userPairs = array('user1' => '用户一', 'user2' => '用户二', 'user3' => '用户三');
r($gitlabTest->checkUserRepeatTest($zentaoUsers, $userPairs)) && p('result') && e('success');

// 步骤2：有重复用户的情况
$zentaoUsers = array('openid1' => 'user1', 'openid2' => 'user1', 'openid3' => 'user2');
$userPairs = array('user1' => '用户一', 'user2' => '用户二');
r($gitlabTest->checkUserRepeatTest($zentaoUsers, $userPairs)) && p('result') && e('fail');

// 步骤3：空zentaoUsers数组
$zentaoUsers = array();
$userPairs = array('user1' => '用户一', 'user2' => '用户二');
r($gitlabTest->checkUserRepeatTest($zentaoUsers, $userPairs)) && p('result') && e('success');

// 步骤4：zentaoUsers有空值情况
$zentaoUsers = array('openid1' => 'user1', 'openid2' => '', 'openid3' => 'user2');
$userPairs = array('user1' => '用户一', 'user2' => '用户二');
r($gitlabTest->checkUserRepeatTest($zentaoUsers, $userPairs)) && p('result') && e('success');

// 步骤5：单个重复用户的情况，检查消息
$zentaoUsers = array('openid1' => 'user1', 'openid2' => 'user1');
$userPairs = array('user1' => '用户一');
r($gitlabTest->checkUserRepeatTest($zentaoUsers, $userPairs)) && p('result,message') && e('fail,不能重复绑定用户 用户一');