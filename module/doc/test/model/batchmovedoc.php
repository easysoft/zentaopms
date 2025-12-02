#!/usr/bin/env php
<?php

/**

title=测试 docModel->batchMoveDoc();
timeout=0
cid=16043

- 测试ID为空时 @0
- 测试文档1文档2的字段
 - 第1条的lib属性 @100
 - 第1条的module属性 @99
 - 第2条的lib属性 @100
 - 第2条的module属性 @99
- 测试文档3文档4的字段
 - 第3条的lib属性 @100
 - 第3条的module属性 @99
 - 第4条的lib属性 @100
 - 第4条的module属性 @99
- 测试文档5文档6的字段
 - 第5条的lib属性 @100
 - 第5条的module属性 @99
 - 第6条的lib属性 @100
 - 第6条的module属性 @99
- 测试文档7文档8的字段
 - 第7条的lib属性 @100
 - 第7条的module属性 @99
 - 第8条的lib属性 @100
 - 第8条的module属性 @99

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('doc')->gen(20);
zenData('user')->gen(5);
su('admin');

$docData   = array('lib' => 100, 'module' => 99);
$docIdList = array(array(), array(1, 2), array(3, 4), array(5, 6), array(7, 8));

$docTester = new docTest();
r($docTester->batchMoveDocTest($docData, $docIdList[0])) && p('') && e('0');                                      // 测试ID为空时
r($docTester->batchMoveDocTest($docData, $docIdList[1])) && p('1:lib,module;2:lib,module') && e('100,99;100,99'); // 测试文档1文档2的字段
r($docTester->batchMoveDocTest($docData, $docIdList[2])) && p('3:lib,module;4:lib,module') && e('100,99;100,99'); // 测试文档3文档4的字段
r($docTester->batchMoveDocTest($docData, $docIdList[3])) && p('5:lib,module;6:lib,module') && e('100,99;100,99'); // 测试文档5文档6的字段
r($docTester->batchMoveDocTest($docData, $docIdList[4])) && p('7:lib,module;8:lib,module') && e('100,99;100,99'); // 测试文档7文档8的字段
