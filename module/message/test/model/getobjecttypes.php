#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/message.unittest.class.php';

zenData('lang')->gen(0);
zenData('user')->gen(1);

su('admin');

/**

title=测试 messageModel->getObjectTypes();
cid=17054
pid=1

- 查询objectType为product的objectTypes属性product @产品
- 查询objectType为story的objectTypes属性story @研发需求
- 查询objectType为productplan的objectTypes属性productplan @计划
- 查询objectType为project的objectTypes属性project @项目
- 查询objectType为task的objectTypes属性task @任务
- 查询objectType为bug的objectTypes属性bug @Bug
- 查询objectType为case的objectTypes属性case @用例
- 查询objectType为testcase的objectTypes属性testtask @测试单
- 查询objectType为todo的objectTypes属性todo @待办
- 查询objectType为doc的objectTypes属性doc @文档
- 查询objectTypes的key值 @product,epic,requirement,story,productplan,release,project,execution,task,bug,case,testtask,todo,doc,kanbancard
- 查询objectTypes的value值 @产品,业务需求,用户需求,研发需求,计划,发布,项目,执行,任务,Bug,用例,测试单,待办,文档,看板卡片

*/

global $lang, $app, $conifg;
$lang->SRCommon = '研发需求';
$lang->URCommon = '用户需求';
$lang->ERCommon = '业务需求';
$app::$loadedLangs = array();
$app->loadLang('action');

$message = new messageTest();
$objectTypes = $message->getObjectTypesTest();

r($objectTypes) && p('product')     && e('产品');     //查询objectType为product的objectTypes
r($objectTypes) && p('story')       && e('研发需求'); //查询objectType为story的objectTypes
r($objectTypes) && p('productplan') && e('计划');     //查询objectType为productplan的objectTypes
r($objectTypes) && p('project')     && e('项目');     //查询objectType为project的objectTypes
r($objectTypes) && p('task')        && e('任务');     //查询objectType为task的objectTypes
r($objectTypes) && p('bug')         && e('Bug');      //查询objectType为bug的objectTypes
r($objectTypes) && p('case')        && e('用例');     //查询objectType为case的objectTypes
r($objectTypes) && p('testtask')    && e('测试单');   //查询objectType为testcase的objectTypes
r($objectTypes) && p('todo')        && e('待办');     //查询objectType为todo的objectTypes
r($objectTypes) && p('doc')         && e('文档');     //查询objectType为doc的objectTypes

r(implode(',', array_slice(array_keys($objectTypes), 0, 15)))   && p() && e('product,epic,requirement,story,productplan,release,project,execution,task,bug,case,testtask,todo,doc,kanbancard'); //查询objectTypes的key值
r(implode(',', array_slice(array_values($objectTypes), 0, 15))) && p() && e('产品,业务需求,用户需求,研发需求,计划,发布,项目,执行,任务,Bug,用例,测试单,待办,文档,看板卡片');                     //查询objectTypes的value值
