#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/message.class.php';
su('admin');

/**

title=测试 messageModel->getObjectActions();
cid=1
pid=1

查询objectType为product的action是opened的lab标签 >> 创建
查询objectType为story的action是frombug的lab标签 >> 转研发需求
查询objectType为productplan的action是edited的lab标签 >> 编辑
查询objectType为project的action是delayed的lab标签 >> 延期
查询objectType为task的action是assigned的lab标签 >> 指派
查询objectType为bug的action是closed的lab标签 >> 关闭
查询objectType为case的action是opended的lab标签 >> 创建
查询objectType为testtask的action是starteded的lab标签 >> 开始
查询objectType为todo的action是edited的lab标签 >> 编辑
查询objectType为doc的action是created的lab标签 >> 创建
查询objectType为mr的action是compilefail的lab标签 >> 构建失败

*/

$message = new messageTest();

r($message->getObjectActionsTest()) && p('product:opened')     && e('创建');       //查询objectType为product的action是opened的lab标签
r($message->getObjectActionsTest()) && p('story:frombug')      && e('转研发需求'); //查询objectType为story的action是frombug的lab标签
r($message->getObjectActionsTest()) && p('productplan:edited') && e('编辑');       //查询objectType为productplan的action是edited的lab标签
r($message->getObjectActionsTest()) && p('project:delayed')    && e('延期');       //查询objectType为project的action是delayed的lab标签
r($message->getObjectActionsTest()) && p('task:assigned')      && e('指派');       //查询objectType为task的action是assigned的lab标签
r($message->getObjectActionsTest()) && p('bug:closed')         && e('关闭');       //查询objectType为bug的action是closed的lab标签
r($message->getObjectActionsTest()) && p('case:opened')        && e('创建');       //查询objectType为case的action是opended的lab标签
r($message->getObjectActionsTest()) && p('testtask:started')   && e('开始');       //查询objectType为testtask的action是starteded的lab标签
r($message->getObjectActionsTest()) && p('todo:edited')        && e('编辑');       //查询objectType为todo的action是edited的lab标签
r($message->getObjectActionsTest()) && p('doc:created')        && e('创建');       //查询objectType为doc的action是created的lab标签
r($message->getObjectActionsTest()) && p('mr:compilefail')     && e('构建失败');   //查询objectType为mr的action是compilefail的lab标签