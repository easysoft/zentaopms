#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('action')->gen(0);
zdTable('story')->gen(20);
zdTable('case')->config('confirmstorychange')->gen(4);

/**

title=测试 testcaseModel->batchConfirmStoryChange();
cid=1
pid=1

*/

$testcase   = new testcaseTest();
$caseIdList = array(array(), array(1, 2), array(3, 4), array(5, 6));

r($testcase->batchConfirmStoryChangeTest($caseIdList[0])) && p() && e('0'); // 用例参数为空返回 false。
r($testcase->batchConfirmStoryChangeTest($caseIdList[2])) && p() && e('0'); // 用例参数对应的用例没有关联需求，返回 false。
r($testcase->batchConfirmStoryChangeTest($caseIdList[3])) && p() && e('0'); // 用例参数对应的用例不存在，返回 false。

r($testcase->objectModel->getByList($caseIdList[1])) && p('1:story,storyVersion;2:story,storyVersion') && e('2,1,2,1'); // 批量确认需求变动前需求 2 版本为 1。

global $tester;
$tester->dao->update(TABLE_STORY)->set('version')->eq(2)->where('id')->eq(2)->exec();                          // 更新需求 2 版本号为 2。
r($testcase->batchConfirmStoryChangeTest($caseIdList[1])) && p() && e('1');                                    // 批量确认需求变动成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:story,storyVersion;2:story,storyVersion') && e('2,2,2,2'); // 批量确认需求变动后需求 2 版本为 2。

$tester->dao->update(TABLE_STORY)->set('version')->eq(3)->where('id')->eq(2)->exec();                          // 更新需求 2 版本号为 3。
r($testcase->batchConfirmStoryChangeTest($caseIdList[1])) && p() && e('1');                                    // 批量确认需求变动成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:story,storyVersion;2:story,storyVersion') && e('2,3,2,3'); // 批量确认需求变动后需求 2 版本为 3。

$tester->dao->update(TABLE_STORY)->set('version')->eq(4)->where('id')->eq(2)->exec();                          // 更新需求 2 版本号为 4。
r($testcase->batchConfirmStoryChangeTest($caseIdList[1])) && p() && e('1');                                    // 批量确认需求变动成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:story,storyVersion;2:story,storyVersion') && e('2,4,2,4'); // 批量确认需求变动后需求 2 版本为 4。

$actions = $testcase->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(6)->fetchAll();
r($actions) && p('0:objectType,objectID,action,extra;1:objectType,objectID,action,extra') && e('case,2,confirmed,4,case,1,confirmed,4'); // 批量修改用例类型后记录日志。
r($actions) && p('2:objectType,objectID,action,extra;3:objectType,objectID,action,extra') && e('case,2,confirmed,3,case,1,confirmed,3'); // 批量修改用例类型后记录日志。
r($actions) && p('4:objectType,objectID,action,extra;5:objectType,objectID,action,extra') && e('case,2,confirmed,2,case,1,confirmed,2'); // 批量修改用例类型后记录日志。

r($testcase->batchDeleteTest($caseIdList[1], array()))    && p() && e('1'); // 批量删除用例返回 true。
r($testcase->batchConfirmStoryChangeTest($caseIdList[1])) && p() && e('0'); // 批量确认已删除用例需求变更返回 false。
