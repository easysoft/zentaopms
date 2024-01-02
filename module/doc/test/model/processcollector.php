#!/usr/bin/env php
<?php
/**

title=测试 docModel->processCollector();
cid=1

- 测试空数据 @0
- 测试正常数据
 - 第1条的id属性 @1
 - 第1条的project属性 @0
 - 第1条的product属性 @0
 - 第1条的execution属性 @0
 - 第1条的module属性 @0
 - 第1条的title属性 @文档标题1
 - 第1条的keywords属性 @关键词1
 - 第1条的type属性 @text
 - 第1条的status属性 @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doccontent')->config('doccontent')->gen(10);
zdTable('docaction')->config('docaction')->gen(20);
zdTable('doc')->config('doc')->gen(10);
su('admin');

$docIdList[0] = array();
$docIdList[1] = range(1, 10);

$docTester = new docTest();
r($docTester->processCollectorTest($docIdList[0])) && p()                                                                   && e('0');                                       // 测试空数据
r($docTester->processCollectorTest($docIdList[1])) && p('1:id,project,product,execution,module,title,keywords,type,status') && e('1,0,0,0,0,文档标题1,关键词1,text,normal'); // 测试正常数据
