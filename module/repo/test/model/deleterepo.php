#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::deleteRepo();
timeout=0
cid=18041

- 删除gitlab版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0
- 删除gitea版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0

*/

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('repohistory')->loadYaml('repohistory')->gen(6);
zenData('repofiles')->loadYaml('repofiles')->gen(7);
zenData('repobranch')->loadYaml('repobranch')->gen(2);

$repoTest = new repoModelTest();
$gitlabID = 1;
$giteaID  = 3;

r($repoTest->deleteRepoTest($gitlabID)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0'); //删除gitlab版本库
r($repoTest->deleteRepoTest($giteaID))  && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0'); //删除gitea版本库