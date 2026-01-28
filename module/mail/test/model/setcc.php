#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setCC();
timeout=0
cid=17019

- 执行mailTest模块的setCCTest方法，参数是array  @0
- 执行mailTest模块的setCCTest方法，参数是array 第admin条的sended属性 @1
- 执行mailTest模块的setCCTest方法，参数是array 第user1条的sended属性 @~~
- 执行mailTest模块的setCCTest方法，参数是array 第admin条的sended属性 @~~
- 执行mailTest模块的setCCTest方法，参数是array 第admin条的sended属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mailTest = new mailModelTest();

// 步骤1：测试空ccList数组情况
$emails = array();
r($mailTest->setCCTest(array(), $emails)) && p() && e(0);

// 步骤2：测试有效ccList和emails情况
$emails = array();
$emails['admin'] = new stdClass();
$emails['admin']->email    = 'admin@cnezsoft.com';
$emails['admin']->realname = '管理员';
r($mailTest->setCCTest(array('admin'), $emails)) && p('admin:sended') && e('1');

// 步骤3：测试emails中缺少对应账号情况
$emails = array();
$emails['user1'] = new stdClass();
$emails['user1']->email    = 'user1@cnezsoft.com';
$emails['user1']->realname = '用户1';
r($mailTest->setCCTest(array('admin'), $emails)) && p('user1:sended') && e('~~');

// 步骤4：测试无效邮箱格式情况
$emails = array();
$emails['admin'] = new stdClass();
$emails['admin']->email    = 'invalid-email';
$emails['admin']->realname = '管理员';
r($mailTest->setCCTest(array('admin'), $emails)) && p('admin:sended') && e('~~');

// 步骤5：测试已发送标记情况
$emails = array();
$emails['admin'] = new stdClass();
$emails['admin']->email    = 'admin@cnezsoft.com';
$emails['admin']->realname = '管理员';
$emails['admin']->sended   = true;
r($mailTest->setCCTest(array('admin'), $emails)) && p('admin:sended') && e('1');