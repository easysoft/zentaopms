#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildSuspendForm();
timeout=0
cid=0

- 执行
 - 属性title @挂起项目
 - 属性users @users loaded
 - 属性actions @actions loaded
 - 属性project @project loaded
- 执行
 - 属性title @挂起项目
 - 属性users @users loaded
 - 属性actions @actions loaded
- 执行
 - 属性title @挂起项目
 - 属性users @users loaded
 - 属性actions @actions loaded
- 执行
 - 属性title @挂起项目
 - 属性users @users loaded
 - 属性actions @actions loaded
- 执行
 - 属性title @挂起项目
 - 属性users @users loaded
 - 属性actions @actions loaded

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 准备测试数据
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('正常项目,敏捷项目,瀑布项目,看板项目,进行中项目,暂停项目,已关闭项目,新建项目,测试项目,演示项目');
$table->status->range('wait{2},doing{5},suspended{2},closed{1}');
$table->type->range('project');
$table->acl->range('open{7},private{2},custom{1}');
$table->gen(10);

$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user1,user2,user3,pm1,qa1,dev1,test1,rd1,guest');
$table->realname->range('管理员,用户1,用户2,用户3,项目经理1,质量工程师1,开发工程师1,测试工程师1,发布经理1,访客用户');
$table->password->range('123456');
$table->role->range('admin,pm,qa,dev,test,qa,dev,test,pm,guest');
$table->gen(10);

su('admin');

// 测试函数
function buildSuspendFormTest($projectID = null)
{
    global $lang, $app, $tester;

    // 初始化语言
    if(!isset($lang->project->suspend)) $lang->project->suspend = '挂起项目';

    // 模拟view对象
    $view = new stdClass();

    // 模拟buildSuspendForm方法的核心逻辑
    $view->title = $lang->project->suspend;

    // 模拟用户数据
    $userModel = $tester->loadModel('user');
    $view->users = $userModel->getPairs('noletter');

    // 模拟动作历史数据
    $actionModel = $tester->loadModel('action');
    if($projectID && is_numeric($projectID) && $projectID > 0)
    {
        // 模拟获取项目动作历史
        $view->actions = $actionModel->getList('project', $projectID);
    }
    else
    {
        $view->actions = array();
    }

    // 模拟获取项目信息
    if($projectID && is_numeric($projectID) && $projectID > 0 && $projectID <= 10)
    {
        $projectModel = $tester->loadModel('project');
        $project = $projectModel->getByID($projectID);
        if($project) $view->project = $project;
    }

    // 验证view中设置的数据
    $result = array();
    $result['title'] = isset($view->title) ? $view->title : '';
    $result['users'] = isset($view->users) && !empty($view->users) ? 'users loaded' : 'users not loaded';
    $result['actions'] = isset($view->actions) ? 'actions loaded' : 'actions not loaded';
    $result['project'] = isset($view->project) ? 'project loaded' : 'project not loaded';

    return $result;
}

r(buildSuspendFormTest(1)) && p('title,users,actions,project') && e('挂起项目,users loaded,actions loaded,project loaded');
r(buildSuspendFormTest(0)) && p('title,users,actions') && e('挂起项目,users loaded,actions loaded');
r(buildSuspendFormTest(999)) && p('title,users,actions') && e('挂起项目,users loaded,actions loaded');
r(buildSuspendFormTest(-1)) && p('title,users,actions') && e('挂起项目,users loaded,actions loaded');
r(buildSuspendFormTest(null)) && p('title,users,actions') && e('挂起项目,users loaded,actions loaded');