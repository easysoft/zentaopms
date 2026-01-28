#!/usr/bin/env php
<?php
/**

title=测试 designModel->getCommitByID();
cid=15990

- 测试提交记录的ID为0的情况 @0
- 测试提交记录的ID为1的情况
 - 属性id @1
 - 属性repo @1
 - 属性revision @1
 - 属性commit @1
 - 属性comment @提交备注1
 - 属性committer @提交者1
- 测试提交记录的ID不存在的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('repohistory')->loadYaml('repohistory')->gen(1);
zenData('user')->gen(5);

$revisions = array(0, 1, 2);

$designTester = new designModelTest();
r($designTester->getCommitByIDTest($revisions[0])) && p()                                            && e('0');                         // 测试提交记录的ID为0的情况
r($designTester->getCommitByIDTest($revisions[1])) && p('id,repo,revision,commit,comment,committer') && e('1,1,1,1,提交备注1,提交者1'); // 测试提交记录的ID为1的情况
r($designTester->getCommitByIDTest($revisions[2])) && p()                                            && e('0');                         // 测试提交记录的ID不存在的情况
