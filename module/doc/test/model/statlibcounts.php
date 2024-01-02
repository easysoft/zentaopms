#!/usr/bin/env php
<?php
/**

title=测试 docModel->statLibCounts();
cid=1

- 文档库ID列表为空时，获取文档、模块的数量 @0
- 文档库ID列表为1-30时，获取文档ID=11、模块的数量属性11 @4
- 文档库ID列表为1-30时，获取文档ID=12、模块的数量属性12 @3
- 文档库ID列表为1-30时，获取文档ID=13、模块的数量属性13 @1
- 文档库ID列表数据不存在时，获取文档、模块的数量属性41 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('module')->config('module')->gen(3);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);

$libIds[0] = array();
$libIds[1] = range(1, 30);
$libIds[2] = range(41, 50);

$docTester = new docTest();
r($docTester->statLibCountsTest($libIds[0])) && p()     && e('0'); // 文档库ID列表为空时，获取文档、模块的数量
r($docTester->statLibCountsTest($libIds[1])) && p('11') && e('4'); // 文档库ID列表为1-30时，获取文档ID=11、模块的数量
r($docTester->statLibCountsTest($libIds[1])) && p('12') && e('3'); // 文档库ID列表为1-30时，获取文档ID=12、模块的数量
r($docTester->statLibCountsTest($libIds[1])) && p('13') && e('1'); // 文档库ID列表为1-30时，获取文档ID=13、模块的数量
r($docTester->statLibCountsTest($libIds[2])) && p('41') && e('0'); // 文档库ID列表数据不存在时，获取文档、模块的数量
