#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getObjectBaseInfo().
timeout=0
cid=1

- 获取操作类型为linked2build,extra为1的用户故事相关产品，项目和执行信息。
 - 第0条的0属性 @0
 - 属性1 @11
 - 属性2 @101
- 获取操作类型为unlinkedfrombuild,extra为1的用户故事相关产品，项目和执行信息。
 - 第0条的0属性 @0
 - 属性1 @11
 - 属性2 @101
- 获取操作类型为estimated,extra为1的用户故事相关产品，项目和执行信息。
 - 第0条的0属性 @0
 - 属性1 @11
 - 属性2 @101
- 获取操作类型为created,extra为1的用户故事相关产品，项目和执行信息。
 - 第0条的0属性 @0
 - 属性1 @11
 - 属性2 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('build')->config('build')->gen(1);
zdTable('project')->config('execution')->gen(1);
zdTable('project')->gen(11, false, false);
zdTable('projectstory')->gen(5);

$actionTest = new actionTest();

$actionTypeList = array('linked2build', 'unlinkedfrombuild', 'estimated', 'created');
$objectIDList   = array(1, 2);
$extraList      = array(1, 101);

r($actionTest->getStoryActionRelated($actionTypeList[0], $objectIDList[0], $extraList[0])) && p('0:0;1;2') && e('0,11,101');    //获取操作类型为linked2build,extra为1的用户故事相关产品，项目和执行信息。
r($actionTest->getStoryActionRelated($actionTypeList[1], $objectIDList[0], $extraList[0])) && p('0:0;1;2') && e('0,11,101');    //获取操作类型为unlinkedfrombuild,extra为1的用户故事相关产品，项目和执行信息。
r($actionTest->getStoryActionRelated($actionTypeList[2], $objectIDList[0], $extraList[1])) && p('0:0;1;2') && e('0,11,101');    //获取操作类型为estimated,extra为1的用户故事相关产品，项目和执行信息。
r($actionTest->getStoryActionRelated($actionTypeList[3], $objectIDList[1], $extraList[1])) && p('0:0;1;2') && e('0,11,0');      //获取操作类型为created,extra为1的用户故事相关产品，项目和执行信息。