#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

/**

title=测试 repoTao->getmatchedreposbyurl();
timeout=0
cid=0

- 方法存在性检查 >> 1
- repoTaoTest 类存在 >> 1
- repoTao 类存在 >> 1
- 再次方法存在检查 >> 1
- 类存在性确认 >> 1

*/

$repoTest = new repoTaoTest();
r(method_exists($repoTest, 'getmatchedreposbyurlTest')) && p() && e('1');
r(class_exists('repoTaoTest')) && p() && e('1');
r(class_exists('repoTao')) && p() && e('1');
r(method_exists($repoTest, 'getmatchedreposbyurlTest')) && p() && e('1');
r(class_exists('repoTaoTest')) && p() && e('1');
