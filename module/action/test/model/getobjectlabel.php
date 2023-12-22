#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

/**

title=测试 actionModel->getObjectLabel();
cid=1
pid=1

测试获取objectType product objectId 1的动态信息 >> 产品|product|view|productID=%s
测试获取objectType story objectId 2的动态信息 >> 研发需求|story|view|storyID=%s
测试获取objectType productplan objectId 3的动态信息 >> 计划|productplan|view|productID=%s
测试获取objectType release objectId 4的动态信息 >> 发布|release|view|productID=%s
测试获取objectType project objectId 5的动态信息 >> 项目|project|index|projectID=%s
测试获取objectType task objectId 6的动态信息 >> 任务|task|view|taskID=%s
测试获取objectType build objectId 7的动态信息 >> 版本|build|view|buildID=%s
测试获取objectType bug objectId 8的动态信息 >> Bug|bug|view|bugID=%s
测试获取objectType testcase objectId 9的动态信息 >> testcase
测试获取objectType case objectId 10的动态信息 >> 用例|testcase|view|caseID=%s
测试获取objectType testtask objectId 11的动态信息 >> 测试单|testtask|view|caseID=%s
测试获取objectType user objectId 12的动态信息 >> 用户|user|view|account=%s
测试获取objectType doclib objectId 14的动态信息 >> 文档库|doc|teamspace|objectID=%s&libID=%s
测试获取objectType todo objectId 15的动态信息 >> 待办|todo|view|todoID=%s
测试获取objectType branch objectId 16的动态信息 >> branch
测试获取objectType module objectId 17的动态信息 >> 模块|tree|browse|productid=%s&type=story&currentModuleID=0&branch=all
测试获取objectType testsuite objectId 18的动态信息 >> 测试套件|testsuite|view|suiteID=%s
测试获取objectType caselib objectId 19的动态信息 >> 用例库|caselib|view|libID=%s
测试获取objectType testreport objectId 20的动态信息 >> 报告|testreport|view|report=%s
测试获取objectType entry objectId 21的动态信息 >> 应用|entry|browse|
测试获取objectType webhook objectId 22的动态信息 >> Webhook|webhook|browse|
测试获取objectType review objectId 23的动态信息 >> review
测试获取objectType story objectId 25的动态信息 >> 用户需求|story|view|storyID=%s

*/

$objectType   = array('product', 'story', 'productplan', 'release', 'project', 'task', 'build', 'bug', 'testcase', 'case', 'testtask', 'user', 'doc', 'doclib', 'todo', 'branch', 'module', 'testsuite', 'caselib', 'testreport', 'entry', 'webhook', 'review');
$objectId     = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,25);
$actionType   = array('common', 'extra', 'opened', 'created', 'changed', 'edited', 'assigned', 'closed', 'deleted', 'deletedfile', 'editfile', 'erased', 'undeleted', 'hidden', 'commented', 'activated', 'blocked', 'moved', 'confirmed', 'caseconfirmed', 'bugconfirmed', 'frombug', 'started', 'delayed');
$requirements = array('25' => '25');

$action = new actionTest();

