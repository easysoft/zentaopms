#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 messageModel->batchSaveTodoNotice();
cid=0

- 检查代表信息的链接是否正确 @1
- 检查第一条数据
 - 属性toList @,admin,
 - 属性objectType @message
 - 属性status @wait

*/

zenData('todo')->loadYaml('todo')->gen(20);
zenData('user')->gen(3);
zenData('notify')->gen(0);

global $tester;
$tester->loadModel('message');

$tester->message->app->user->account  = 'admin';
$tester->message->config->requestType = 'PATH_INFO';

$todos = $tester->message->batchSaveTodoNotice();
r(strpos($todos[1]->data, 'todo-view-3.html') !== false) && p() && e('1'); // 检查代表信息的链接是否正确

$messages = $tester->message->dao->select('*')->from(TABLE_NOTIFY)->where('id')->in(array_keys($todos))->fetchAll('id');
r((array)$messages[1]) && p('toList;objectType;status', ';') && e(',admin,;message;wait'); // 检查第一条数据
