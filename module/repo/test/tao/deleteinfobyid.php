#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel::deleteInfoByID();
timeout=0
cid=18116

- 删除gitlab版本库
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0
- 删除gitea版本库
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0

*/

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('repohistory')->loadYaml('repohistory')->gen(6);
zenData('repofiles')->loadYaml('repofiles')->gen(7);
zenData('repobranch')->loadYaml('repobranch')->gen(2);

$repoTest = new repoTest();
$gitlabID = 1;
$giteaID  = 3;

r($repoTest->deleteInfoByIDTest($gitlabID)) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0'); //删除gitlab版本库
r($repoTest->deleteInfoByIDTest($giteaID))  && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0'); //删除gitea版本库