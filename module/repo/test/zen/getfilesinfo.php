#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getFilesInfo();
timeout=0
cid=0

- 有效repoID >> 返回文件信息列表
- 空path >> 返回根目录文件列表
- 指定分支 >> 返回该分支文件列表
- repoID=0 >> 返回空数组
- 不存在的repoID >> 返回空数组

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->getFilesInfoTest(1)) && p() && e(array());                   // 有效repoID
r($zenTest->getFilesInfoTest(1, '')) && p() && e(array());               // 空path
r($zenTest->getFilesInfoTest(1, '', 'main', 'bWFpbg==')) && p() && e(array()); // 指定分支
r($zenTest->getFilesInfoTest(0)) && p() && e(array());                   // repoID=0
r($zenTest->getFilesInfoTest(999)) && p() && e(array());                 // 不存在的repoID
