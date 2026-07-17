#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->linkObject();
timeout=0
cid=0

- type=story >> 返回结果数量
- type=bug >> 返回结果数量
- type=task >> 返回结果数量
- type=invalid >> 返回0
- type为空 >> 返回0

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->linkObjectTest(1, 'HEAD', 'story')) && p() && e(0);    // type=story
r($zenTest->linkObjectTest(1, 'HEAD', 'bug')) && p() && e(0);      // type=bug
r($zenTest->linkObjectTest(1, 'HEAD', 'task')) && p() && e(0);     // type=task
r($zenTest->linkObjectTest(1, 'HEAD', 'invalid')) && p() && e(0);  // type=invalid
r($zenTest->linkObjectTest(1, 'HEAD', '')) && p() && e(0);         // type为空
