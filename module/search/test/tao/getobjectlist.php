#!/usr/bin/env php
<?php

/**

title=测试 searchModel->getParamValuesTest();
timeout=0
cid=1

- 测试获取搜索到的项目对象
 - 第1条的id属性 @1
 - 第1条的model属性 @scrum
- 测试获取搜索到的项目对象
 - 第2条的id属性 @2
 - 第2条的model属性 @scrum
- 测试获取搜索到的迭代对象
 - 第3条的id属性 @3
 - 第3条的type属性 @sprint
- 测试获取搜索到的阶段对象
 - 第4条的id属性 @4
 - 第4条的type属性 @stage
- 测试获取搜索到的看板对象
 - 第5条的id属性 @5
 - 第5条的type属性 @kanan
- 测试获取搜索到的研发需求对象
 - 第1条的id属性 @1
 - 第1条的type属性 @story
- 测试获取搜索到的用户需求对象
 - 第2条的id属性 @2
 - 第2条的type属性 @requirement

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('issue')->gen(2);
zdTable('story')->gen(2);

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->model->range('scrum{2},[]{3}');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$idListGroup = array();
$idListGroup['project']   = array(1, 2);
$idListGroup['execution'] = array(3, 4, 5);
$idListGroup['story']     = array(1, 2);

$typeList = array('project', 'execution', 'story');

$search = new searchTest();
r($search->getObjectListTest($idListGroup, $typeList[0])) && p('1:id,model') && e('1,scrum');       //测试获取搜索到的项目对象
r($search->getObjectListTest($idListGroup, $typeList[0])) && p('2:id,model') && e('2,scrum');       //测试获取搜索到的项目对象
r($search->getObjectListTest($idListGroup, $typeList[1])) && p('3:id,type')  && e('3,sprint');      //测试获取搜索到的迭代对象
r($search->getObjectListTest($idListGroup, $typeList[1])) && p('4:id,type')  && e('4,stage');       //测试获取搜索到的阶段对象
r($search->getObjectListTest($idListGroup, $typeList[1])) && p('5:id,type')  && e('5,kanban');      //测试获取搜索到的看板对象
r($search->getObjectListTest($idListGroup, $typeList[2])) && p('1:id,type')  && e('1,requirement'); //测试获取搜索到的用户需求对象
r($search->getObjectListTest($idListGroup, $typeList[2])) && p('2:id,type')  && e('2,story');       //测试获取搜索到的研发需求对象
