#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getListBySCM();
timeout=0
cid=1

- 执行$result1第1条的name属性 @testHtml
- 执行$result2 @empty
- 执行$result3 @empty

*/

zdTable('repo')->config('repo')->gen(4);

$repo = new repoTest();
$result1 = $repo->getListBySCMTest('Gitlab');
$result2 = $repo->getListBySCMTest('gogs');
$result3 = $repo->getListBySCMTest('empty');

r($result1) && p('1:name') && e('testHtml');
r($result2) && p('')       && e('empty');
r($result3) && p('')       && e('empty');