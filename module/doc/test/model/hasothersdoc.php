#!/usr/bin/env php
<?php

/**

title=测试 docModel->hasOthersDoc();
timeout=0
cid=1

- 测试文档库1下是否有其他人创建的文档 @0
- 测试文档库2下是否有其他人创建的文档 @0
- 测试文档库3下是否有其他人创建的文档 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doclib')->loadYaml('doclib')->gen(3);
zenData('user')->gen(5);
zenData('doc')->loadYaml('doc')->gen(5);
su('admin');

global $tester;
$lib1 = $tester->loadModel('doc')->getLibByID(1);
$lib2 = $tester->loadModel('doc')->getLibByID(2);
$lib3 = $tester->loadModel('doc')->getLibByID(3);

r($tester->doc->hasOthersDoc($lib1)) && p() && e('0'); // 测试文档库1下是否有其他人创建的文档
r($tester->doc->hasOthersDoc($lib2)) && p() && e('0'); // 测试文档库2下是否有其他人创建的文档
r($tester->doc->hasOthersDoc($lib3)) && p() && e('0'); // 测试文档库3下是否有其他人创建的文档