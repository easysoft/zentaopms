#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getActionListByTypeAndID().
timeout=0
cid=1

- 测试获取历史例句表中的id为1的记录的objectType和objectID字段的值是否正确,未能获取product字段，所以product为空。
 - 属性id @1
 - 属性objectType @product
 - 属性product @~~
 - 属性objectID @1
- 测试获取项目表中的id为1的记录的project和type字段的值是否正确，未能获取charter字段，所以charter为空。
 - 属性id @1
 - 属性project @0
 - 属性charter @~~
 - 属性type @program

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->gen(1);
zdTable('project')->gen(1);
$actionTest = new actionTest();

r($actionTest->fetchObjectInfoByID(TABLE_ACTION,  1, 'id,objectType,objectID')) && p('id,objectType,product,objectID') && e('1,product,~~,1');   //测试获取历史例句表中的id为1的记录的objectType和objectID字段的值是否正确,未能获取product字段，所以product为空。
r($actionTest->fetchObjectInfoByID(TABLE_PROJECT, 1, 'id,project,type')) && p('id,project,charter,type') && e('1,0,~~,program');                 //测试获取项目表中的id为1的记录的project和type字段的值是否正确，未能获取charter字段，所以charter为空。