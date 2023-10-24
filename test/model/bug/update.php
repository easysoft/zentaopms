#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->update();
cid=1
pid=1

测试更新bug名称 >> title,BUG1,john
测试更新bug类型 >> type,codeerror,config
测试更新bug名称和类型 >> title,john,jack;type,config,install
测试不更改bug名称 >> 没有数据更新
测试不更改bug类型 >> 没有数据更新

*/

$projectIdList = array('1', '2');

$t_uptitle    = array('title' => 'john');
$t_uptype     = array('type'  => 'config');
$t_typetitle  = array('title' => 'jack', 'type' => 'install');
$t_untitle    = array('title' => 'jack');
$t_untype     = array('type'  => 'install');

$bug=new bugTest();
r($bug->updateObject($projectIdList[0], $t_uptitle))   && p('0:field,old,new')                 && e('title,BUG1,john');                     // 测试更新bug名称
r($bug->updateObject($projectIdList[0], $t_uptype))    && p('0:field,old,new')                 && e('type,codeerror,config');               // 测试更新bug类型
r($bug->updateObject($projectIdList[0], $t_typetitle)) && p('0:field,old,new;1:field,old,new') && e('title,john,jack;type,config,install'); // 测试更新bug名称和类型
r($bug->updateObject($projectIdList[0], $t_untitle))   && p()                                  && e('没有数据更新');                        // 测试不更改bug名称
r($bug->updateObject($projectIdList[0], $t_untype))    && p()                                  && e('没有数据更新');                        // 测试不更改bug类型
