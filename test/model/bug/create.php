#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->create();
cid=1
pid=1

测试正常的创建代码错误bug >> 代码错误bug一
测试正常的创建配置相关bug >> 1
测试正常的创建安装部署bug >> 101
测试正常的创建安全相关bug >> security
测试正常的创建性能问题bug >> 3
测试正常的创建标准规范bug >> active
测试正常的创建测试脚本bug >> admin
测试正常的创建设计缺陷bug >> 3
测试正常的创建其他bug >> 2021-03-19
测试不输入名称创建bug >> 『Bug标题』不能为空。
测试不输入影响版本创建bug >> 『影响版本』不能为空。
测试指派人bug >> user92

*/

$b_codeerror    = array('title' => '代码错误bug一', 'type' => 'codeerror');
$b_config       = array('title' => '配置相关bug一', 'type' => 'config');
$b_install      = array('title' => '安装部署bug一', 'type' => 'install');
$b_security     = array('title' => '安全相关bug一', 'type' => 'security');
$b_performance  = array('title' => '性能问题bug一', 'type' => 'performance');
$b_standard     = array('title' => '标准规范bug一', 'type' => 'standard');
$b_automation   = array('title' => '测试脚本bug一', 'type' => 'automation');
$b_designdefect = array('title' => '设计缺陷bug一', 'type' => 'designdefect');
$b_others       = array('title' => '其他bug一',     'type' => 'others');
$b_notitle      = array('title' => '',              'type' => 'codeerror');
$b_nobuild      = array('title' => '没有影响版本',  'type' => 'codeerror', 'openedBuild' => '');
$b_assign       = array('title' => '指派人user92',  'type' => 'codeerror', 'assignedTo' => 'user92');

$bug=new bugTest();
r($bug->createObject($b_codeerror))    && p('title')         && e('代码错误bug一');          // 测试正常的创建代码错误bug
r($bug->createObject($b_config))       && p('product')       && e('1');                      // 测试正常的创建配置相关bug
r($bug->createObject($b_install))      && p('execution')     && e('101');                    // 测试正常的创建安装部署bug
r($bug->createObject($b_security))     && p('type')          && e('security');               // 测试正常的创建安全相关bug
r($bug->createObject($b_performance))  && p('pri')           && e('3');                      // 测试正常的创建性能问题bug
r($bug->createObject($b_standard))     && p('status')        && e('active');                 // 测试正常的创建标准规范bug
r($bug->createObject($b_automation))   && p('openedBy')      && e('admin');                  // 测试正常的创建测试脚本bug
r($bug->createObject($b_designdefect)) && p('severity')      && e('3');                      // 测试正常的创建设计缺陷bug
r($bug->createObject($b_others))       && p('deadline')      && e('2021-03-19');             // 测试正常的创建其他bug
r($bug->createObject($b_notitle))      && p('title:0')       && e('『Bug标题』不能为空。');  // 测试不输入名称创建bug
r($bug->createObject($b_nobuild))      && p('openedBuild:0') && e('『影响版本』不能为空。'); // 测试不输入影响版本创建bug
r($bug->createObject($b_assign))       && p('assignedTo')    && e('user92');                 // 测试指派人bug

