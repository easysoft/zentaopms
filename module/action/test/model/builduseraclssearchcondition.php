#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(5);
$project = zenData('project');
$project->project->range('0-4');
$project->parent->range('0-4');
$project->gen(5);

su('admin');

/**

title=测试 actionModel->buildUserAclsSearchCondition();
timeout=0
cid=1

- 执行actionModel模块的buildUserAclsSearchCondition方法，参数是'all', 'all', 'all'  @((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product IN ('1','2','3','4')) OR (execution = '0' AND project != '0' AND project IN ('1','2','3','4')) OR (execution != '0' AND execution IN ('5','6','7','8'))
- 执行actionModel模块的buildUserAclsSearchCondition方法，参数是'1', 'all', 'all'  @((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '')
- 执行actionModel模块的buildUserAclsSearchCondition方法，参数是'all', '1', 'all'  @((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = 'all') OR (execution = '0' AND project = '1') OR (execution != '0' AND execution = '')
- 执行actionModel模块的buildUserAclsSearchCondition方法，参数是'all', 'all', '5'  @((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product IN ('1','2','3','4')) OR (execution = '0' AND project != '0' AND project IN ('1','2','3','4')) OR (execution != '0' AND execution = '5')
- 执行actionModel模块的buildUserAclsSearchCondition方法，参数是'1', '1', 'all'  @((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '')
- 执行actionModel模块的buildUserAclsSearchCondition方法，参数是'1', 'all', '5'  @((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '0')
- 执行actionModel模块的buildUserAclsSearchCondition方法，参数是'1', '4', '5'  @((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '0')

*/

global $tester, $app;
$actionModel = $tester->loadModel('action');

$app->user->admin = true;
$app->user->view  = new stdclass();
$app->user->view->products = '1,2,3,4';
$app->user->view->projects = '1,2,3,4';
$app->user->view->sprints  = '5,6,7,8';
$app->user->rights['acls'] = array();

$executions = array();
r($actionModel->buildUserAclsSearchCondition('all', 'all', 'all')) && p() && e("((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product IN ('1','2','3','4')) OR (execution = '0' AND project != '0' AND project IN ('1','2','3','4')) OR (execution != '0' AND execution IN ('5','6','7','8'))");
r($actionModel->buildUserAclsSearchCondition('1',   'all', 'all')) && p() && e("((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '')");
r($actionModel->buildUserAclsSearchCondition('all', '1',   'all')) && p() && e("((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = 'all') OR (execution = '0' AND project = '1') OR (execution != '0' AND execution = '')");
r($actionModel->buildUserAclsSearchCondition('all', 'all', '5'))   && p() && e("((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product IN ('1','2','3','4')) OR (execution = '0' AND project != '0' AND project IN ('1','2','3','4')) OR (execution != '0' AND execution = '5')");
r($actionModel->buildUserAclsSearchCondition('1',   '1',   'all')) && p() && e("((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '')");
r($actionModel->buildUserAclsSearchCondition('1',   'all', '5'))   && p() && e("((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '0')");
r($actionModel->buildUserAclsSearchCondition('1',   '4',   '5'))   && p() && e("((action.product =',0,' OR action.product = '0' OR action.product=',,') AND project = '0' AND execution = '0')  OR (execution = '0' AND project = '0' AND t2.product = '1') OR (execution = '0' AND project != '0' AND project = '') OR (execution != '0' AND execution = '0')");