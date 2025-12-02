#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('scene')->gen(0);

su('admin');

/**

title=测试 testcaseModel->createScene();
cid=18970
pid=1

*/

$scene1 = array('product' => 0,         'title' => '这个是测试场景1');
$scene2 = array('product' => '',        'title' => '这个是测试场景1');
$scene3 = array('product' => 'product', 'title' => '这个是测试场景1');

$scene4 = array('product' => 1, 'title' => '');
$scene5 = array('product' => 1, 'title' => '0');
$scene6 = array('product' => 1, 'title' => '这是一个很长的测试场景标题，主要目的就是用来测试场景标题字段超出数据库字段长度后能不能正常提示错误信息。如果能够正常提示错误信息，那么这条测试就通过了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。');

$scene7  = array('product' => 1, 'title' => '这个是测试场景1', 'branch' => '');
$scene8  = array('product' => 1, 'title' => '这个是测试场景1', 'branch' => 'branch');
$scene9  = array('product' => 1, 'title' => '这个是测试场景1', 'module' => '');
$scene10 = array('product' => 1, 'title' => '这个是测试场景1', 'module' => 'module');
$scene11 = array('product' => 1, 'title' => '这个是测试场景1', 'parent' => '');
$scene12 = array('product' => 1, 'title' => '这个是测试场景1', 'parent' => 'parent');

$scene13 = array('product' => 1, 'title' => '这个是测试场景1', 'branch' => 1, 'module' => 1, 'parent' => 0);
$scene14 = array('product' => 1, 'title' => '这个是测试场景2', 'branch' => 1, 'module' => 1, 'parent' => 1);
$scene15 = array('product' => 1, 'title' => '这个是测试场景3', 'branch' => 1, 'module' => 1, 'parent' => 2);
$scene16 = array('product' => 1, 'title' => '这个是测试场景4', 'branch' => 1, 'module' => 1, 'parent' => 5);

$scene17 = array('product' => 1, 'title' => '这个是测试场景1');
$scene18 = array('product' => 2, 'title' => '这个是测试场景1');

$testcase = new testcaseTest();

r($testcase->createSceneTest($scene1))  && p('product:0') && e('『所属产品』不能为空。');                           // 所属产品设为数字 0 输出错误提示。
r($testcase->createSceneTest($scene2))  && p('product:0') && e('『所属产品』应当是数字。');                         // 所属产品设为空字符串输出错误提示。
r($testcase->createSceneTest($scene3))  && p('product:0') && e('『所属产品』应当是数字。');                         // 所属产品设为字符串输出错误提示。
r($testcase->createSceneTest($scene4))  && p('title:0')   && e('『场景名称』不能为空。');                           // 场景标题设为空字符串输出错误提示。
r($testcase->createSceneTest($scene5))  && p('title:0')   && e('『场景名称』不能为空。');                           // 场景标题设为字符串 0 输出错误提示。
r($testcase->createSceneTest($scene6))  && p('title:0')   && e('『场景名称』长度应当不超过『255』，且大于『0』。'); // 场景标题超过数据库字段长度输出错误提示。
r($testcase->createSceneTest($scene7))  && p('branch:0')  && e('『所属分支』应当是数字。');                         // 所属分支设为空字符串输出错误提示。
r($testcase->createSceneTest($scene8))  && p('branch:0')  && e('『所属分支』应当是数字。');                         // 所属分支设为字符串输出错误提示。
r($testcase->createSceneTest($scene9))  && p('module:0')  && e('『所属模块』应当是数字。');                         // 所属模块设为空字符串输出错误提示。
r($testcase->createSceneTest($scene10)) && p('module:0')  && e('『所属模块』应当是数字。');                         // 所属模块设为字符串输出错误提示。
r($testcase->createSceneTest($scene11)) && p('parent:0')  && e('『父场景』应当是数字。');                           // 父场景设为空字符串输出错误提示。
r($testcase->createSceneTest($scene12)) && p('parent:0')  && e('『父场景』应当是数字。');                           // 父场景设为字符串输出错误提示。

r($testcase->createSceneTest($scene13)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('1|1|0|1|,1,;scene|opened');     // 创建场景 1 成功后检测场景信息和日志。
r($testcase->createSceneTest($scene14)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('2|2|1|2|,1,2,;scene|opened');   // 创建场景 2 成功后检测场景信息和日志。
r($testcase->createSceneTest($scene15)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('3|3|2|3|,1,2,3,;scene|opened'); // 创建场景 3 成功后检测场景信息和日志。
r($testcase->createSceneTest($scene16)) && p('scene:id|sort|parent|grade|path;action:objectType|action', '|') && e('4|4|0|1|,4,;scene|opened');     // 创建场景 4 成功后检测场景信息和日志。

r($testcase->createSceneTest($scene17)) && p('title:0') && e('『场景名称』已经有『这个是测试场景1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 同产品下场景标题重复输出错误提示。
r($testcase->createSceneTest($scene18)) && p('scene:id|product|sort|parent|grade|path;action:objectType|action', '|') && e('5|2|5|0|1|,5,;scene|opened');                 // 不同产品下场景标题重复，创建场景 5 成功后检测场景信息和日志。