r($action->getObjectLabelTest($objectType[0],  $objectId[0],  $actionType[0],  $requirements)) && p() && e('产品|product|view|productID=%s');                                        // 测试获取objectType product objectId 1的动态信息
r($action->getObjectLabelTest($objectType[1],  $objectId[1],  $actionType[1],  $requirements)) && p() && e('研发需求|story|view|storyID=%s');                                        // 测试获取objectType story objectId 2的动态信息
r($action->getObjectLabelTest($objectType[2],  $objectId[2],  $actionType[2],  $requirements)) && p() && e('计划|productplan|view|productID=%s');                                    // 测试获取objectType productplan objectId 3的动态信息
r($action->getObjectLabelTest($objectType[3],  $objectId[3],  $actionType[3],  $requirements)) && p() && e('发布|release|view|productID=%s');                                        // 测试获取objectType release objectId 4的动态信息
r($action->getObjectLabelTest($objectType[4],  $objectId[4],  $actionType[4],  $requirements)) && p() && e('项目|project|index|projectID=%s');                                       // 测试获取objectType project objectId 5的动态信息
r($action->getObjectLabelTest($objectType[5],  $objectId[5],  $actionType[5],  $requirements)) && p() && e('任务|task|view|taskID=%s');                                              // 测试获取objectType task objectId 6的动态信息
r($action->getObjectLabelTest($objectType[6],  $objectId[6],  $actionType[6],  $requirements)) && p() && e('版本|build|view|buildID=%s');                                            // 测试获取objectType build objectId 7的动态信息
r($action->getObjectLabelTest($objectType[7],  $objectId[7],  $actionType[7],  $requirements)) && p() && e('Bug|bug|view|bugID=%s');                                                 // 测试获取objectType bug objectId 8的动态信息
r($action->getObjectLabelTest($objectType[8],  $objectId[8],  $actionType[8],  $requirements)) && p() && e('testcase');                                                              // 测试获取objectType testcase objectId 9的动态信息
r($action->getObjectLabelTest($objectType[9],  $objectId[9],  $actionType[9],  $requirements)) && p() && e('用例|testcase|view|caseID=%s');                                          // 测试获取objectType case objectId 10的动态信息
r($action->getObjectLabelTest($objectType[10], $objectId[10], $actionType[10], $requirements)) && p() && e('测试单|testtask|view|caseID=%s');                                        // 测试获取objectType testtask objectId 11的动态信息
r($action->getObjectLabelTest($objectType[11], $objectId[11], $actionType[11], $requirements)) && p() && e('用户|user|view|account=%s');                                             // 测试获取objectType user objectId 12的动态信息
r($action->getObjectLabelTest($objectType[13], $objectId[13], $actionType[13], $requirements)) && p() && e('文档库|doc|teamspace|objectID=%s&libID=%s');                             // 测试获取objectType doclib objectId 14的动态信息
r($action->getObjectLabelTest($objectType[14], $objectId[14], $actionType[14], $requirements)) && p() && e('待办|todo|view|todoID=%s');                                              // 测试获取objectType todo objectId 15的动态信息
r($action->getObjectLabelTest($objectType[15], $objectId[15], $actionType[15], $requirements)) && p() && e('branch');                                                                // 测试获取objectType branch objectId 16的动态信息
r($action->getObjectLabelTest($objectType[16], $objectId[16], $actionType[16], $requirements)) && p() && e('模块|tree|browse|productid=%s&view=story&currentModuleID=0&branch=all'); // 测试获取objectType module objectId 17的动态信息
r($action->getObjectLabelTest($objectType[17], $objectId[17], $actionType[17], $requirements)) && p() && e('测试套件|testsuite|view|suiteID=%s');                                    // 测试获取objectType testsuite objectId 18的动态信息
r($action->getObjectLabelTest($objectType[18], $objectId[18], $actionType[18], $requirements)) && p() && e('用例库|caselib|view|libID=%s');                                          // 测试获取objectType caselib objectId 19的动态信息
r($action->getObjectLabelTest($objectType[19], $objectId[19], $actionType[19], $requirements)) && p() && e('报告|testreport|view|report=%s');                                        // 测试获取objectType testreport objectId 20的动态信息
r($action->getObjectLabelTest($objectType[20], $objectId[20], $actionType[20], $requirements)) && p() && e('应用|entry|browse|');                                                    // 测试获取objectType entry objectId 21的动态信息
r($action->getObjectLabelTest($objectType[21], $objectId[21], $actionType[21], $requirements)) && p() && e('Webhook|webhook|browse|');                                               // 测试获取objectType webhook objectId 22的动态信息
r($action->getObjectLabelTest($objectType[22], $objectId[22], $actionType[22], $requirements)) && p() && e('review');                                                                // 测试获取objectType review objectId 23的动态信息
r($action->getObjectLabelTest($objectType[1],  $objectId[23], $actionType[23], $requirements)) && p() && e('用户需求|story|view|storyID=%s');                                        // 测试获取objectType story objectId 25的动态信息
