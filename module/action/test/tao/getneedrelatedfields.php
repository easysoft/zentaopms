#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getNeedRelatedFields().
timeout=0
cid=1

- 测试获取id为1的story的相关字段
 -  @1
 - 属性1 @0
 - 属性2 @0
- 测试获取id为1的productplan的相关字段
 -  @1
 - 属性1 @0
 - 属性2 @0
- 测试获取id为1的branch的相关字段
 -  @41
 - 属性1 @0
 - 属性2 @0
- 测试获取id为1的testcase的相关字段
 - 第0条的0属性 @1
 - 属性1 @0
 - 属性2 @101
- 测试获取id为1的case的相关字段
 - 第0条的0属性 @1
 - 属性1 @0
 - 属性2 @101
- 测试获取id为1的kanbanlane的相关字段
 - 第0条的0属性 @0
 - 属性1 @0
 - 属性2 @0
- 测试获取id为1的release的相关字段
 - 第0条的0属性 @~~
 - 属性1 @11
 - 属性2 @0
- 测试获取id为1的task的相关字段
 - 第0条的0属性 @~~
 - 属性1 @11
 - 属性2 @101
- 测试获取id为1的kanbancolumn的相关字段
 - 第0条的0属性 @0
 - 属性1 @0
 - 属性2 @1111
- 测试获取id为1的team的相关字段
 - 第0条的0属性 @0
 - 属性1 @0
 - 属性2 @1
- 测试获取id为1的whhitelist的相关字段
 - 第0条的0属性 @0
 - 属性1 @1
 - 属性2 @0
- 测试获取id为1的whhitelist的相关字段
 - 第0条的0属性 @0
 - 属性1 @0
 - 属性2 @1
- 测试获取id为1的module的相关字段
 - 第0条的0属性 @88
 - 属性1 @0
 - 属性2 @0
- 测试获取id为1的review的相关字段
 - 第0条的0属性 @0
 - 属性1 @1
 - 属性2 @0
- 测试获取id为1的user的相关字段
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('story')->gen(1);
zdTable('productplan')->gen(1);
zdTable('branch')->gen(1);
zdTable('case')->gen(1);
zdTable('repo')->gen(1);
zdTable('kanbanlane')->gen(1);
zdTable('release')->gen(1);
zdTable('task')->gen(1);
zdTable('kanbancolumn')->gen(1);
zdTable('team')->gen(1);
zdTable('module')->config('module')->gen(1);
zdTable('review')->gen(1);
zdTable('testtask')->gen(1);

$actionTest = new actionTest();

$objectTypeList = array('story', 'productplan', 'branch', 'testcase', 'case', 'repo', 'kanbanlane', 'release', 'task', 'kanbancolumn', 'team', 'whitelist', 'module', 'review', 'testtask');
$objectIDList   = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
$actionType     = array('');
$extraList      = array('1111', 'project', 'sprint', '1');

r($actionTest->getNeedRelatedFields($objectTypeList[0],  $objectIDList[0],  $actionType[0], $extraList[0])) && p('0,1,2')   && e('1,0,0');      //测试获取id为1的story的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[1],  $objectIDList[1],  $actionType[0], $extraList[0])) && p('0,1,2')   && e('1,0,0');      //测试获取id为1的productplan的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[2],  $objectIDList[2],  $actionType[0], $extraList[0])) && p('0,1,2')   && e('41,0,0');     //测试获取id为1的branch的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[3],  $objectIDList[3],  $actionType[0], $extraList[0])) && p('0:0;1;2') && e('1,0,101');    //测试获取id为1的testcase的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[4],  $objectIDList[4],  $actionType[0], $extraList[0])) && p('0:0;1;2') && e('1,0,101');    //测试获取id为1的case的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[6],  $objectIDList[6],  $actionType[0], $extraList[0])) && p('0:0;1;2') && e('0,0,0');      //测试获取id为1的kanbanlane的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[7],  $objectIDList[7],  $actionType[0], $extraList[0])) && p('0:0;1;2') && e('~~,11,0');    //测试获取id为1的release的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[8],  $objectIDList[8],  $actionType[0], $extraList[0])) && p('0:0;1;2') && e('~~,11,101');  //测试获取id为1的task的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[9],  $objectIDList[9],  $actionType[0], $extraList[0])) && p('0:0;1;2') && e('0,0,1111');   //测试获取id为1的kanbancolumn的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[10], $objectIDList[10], $actionType[0], $extraList[0])) && p('0:0;1;2') && e('0,0,1');      //测试获取id为1的team的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[11], $objectIDList[11], $actionType[0], $extraList[1])) && p('0:0;1;2') && e('0,1,0');      //测试获取id为1的whhitelist的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[11], $objectIDList[11], $actionType[0], $extraList[2])) && p('0:0;1;2') && e('0,0,1');      //测试获取id为1的whhitelist的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[12], $objectIDList[12], $actionType[0], $extraList[3])) && p('0:0;1;2') && e('88,0,0');     //测试获取id为1的module的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[13], $objectIDList[13], $actionType[0], $extraList[0])) && p('0:0;1;2') && e('0,1,0');      //测试获取id为1的review的相关字段
r($actionTest->getNeedRelatedFields($objectTypeList[14], $objectIDList[14], $actionType[0], $extraList[0])) && p('0:0;1;2') && e('1,11,101');   //测试获取id为1的user的相关字段
