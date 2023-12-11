#!/usr/bin/env php
<?php

/**

title=测试 docModel->getPrivDocs();
cid=1

- 获取系统中所有的未删除文档 @1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50
- 获取系统中所有的文档 @1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50
- 获取系统中moduleID=1一级子模块所有的文档 @6,7,8,16,17,18,26,27,28,36,37,38,46,47,48
- 获取系统中moduleID=1所有的文档 @6,16,26,36,46
- 获取系统中moduleID=1所有的文档 @7,8,17,18,27,28,37,38,47,48
- 获取系统中moduleID=1所有的未删除文档 @7,8,17,18,27,28,37,38,47,48
- 获取系统中moduleID=3所有的文档 @9,10,19,20,29,30,39,40,49,50
- 获取系统中moduleID=3所有的未删除文档 @9,10,19,20,29,30,39,40,49,50
- 获取系统libID为1,6,11,13中所有的未删除文档 @1,2,6,7,8,9,10,11,12,16,17,18
- 获取系统libID为1,6,11,13中所有的文档 @1,2,6,7,8,9,10,11,12,16,17,18
- 获取系统libID为1,6,11,13中moduleID=1一级子模块所有的文档 @6,7,8,16,17,18
- 获取系统libID为1,6,11,13中moduleID=1所有的文档 @6,16
- 获取系统libID为1,6,11,13中moduleID=1所有未删除的文档 @6,16

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('module')->config('module')->gen(3);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$libIdList[0] = array();
$libIdList[1] = array(1, 6, 11, 13);

$modules = array(0, 1, 2, 3);
$modes   = array('normal', 'all', 'children');

$docTester = new docTest();
r($docTester->getPrivDocsTest($libIdList[0], $modules[0], $modes[0])) && p('', ';') && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50'); // 获取系统中所有的未删除文档
r($docTester->getPrivDocsTest($libIdList[0], $modules[0], $modes[1])) && p('', ';') && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50'); // 获取系统中所有的文档
r($docTester->getPrivDocsTest($libIdList[0], $modules[1], $modes[2])) && p('', ';') && e('6,7,8,16,17,18,26,27,28,36,37,38,46,47,48');                                                                                                    // 获取系统中moduleID=1一级子模块所有的文档
r($docTester->getPrivDocsTest($libIdList[0], $modules[1], $modes[1])) && p('', ';') && e('6,16,26,36,46');                                                                                                                                // 获取系统中moduleID=1所有的文档
r($docTester->getPrivDocsTest($libIdList[0], $modules[2], $modes[1])) && p('', ';') && e('7,8,17,18,27,28,37,38,47,48');                                                                                                                  // 获取系统中moduleID=1所有的文档
r($docTester->getPrivDocsTest($libIdList[0], $modules[2], $modes[0])) && p('', ';') && e('7,8,17,18,27,28,37,38,47,48');                                                                                                                  // 获取系统中moduleID=1所有的未删除文档
r($docTester->getPrivDocsTest($libIdList[0], $modules[3], $modes[1])) && p('', ';') && e('9,10,19,20,29,30,39,40,49,50');                                                                                                                 // 获取系统中moduleID=3所有的文档
r($docTester->getPrivDocsTest($libIdList[0], $modules[3], $modes[0])) && p('', ';') && e('9,10,19,20,29,30,39,40,49,50');                                                                                                                 // 获取系统中moduleID=3所有的未删除文档
r($docTester->getPrivDocsTest($libIdList[1], $modules[0], $modes[0])) && p('', ';') && e('1,2,6,7,8,9,10,11,12,16,17,18');                                                                                                                // 获取系统libID为1,6,11,13中所有的未删除文档
r($docTester->getPrivDocsTest($libIdList[1], $modules[0], $modes[1])) && p('', ';') && e('1,2,6,7,8,9,10,11,12,16,17,18');                                                                                                                // 获取系统libID为1,6,11,13中所有的文档
r($docTester->getPrivDocsTest($libIdList[1], $modules[1], $modes[2])) && p('', ';') && e('6,7,8,16,17,18');                                                                                                                               // 获取系统libID为1,6,11,13中moduleID=1一级子模块所有的文档
r($docTester->getPrivDocsTest($libIdList[1], $modules[1], $modes[1])) && p('', ';') && e('6,16');                                                                                                                                         // 获取系统libID为1,6,11,13中moduleID=1所有的文档
r($docTester->getPrivDocsTest($libIdList[1], $modules[1], $modes[0])) && p('', ';') && e('6,16');                                                                                                                                         // 获取系统libID为1,6,11,13中moduleID=1所有未删除的文档
