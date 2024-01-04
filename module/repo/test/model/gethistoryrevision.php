#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 repoModel::getHistoryRevision();
timeout=0
cid=1

- 查询提交记录版本号 @d30919bdb9b4cf8e2698f4a6a30e41910427c01c
- 查询提交记录版本号withCommmit参数为true
 - 属性revision @d30919bdb9b4cf8e2698f4a6a30e41910427c01c
 - 属性commit @2
- 查询提交记录版本号withCommmit参数为true
 - 属性revision @0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb
 - 属性commit @1

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(3);

$repoModel = $tester->loadModel('repo');

$repoID     = 3;
$revision   = 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c';
$withCommit = true;
$condition  = 'lt';

r($repoModel->getHistoryRevision($repoID, $revision))                          && p()                  && e('d30919bdb9b4cf8e2698f4a6a30e41910427c01c'); //查询提交记录版本号
r($repoModel->getHistoryRevision($repoID, $revision, $withCommit))             && p('revision,commit') && e('d30919bdb9b4cf8e2698f4a6a30e41910427c01c,2'); //查询提交记录版本号withCommmit参数为true
r($repoModel->getHistoryRevision($repoID, $revision, $withCommit, $condition)) && p('revision,commit') && e('0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb,1'); //查询提交记录版本号withCommmit参数为true
