#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getReleaseRelated().
timeout=0
cid=1

- 测试当objectType为release,objectID为1时，返回的数据是否正确
 - 第0条的0属性 @1
 - 属性1 @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('release')->gen(1);
zdTable('build')->gen(1);

$actionTest = new actionTest();

r($actionTest->getReleaseRelated('release', 1)) && p('0:0;1') && e('1,11');   //测试当objectType为release,objectID为1时，返回的数据是否正确