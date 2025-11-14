#!/usr/bin/env php
<?php

/**

title=测试 repoModel::rmClientVersionFile();
timeout=0
cid=18092

- 测试步骤1：有文件且文件存在 @1
- 测试步骤2：有文件路径但文件不存在 @1
- 测试步骤3：session中为空字符串 @1
- 测试步骤4：session中没有该属性 @1
- 测试步骤5：特殊字符文件名处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repo = new repoTest();

r($repo->rmClientVersionFileTest('existing_file')) && p() && e('1'); //测试步骤1：有文件且文件存在
r($repo->rmClientVersionFileTest('nonexistent_file')) && p() && e('1'); //测试步骤2：有文件路径但文件不存在
r($repo->rmClientVersionFileTest('empty_string')) && p() && e('1'); //测试步骤3：session中为空字符串
r($repo->rmClientVersionFileTest('null')) && p() && e('1'); //测试步骤4：session中没有该属性
r($repo->rmClientVersionFileTest('special_chars')) && p() && e('1'); //测试步骤5：特殊字符文件名处理