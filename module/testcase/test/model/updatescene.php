#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('user')->gen('1');
zdTable('scene')->gen(4)->fixPath();
zdTable('action')->gen(0);
zdTable('history')->gen(0);

su('admin');

/**

title=测试 testcaseModel->updateScene();
cid=1
pid=1

*/

$scene1 = array('id' => 1, 'product' => 0,         'title' => '这个是测试场景1');
$scene2 = array('id' => 1, 'product' => '',        'title' => '这个是测试场景1');
$scene3 = array('id' => 1, 'product' => 'product', 'title' => '这个是测试场景1');

$scene4 = array('id' => 1, 'product' => 1, 'title' => '');
$scene5 = array('id' => 1, 'product' => 1, 'title' => '0');
$scene6 = array('id' => 1, 'product' => 1, 'title' => '这是一个很长的测试场景标题，主要目的就是用来测试场景标题字段超出数据库字段长度后能不能正常提示错误信息。如果能够正常提示错误信息，那么这条测试就通过了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。如果不能正常提示错误信息，那么这条测试就失败了。');

$scene7  = array('id' => 1, 'product' => 1, 'title' => '这个是测试场景1', 'branch' => '');
$scene8  = array('id' => 1, 'product' => 1, 'title' => '这个是测试场景1', 'branch' => 'branch');
$scene9  = array('id' => 1, 'product' => 1, 'title' => '这个是测试场景1', 'module' => '');
$scene10 = array('id' => 1, 'product' => 1, 'title' => '这个是测试场景1', 'module' => 'module');
$scene11 = array('id' => 1, 'product' => 1, 'title' => '这个是测试场景1', 'parent' => '');
$scene12 = array('id' => 1, 'product' => 1, 'title' => '这个是测试场景1', 'parent' => 'parent');

$scene13 = array('id' => 1, 'product' => 1, 'title' => '这个是测试场景2');
$scene14 = array('id' => 1, 'product' => 2, 'title' => '这个是测试场景2');
$scene15 = array('id' => 5, 'product' => 1, 'title' => '这个是测试场景5');

$scene16 = array('id' => 2, 'product' => 1, 'parent' => 1);
$scene17 = array('id' => 3, 'product' => 1, 'parent' => 2);
$scene18 = array('id' => 4, 'product' => 1, 'parent' => 0);
$scene19 = array('id' => 2, 'product' => 1, 'parent' => 4);

$testcase = new testcaseTest();

r($testcase->updateSceneTest($scene1))  && p('product:0') && e('『所属产品』不能为空。');                           // 所属产品设为数字 0 输出错误提示。
r($testcase->updateSceneTest($scene2))  && p('product:0') && e('『所属产品』应当是数字。');                         // 所属产品设为空字符串输出错误提示。
r($testcase->updateSceneTest($scene3))  && p('product:0') && e('『所属产品』应当是数字。');                         // 所属产品设为字符串输出错误提示。
r($testcase->updateSceneTest($scene4))  && p('title:0')   && e('『场景名称』不能为空。');                           // 场景标题设为空字符串输出错误提示。
r($testcase->updateSceneTest($scene5))  && p('title:0')   && e('『场景名称』不能为空。');                           // 场景标题设为字符串 0 输出错误提示。
r($testcase->updateSceneTest($scene6))  && p('title:0')   && e('『场景名称』长度应当不超过『255』，且大于『0』。'); // 场景标题超过数据库字段长度输出错误提示。
r($testcase->updateSceneTest($scene7))  && p('branch:0')  && e('『所属分支』应当是数字。');                         // 所属分支设为字符串输出错误提示。
r($testcase->updateSceneTest($scene8))  && p('branch:0')  && e('『所属分支』应当是数字。');                         // 所属分支设为字符串输出错误提示。
r($testcase->updateSceneTest($scene9))  && p('module:0')  && e('『所属模块』应当是数字。');                         // 所属模块设为字符串输出错误提示。
r($testcase->updateSceneTest($scene10)) && p('module:0')  && e('『所属模块』应当是数字。');                         // 所属模块设为字符串输出错误提示。
r($testcase->updateSceneTest($scene11)) && p('parent:0')  && e('『父场景』应当是数字。');                           // 父场景设为字符串输出错误提示。
r($testcase->updateSceneTest($scene12)) && p('parent:0')  && e('『父场景』应当是数字。');                           // 父场景设为字符串输出错误提示。

r($testcase->updateSceneTest($scene13)) && p('title:0')   && e('『场景名称』已经有『这个是测试场景2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 同产品下场景标题重复输出错误提示。
r($testcase->updateSceneTest($scene14)) && p('scene:id|product|title;action:objectType|action', '|') && e('1|2|这个是测试场景2;scene|edited');                              // 不同产品下场景标题重复，编辑场景 1 成功后检测场景信息和日志。

r($testcase->updateSceneTest($scene15)) && p() && e(0); // 场景 ID 对应的场景不存在返回 false。

r($testcase->updateSceneTest($scene16)) && p('scene:id|sort|parent|grade|path;action:objectType|action;history[0]:field|old|new;history[1]:field|old|new;history[2]:field|old|new;history[3]:field|old|new', '|') && e('2|2|1|2|,1,2,;scene|edited;product|1|2;parent|0|1;path|,2,|,1,2,;grade|1|2');     // 编辑场景 2 成功后检测场景信息和日志。
r($testcase->updateSceneTest($scene17)) && p('scene:id|sort|parent|grade|path;action:objectType|action;history[0]:field|old|new;history[1]:field|old|new;history[2]:field|old|new;history[3]:field|old|new', '|') && e('3|3|2|3|,1,2,3,;scene|edited;product|1|2;parent|0|2;path|,3,|,1,2,3,;grade|1|3'); // 编辑场景 3 成功后检测场景信息和日志。
r($testcase->updateSceneTest($scene18)) && p('scene:id|sort|parent|grade|path;action:objectType|action;history[0]:field|old|new;history[1]:field|old|new;history[2]:field|old|new',                          '|') && e('4|4|0|1|,4,;scene|edited;parent|1|0;path|,1,4,|,4,;grade|2|1');                   // 编辑场景 4 成功后检测场景信息和日志。
r($testcase->updateSceneTest($scene19)) && p('scene:id|sort|parent|grade|path;action:objectType|action;history[0]:field|old|new;history[1]:field|old|new;history[2]:field|old|new',                          '|') && e('2|2|4|2|,4,2,;scene|edited;product|2|1;parent|1|4;path|,1,2,|,4,2,');             // 编辑场景 2 成功后检测场景信息和日志。
