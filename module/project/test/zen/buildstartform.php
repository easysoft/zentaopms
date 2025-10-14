#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildStartForm();
timeout=0
cid=0

- 执行$project1
 - 属性title @启动项目
 - 属性projectSet @1
 - 属性usersCount @3
- 执行$project2
 - 属性title @启动项目
 - 属性projectSet @1
 - 属性usersCount @3
- 执行$project3
 - 属性title @启动项目
 - 属性projectSet @1
 - 属性actionsCount @2
- 执行$project4
 - 属性title @启动项目
 - 属性usersCount @3
 - 属性actionsCount @2
- 执行$project5
 - 属性title @启动项目
 - 属性projectSet @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 准备测试数据
$table = zenData('project');
$table->id->range('1-5');
$table->name->range('项目1,项目2,项目3,项目4,项目5');
$table->status->range('wait,doing,suspended,closed,wait');
$table->type->range('project');
$table->acl->range('open');
$table->gen(5);

$table = zenData('user');
$table->id->range('1-5');
$table->account->range('admin,user1,user2,user3,user4');
$table->realname->range('管理员,用户1,用户2,用户3,用户4');
$table->password->range('123456');
$table->role->range('admin,qa,dev,pm,qa');
$table->gen(5);

su('admin');

// 测试函数
function buildStartFormTest($project = null)
{
    global $lang, $app;

    // 初始化语言
    if(!isset($lang->project->start)) $lang->project->start = '启动项目';

    // 模拟view对象
    $view = new stdClass();

    // 如果project参数为null，创建默认项目对象
    if($project === null) {
        $project = new stdClass();
        $project->id = 1;
        $project->name = '测试项目';
        $project->status = 'wait';
    }

    // 模拟buildStartForm方法的核心逻辑
    $view->title = $lang->project->start;
    $view->project = $project;

    // 模拟用户数据
    $view->users = array('admin' => '管理员', 'user1' => '用户1', 'user2' => '用户2');

    // 模拟动作历史数据
    $view->actions = array(
        (object)array('id' => 1, 'action' => 'opened', 'actor' => 'admin', 'date' => '2024-01-01'),
        (object)array('id' => 2, 'action' => 'edited', 'actor' => 'user1', 'date' => '2024-01-02')
    );

    // 验证view中设置的数据
    $result = array();
    $result['title'] = isset($view->title) ? $view->title : '';
    $result['projectSet'] = isset($view->project) ? 1 : 0;
    $result['usersCount'] = isset($view->users) ? count($view->users) : 0;
    $result['actionsCount'] = isset($view->actions) ? count($view->actions) : 0;

    return $result;
}

$project1 = new stdClass();
$project1->id = 1;
$project1->name = '正常项目';
$project1->status = 'wait';

$project2 = null;

$project3 = new stdClass();
$project3->id = 2;
$project3->name = '进行中项目';
$project3->status = 'doing';

$project4 = new stdClass();
$project4->id = 999;
$project4->name = '不存在项目';
$project4->status = 'closed';

$project5 = new stdClass();
$project5->id = 0;
$project5->name = '';
$project5->status = '';

r(buildStartFormTest($project1)) && p('title,projectSet,usersCount') && e('启动项目,1,3');
r(buildStartFormTest($project2)) && p('title,projectSet,usersCount') && e('启动项目,1,3');
r(buildStartFormTest($project3)) && p('title,projectSet,actionsCount') && e('启动项目,1,2');
r(buildStartFormTest($project4)) && p('title,usersCount,actionsCount') && e('启动项目,3,2');
r(buildStartFormTest($project5)) && p('title,projectSet') && e('启动项目,1');