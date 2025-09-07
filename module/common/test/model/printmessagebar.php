#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printMessageBar();
timeout=0
cid=0

- 步骤1：消息功能关闭时不输出任何内容 @
- 步骤2：消息功能开启且无未读消息时输出基础HTML @*id='messageBar'*
- 步骤3：消息功能开启且有未读消息时显示消息数量 @*label-dot danger*5*
- 步骤4：未读消息数量超过99时显示99+ @*99+*
- 步骤5：设置不显示计数时只显示红点 @*width:5px*
- 步骤6：测试不同用户账户的消息栏输出 @*id='messageBar'*
- 步骤7：测试消息栏HTML结构的完整性 @*<li id='messageDropdown'*

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->printMessageBarTest(array('turnon' => false))) && p() && e(''); // 步骤1：消息功能关闭时不输出任何内容
r($commonTest->printMessageBarTest(array('turnon' => true, 'unreadCount' => 0))) && p() && e("*id='messageBar'*"); // 步骤2：消息功能开启且无未读消息时输出基础HTML
r($commonTest->printMessageBarTest(array('turnon' => true, 'count' => '1', 'unreadCount' => 5))) && p() && e('*label-dot danger*5*'); // 步骤3：消息功能开启且有未读消息时显示消息数量
r($commonTest->printMessageBarTest(array('turnon' => true, 'count' => '1', 'unreadCount' => 150))) && p() && e('*99+*'); // 步骤4：未读消息数量超过99时显示99+
r($commonTest->printMessageBarTest(array('turnon' => true, 'count' => '0', 'unreadCount' => 3))) && p() && e('*width:5px*'); // 步骤5：设置不显示计数时只显示红点
r($commonTest->printMessageBarTest(array('turnon' => true, 'account' => 'user1', 'unreadCount' => 2))) && p() && e("*id='messageBar'*"); // 步骤6：测试不同用户账户的消息栏输出
r($commonTest->printMessageBarTest(array('turnon' => true, 'unreadCount' => 1))) && p() && e("*<li id='messageDropdown'*"); // 步骤7：测试消息栏HTML结构的完整性