#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getCloneUrl();
timeout=0
cid=1

- 执行$result1属性http @
- 执行$result2属性http @
- 执行$result3属性http @
- 执行$result4属性http @
- 执行$result5属性http @
- 执行$result6属性http @
- 执行$result7属性http @

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repo = new repoTest();
$result1 = $repo->getCloneUrlTest(1);
$result2 = $repo->getCloneUrlTest(2);
$result3 = $repo->getCloneUrlTest(3);
$result4 = $repo->getCloneUrlTest(4);
$result5 = $repo->getCloneUrlTest(7);
$result6 = $repo->getCloneUrlTest(11);
$result7 = $repo->getCloneUrlTest(0);

r($result1) && p('http') && e('');
r($result2) && p('http') && e('');
r($result3) && p('http') && e('');
r($result4) && p('http') && e('');
r($result5) && p('http') && e('');
r($result6) && p('http') && e('');
r($result7) && p('http') && e('');
