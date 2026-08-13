#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getLastReviewInfo();
timeout=0
cid=0

- 有效entry路径 @0
- 空字符串entry(匹配所有) @1
- 不存在的entry @0
- 普通文件entry @0
- entry包含路径 @0

*/

su('admin');

zendata('bug')->loadYaml('bug_getcommitsbyobject', false, 2)->gen(3);

$repoTest = new repoModelTest();

r($repoTest->getLastReviewInfoTest('src/main.php')) && p() && e('0');   // 有效entry路径
r($repoTest->getLastReviewInfoTest('')) && p() && e('1');               // 空字符串entry(匹配所有)
r($repoTest->getLastReviewInfoTest('nonexistent_file')) && p() && e('0'); // 不存在的entry
r($repoTest->getLastReviewInfoTest('testfile.php')) && p() && e('0');    // 普通文件entry
r($repoTest->getLastReviewInfoTest('path/to/file.php')) && p() && e('0'); // entry包含路径