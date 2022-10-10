#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/repo.class.php';
su('admin');

/**

title=测试 repoModel->getListBySCM();
cid=1
pid=1

*/

$repo = new repoTest();
$result1 = $repo->getListBySCMTest('Gitlab');
$result2 = $repo->getListBySCMTest('gogs');
$result3 = $repo->getListBySCMTest('empty');

r($result1) && p('') && e('723test');
r($result2) && p('') && e('empty');
r($result3) && p('') && e('empty');
