#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getObjectBaseInfo().
timeout=0
cid=1

- 测试获取project=11的首个bug的id,title,project字段
 - 属性id @1
 - 属性title @BUG1
 - 属性project @11
- 测试获取project=11,并且按照id倒叙排序的首个bug的id,title,project字段
 - 属性id @3
 - 属性title @BUG3
 - 属性project @11
- 测试获取name=正常产品1的首个产品的name,code,po1字段
 - 属性name @正常产品1
 - 属性code @code1
 - 属性createdBy @po1
- 测试获取name=正常产品1,并且按照id倒叙排序的首个产品的name,code,po1字段
 - 属性name @正常产品1
 - 属性code @code1
 - 属性createdBy @po1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('bug')->gen(4);
zdTable('product')->gen(4);

$actionIDList = array(1, 2, 3, 0);

$actionTest = new actionTest();

$tableList = array(TABLE_BUG, TABLE_PRODUCT);
$queryParamList = array(array('project' => 11), array('project' => 11), array('name' => '正常产品1', 'code' => 'code1'), array('name' => '正常产品2', 'code' => 'code2'));
$fieldList = array('id,title,project','name,code,createdBy');
$orderByList = array('', 'id desc');

r($actionTest->getObjectBaseInfo($tableList[0], $queryParamList[0], $fieldList[0], $orderByList[0])) && p('id,title,project') && e('1,BUG1,11');  //测试获取project=11的首个bug的id,title,project字段
r($actionTest->getObjectBaseInfo($tableList[0], $queryParamList[0], $fieldList[0], $orderByList[1])) && p('id,title,project') && e('3,BUG3,11');  //测试获取project=11,并且按照id倒叙排序的首个bug的id,title,project字段
r($actionTest->getObjectBaseInfo($tableList[1], $queryParamList[2], $fieldList[1], $orderByList[0])) && p('name,code,createdBy') && e('正常产品1,code1,po1');  //测试获取name=正常产品1的首个产品的name,code,po1字段
r($actionTest->getObjectBaseInfo($tableList[1], $queryParamList[2], $fieldList[1], $orderByList[1])) && p('name,code,createdBy') && e('正常产品1,code1,po1');  //测试获取name=正常产品1,并且按照id倒叙排序的首个产品的name,code,po1字段