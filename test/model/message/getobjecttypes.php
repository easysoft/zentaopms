#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/message.class.php';
su('admin');

/**

title=测试 messageModel->getObjectTypes();
cid=1
pid=1

查询objectType为product的objectTypes >> 产品
查询objectType为story的objectTypes >> 研发需求
查询objectType为productplan的objectTypes >> 计划
查询objectType为project的objectTypes >> 项目
查询objectType为task的objectTypes >> 任务
查询objectType为bug的objectTypes >> Bug
查询objectType为case的objectTypes >> 用例
查询objectType为testcase的objectTypes >> 测试单
查询objectType为todo的objectTypes >> 待办
查询objectType为doc的objectTypes >> 文档
查询objectType为mr的objectTypes >> 合并请求

*/

$message = new messageTest();

r($message->getObjectTypesTest()) && p('product')     && e('产品');     //查询objectType为product的objectTypes
r($message->getObjectTypesTest()) && p('story')       && e('研发需求'); //查询objectType为story的objectTypes
r($message->getObjectTypesTest()) && p('productplan') && e('计划');     //查询objectType为productplan的objectTypes
r($message->getObjectTypesTest()) && p('project')     && e('项目');     //查询objectType为project的objectTypes
r($message->getObjectTypesTest()) && p('task')        && e('任务');     //查询objectType为task的objectTypes
r($message->getObjectTypesTest()) && p('bug')         && e('Bug');      //查询objectType为bug的objectTypes
r($message->getObjectTypesTest()) && p('case')        && e('用例');     //查询objectType为case的objectTypes
r($message->getObjectTypesTest()) && p('testtask')    && e('测试单');   //查询objectType为testcase的objectTypes
r($message->getObjectTypesTest()) && p('todo')        && e('待办');     //查询objectType为todo的objectTypes
r($message->getObjectTypesTest()) && p('doc')         && e('文档');     //查询objectType为doc的objectTypes
r($message->getObjectTypesTest()) && p('mr')          && e('合并请求'); //查询objectType为mr的objectTypes