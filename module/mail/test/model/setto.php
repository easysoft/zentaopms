#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setTO();
timeout=0
cid=0

- 步骤1：空toList列表情况 @0
- 步骤2：toList包含有效用户但emails为空 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$mailTest = new mailTest();

// 4. 测试步骤执行（每个r()...e()语句必须在同一行）
r(count($mailTest->setTOTest(array(), array()))) && p() && e('0'); // 步骤1：空toList列表情况
r(count($mailTest->setTOTest(array('admin'), array()))) && p() && e('0'); // 步骤2：toList包含有效用户但emails为空
$emails = array(); $emails['admin'] = new stdclass(); $emails['admin']->email = 'admin@cnezsoft.com'; $emails['admin']->realname = '管理员'; r($mailTest->setTOTest(array('admin'), $emails)) && p('admin:sended') && e('1'); // 步骤3：正常情况添加有效邮件地址
$emails = array(); $emails['admin'] = new stdclass(); $emails['admin']->email = 'admin@cnezsoft.com'; $emails['admin']->realname = '管理员'; $emails['admin']->sended = true; r($mailTest->setTOTest(array('admin'), $emails)) && p('admin:sended') && e('1'); // 步骤4：邮件地址已标记为已发送
$emails = array(); $emails['invalid'] = new stdclass(); $emails['invalid']->email = 'invalid-email'; $emails['invalid']->realname = '无效用户'; r($mailTest->setTOTest(array('invalid'), $emails)) && p('invalid:sended') && e('~~'); // 步骤5：无效邮件地址情况