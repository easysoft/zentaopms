#!/usr/bin/env php
<?php

/**

title=测试adminModel->setMenu();
timeout=0
cid=0

- 执行admin模块的menuList方法
 - 第system条的name属性 @系统设置
 - 第system条的order属性 @5
- 执行admin模块的menuList方法
 - 第dev条的name属性 @二次开发
 - 第dev条的order属性 @45
- 执行admin模块的menuList方法
 - 第approvalflow条的name属性 @审批流
 - 第approvalflow条的order属性 @73
- 执行admin模块的menuList方法
 - 第productflow条的name属性 @产品流程
 - 第productflow条的order属性 @70
- 执行admin模块的menuList方法
 - 第projectflow条的name属性 @项目流程
 - 第projectflow条的order属性 @71
- 执行admin模块的menuList方法
 - 第workflow条的name属性 @工作流
 - 第workflow条的order属性 @72

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester,$lang;
$tester->loadModel('admin');
r($lang->admin->menuList) && p('system:name,order') && e('系统设置,5');
r($lang->admin->menuList) && p('dev:name,order') && e('二次开发,45');
r($lang->admin->menuList) && p('approvalflow:name,order') && e('审批流,73');
$tester->admin->setMenu();
r($lang->admin->menuList) && p('productflow:name,order') && e('产品流程,70');
r($lang->admin->menuList) && p('projectflow:name,order') && e('项目流程,71');
r($lang->admin->menuList) && p('workflow:name,order') && e('工作流,72');
