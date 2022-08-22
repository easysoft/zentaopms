#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->transformActions();
cid=1
pid=1

测试转换动态1 2 3 4 5 >> 0
测试转换动态26 27 28 29 30 >> 1.1;;项目18;开发任务39;
测试转换动态51 52 53 54 55 >> 项目41;开发任务62;;BUG54;这个是测试用例55
测试转换动态71 72 73 74 75 >> 用户需求71;;;项目64;开发任务85
测试转换动态96 97 98 99 100 >> ;项目87;开发任务108;;BUG10

*/
$actions = array('1,2,3,4,5', '26,27,28,29,30', '51,52,53,54,55', '71,72,73,74,75', '96,97,98,99,100');

$action = new actionTest();

r($action->transformActionsTest($actions[0])) && p('1:objectName;2:objectName;3:objectName;4:objectName;5:objectName')       && e('0');                                         // 0
r($action->transformActionsTest($actions[1])) && p('26:objectName;27:objectName;28:objectName;29:objectName;30:objectName')  && e('1.1;;项目18;开发任务39;');                   // 测试转换动态26 27 28 29 30
r($action->transformActionsTest($actions[2])) && p('51:objectName;52:objectName;53:objectName;54:objectName;55:objectName')  && e('项目41;开发任务62;;BUG54;这个是测试用例55'); // 测试转换动态51 52 53 54 55
r($action->transformActionsTest($actions[3])) && p('71:objectName;72:objectName;73:objectName;74:objectName;75:objectName')  && e('用户需求71;;;项目64;开发任务85');            // 测试转换动态71 72 73 74 75
r($action->transformActionsTest($actions[4])) && p('96:objectName;97:objectName;98:objectName;99:objectName;100:objectName') && e(';项目87;开发任务108;;BUG10');                // 测试转换动态96 97 98 99 100
