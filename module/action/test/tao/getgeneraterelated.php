#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getGenerateRelated().
timeout=0
cid=1

- 测试当objectType为bug,objectID为1时，返回的数据是否正确
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101
- 测试当objectType为testreport,objectID为1时，返回的数据是否正确
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('bug')->gen(1);
zdTable('testreport')->gen(1);

$actionTest = new actionTest();

$objectTypeList = array('bug', 'testreport');

r($actionTest->getGenerateRelated($objectTypeList[0], 1)) && p('0:0;1;2') && e('1,11,101');   //测试当objectType为bug,objectID为1时，返回的数据是否正确
r($actionTest->getGenerateRelated($objectTypeList[1], 1)) && p('0:0;1;2') && e('1,11,101');   //测试当objectType为testreport,objectID为1时，返回的数据是否正确