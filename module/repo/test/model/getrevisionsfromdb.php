#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getRevisionsFromDB();
timeout=0
cid=1

- 获取版本库提交记录第0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb条的id属性 @3
- 获取limit为1版本库提交记录数量 @1
- 获取maxrevision的列表第0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb条的commit属性 @1
- 获取maxrevision的列表数量 @1
- 获取minrevision的列表第d30919bdb9b4cf8e2698f4a6a30e41910427c01c条的commit属性 @2
- 获取minrevision的列表数量 @1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(3);

$repo = new repoTest();

$repoID      = 3;
$limit       = 1;
$maxRevision = '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb';
$minRevision = 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c';

r($repo->getRevisionsFromDBTest($repoID)) && p('0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb:id') && e('3'); //获取版本库提交记录

$result = $repo->getRevisionsFromDBTest($repoID, $limit);
r(count($result)) && p() && e('1'); //获取limit为1版本库提交记录数量

$result = $repo->getRevisionsFromDBTest($repoID, 0, $maxRevision, '');
r($result) && p('0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb:commit') && e('1'); // 获取maxrevision的列表
r(count($result)) && p() && e('1'); // 获取maxrevision的列表数量

$result = $repo->getRevisionsFromDBTest($repoID, 0, '', $minRevision);
r($result) && p('d30919bdb9b4cf8e2698f4a6a30e41910427c01c:commit') && e('2'); // 获取minrevision的列表
r(count($result)) && p() && e('1'); // 获取minrevision的列表数量