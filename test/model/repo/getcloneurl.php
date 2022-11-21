#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/repo.class.php';
su('admin');

/**

title=测试 repoModel->getCloneUrl();
cid=1
pid=1

*/

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
