#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('user')->gen('1');
zdTable('scene')->gen(0);

su('admin');

/**

title=测试 testcaseModel->createScene();
cid=1
pid=1

*/

$scene1 = array('product' => 0,         'title' => '测试场景1');
$scene2 = array('product' => '',        'title' => '测试场景1');
$scene3 = array('product' => 'product', 'title' => '测试场景1');

$scene4 = array('product' => 1, 'title' => '');
$scene5 = array('product' => 1, 'title' => '0');
$scene6 = array('product' => 1, 'title' => '这是一个很长的测试场景标题，主要目的就是用来测试场景标题字段超出数据库字段长度后能不能正常提示错误信息。如果能够正常提示错误信息，那么这条测试就通过了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。');

$scene7  = array('product' => 1, 'title' => '测试场景1', 'branch' => 'branch');
$scene8  = array('product' => 1, 'title' => '测试场景1', 'module' => 'module');
$scene9  = array('product' => 1, 'title' => '测试场景1', 'parent' => 'parent');

$scene10 = array('product' => 1, 'title' => '测试场景1', 'branch' => 1, 'module' => 1, 'parent' => 0);
$scene11 = array('product' => 1, 'title' => '测试场景2', 'branch' => 1, 'module' => 1, 'parent' => 1);
$scene12 = array('product' => 1, 'title' => '测试场景3', 'branch' => 1, 'module' => 1, 'parent' => 2);
$scene13 = array('product' => 1, 'title' => '测试场景3', 'branch' => 1, 'module' => 1, 'parent' => 4);

$testcase = new testcaseTest();

r($testcase->createSceneTest($scene1)) && p() && e(0);                 // 创建场景时所属产品设为数字 0 返回 false。
r(dao::getError()) && p('product:0') && e('『所属产品』不能为空。');   // 创建场景时所属产品设为数字 0 输出错误提示。

r($testcase->createSceneTest($scene2)) && p() && e(0);                 // 创建场景时所属产品设为空字符串返回 false。
r(dao::getError()) && p('product:0') && e('『所属产品』应当是数字。'); // 创建场景时所属产品设为空字符串输出错误提示。

r($testcase->createSceneTest($scene3)) && p() && e(0);                 // 创建场景时所属产品设为字符串返回 false。
r(dao::getError()) && p('product:0') && e('『所属产品』应当是数字。'); // 创建场景时所属产品设为字符串输出错误提示。

r($testcase->createSceneTest($scene4)) && p() && e(0);             // 创建场景时场景标题设为空字符串返回 false。
r(dao::getError()) && p('title:0') && e('『场景名称』不能为空。'); // 创建场景时场景标题设为空字符串输出错误提示。

r($testcase->createSceneTest($scene5)) && p() && e(0);             // 创建场景时场景标题设为字符串 0 返回 false。
r(dao::getError()) && p('title:0') && e('『场景名称』不能为空。'); // 创建场景时场景标题设为字符串 0 输出错误提示。

r($testcase->createSceneTest($scene6)) && p() && e(0);                                       // 创建场景时场景标题超过数据库字段长度返回 false。
r(dao::getError()) && p('title:0') && e('『场景名称』长度应当不超过『255』，且大于『0』。'); // 创建场景时场景标题超过数据库字段长度输出错误提示。

r($testcase->createSceneTest($scene7)) && p() && e(0);                // 创建场景时所属分支设为字符串返回 false。
r(dao::getError()) && p('branch:0') && e('『所属分支』应当是数字。'); // 创建场景时所属分支设为字符串输出错误提示。

r($testcase->createSceneTest($scene8)) && p() && e(0);                // 创建场景时所属模块设为字符串返回 false。
r(dao::getError()) && p('module:0') && e('『所属模块』应当是数字。'); // 创建场景时所属模块设为字符串输出错误提示。

r($testcase->createSceneTest($scene9)) && p() && e(0);              // 创建场景时父场景设为字符串返回 false。
r(dao::getError()) && p('parent:0') && e('『父场景』应当是数字。'); // 创建场景时父场景设为字符串输出错误提示。

r($testcase->createSceneTest($scene10)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('1|1|0|1|,1,|scene|opened');     // 获取创建场景 1 成功后检测场景信息和日志。
r($testcase->createSceneTest($scene11)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('2|2|1|2|,1,2,|scene|opened');   // 获取创建场景 2 成功后检测场景信息和日志。
r($testcase->createSceneTest($scene12)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('3|3|2|3|,1,2,3,|scene|opened'); // 获取创建场景 3 成功后检测场景信息和日志。
r($testcase->createSceneTest($scene13)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('4|4|0|1|,1,|scene|opened');     // 获取创建场景 4 成功后检测场景信息和日志。
