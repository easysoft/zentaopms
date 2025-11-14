#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('scene')->loadYaml('treescene')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->buildSceneBaseOnCase();
timeout=0
cid=19029

- 测试构建场景 1 rule1 rule2 用例 空 的用例信息 @1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 1 rule3 rule4 rule5 用例 空 的用例信息 @1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 1 rule1 rule2 用例 1,2 的用例信息 @1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 1 rule3 rule4 rule5 用例 1,2 的用例信息 @1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 2 rule1 rule2 用例 空 的用例信息 @2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 2 rule3 rule4 rule5 用例 空 的用例信息 @2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 2 rule1 rule2 用例 1,2 的用例信息 @2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 2 rule3 rule4 rule5 用例 1,2 的用例信息 @2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 20 rule1 rule2 用例 空 的用例信息 @20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 20 rule3 rule4 rule5 用例 空 的用例信息 @20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 20 rule1 rule2 用例 1,2 的用例信息 @20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene

- 测试构建场景 20 rule3 rule4 rule5 用例 1,2 的用例信息 @20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene

*/

$rule1 = array('branch' => array('rule' => 'int'));
$rule2 = array('lib'    => array('rule' => 'int'));
$rule3 = array('type'   => array('rule' => 'string'));
$rule4 = array('pri'    => array('rule' => 'int'));
$rule5 = array('auto'   => array('rule' => 'string'));

$sceneIdList   = array(1, 2, 20);
$fieldTypeList = array(array_merge($rule1, $rule2), array_merge($rule3, $rule4, $rule5));
$caseIdList    = array(array(), array(1, 2));

$testcase = new testcaseTest();

r($testcase->buildSceneBaseOnCaseTest($sceneIdList[0], $fieldTypeList[0], $caseIdList[0])) && p() && e('1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene');             // 测试构建场景 1 rule1 rule2 用例 空 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[0], $fieldTypeList[1], $caseIdList[0])) && p() && e('1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene'); // 测试构建场景 1 rule3 rule4 rule5 用例 空 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[0], $fieldTypeList[0], $caseIdList[1])) && p() && e('1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene');             // 测试构建场景 1 rule1 rule2 用例 1,2 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[0], $fieldTypeList[1], $caseIdList[1])) && p() && e('1: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene'); // 测试构建场景 1 rule3 rule4 rule5 用例 1,2 的用例信息

r($testcase->buildSceneBaseOnCaseTest($sceneIdList[1], $fieldTypeList[0], $caseIdList[0])) && p() && e('2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene');             // 测试构建场景 2 rule1 rule2 用例 空 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[1], $fieldTypeList[1], $caseIdList[0])) && p() && e('2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene'); // 测试构建场景 2 rule3 rule4 rule5 用例 空 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[1], $fieldTypeList[0], $caseIdList[1])) && p() && e('2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene');             // 测试构建场景 2 rule1 rule2 用例 1,2 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[1], $fieldTypeList[1], $caseIdList[1])) && p() && e('2: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene'); // 测试构建场景 2 rule3 rule4 rule5 用例 1,2 的用例信息

r($testcase->buildSceneBaseOnCaseTest($sceneIdList[2], $fieldTypeList[0], $caseIdList[0])) && p() && e('20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene');             // 测试构建场景 20 rule1 rule2 用例 空 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[2], $fieldTypeList[1], $caseIdList[0])) && p() && e('20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene'); // 测试构建场景 20 rule3 rule4 rule5 用例 空 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[2], $fieldTypeList[0], $caseIdList[1])) && p() && e('20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, lib, caseID, bugs, results, caseFails, stepNumber, isScene');             // 测试构建场景 20 rule1 rule2 用例 1,2 的用例信息
r($testcase->buildSceneBaseOnCaseTest($sceneIdList[2], $fieldTypeList[1], $caseIdList[1])) && p() && e('20: id, product, branch, module, title, sort, openedBy, openedDate, lastEditedBy, lastEditedDate, deleted, parent, grade, path, type, pri, auto, caseID, bugs, results, caseFails, stepNumber, isScene'); // 测试构建场景 20 rule3 rule4 rule5 用例 1,2 的用例信息
