#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getGenerateRelated().
timeout=0
cid=14948

- 测试当objectType为bug,objectID为1时，返回的数据是否正确
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101
- 测试当objectType为story,objectID为1时，返回的数据是否正确
 - 第0条的0属性 @1
 - 属性1 @0
 - 属性2 @0
- 测试当objectType为task,objectID为1时，返回的数据是否正确
 - 第0条的0属性 @0
 - 属性1 @11
 - 属性2 @101
- 测试当objectType为testreport,objectID为1时，返回的数据是否正确
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101
- 测试当objectType不存在时，返回的数据是否正确
 - 第0条的0属性 @0
 - 属性1 @0
 - 属性2 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('bug')->gen(1);
zenData('story')->gen(1);
zenData('task')->gen(1);
zenData('testreport')->gen(1);

$actionTest = new actionTest();

$objectTypeList = array('bug', 'story', 'task', 'testreport', 'unknown');

r($actionTest->getGenerateRelatedTest($objectTypeList[0], 1)) && p('0:0;1;2', ';') && e('1;11;101'); //测试当objectType为bug,objectID为1时，返回的数据是否正确
r($actionTest->getGenerateRelatedTest($objectTypeList[1], 1)) && p('0:0;1;2', ';') && e('1;0;0');    //测试当objectType为story,objectID为1时，返回的数据是否正确
r($actionTest->getGenerateRelatedTest($objectTypeList[2], 1)) && p('0:0;1;2', ';') && e('0;11;101'); //测试当objectType为task,objectID为1时，返回的数据是否正确
r($actionTest->getGenerateRelatedTest($objectTypeList[3], 1)) && p('0:0;1;2', ';') && e('1;11;101'); //测试当objectType为testreport,objectID为1时，返回的数据是否正确
r($actionTest->getGenerateRelatedTest($objectTypeList[4], 1)) && p('0:0;1;2', ';') && e('0;0;0');    //测试当objectType不存在时，返回的数据是否正确