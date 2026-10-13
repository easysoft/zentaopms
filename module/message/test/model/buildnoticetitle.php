#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

su('admin');

/* buildNoticeTitle 需要读取 action 语言包中的对象类型名称。 */
global $app;
$app->loadLang('action');

/**

title=测试 messageModel::buildNoticeTitle();
timeout=0
cid=17065

- 测试task被opened的消息标题 @新的任务已创建
- 测试task被edited的消息标题 @任务信息已更新
- 测试task被commented的消息标题 @任务有新备注
- 测试task被closed的消息标题 @任务已关闭
- 测试bug被resolved的消息标题 @Bug已解决
- 测试指派给user2且接收人为to角色的消息标题 @用户2被指派处理任务
- 测试指派给自己且接收人为cc角色的消息标题 @用户1被指派处理任务
- 测试指派给user2且接收人为cc角色的消息标题 @用户2被指派处理任务
- 测试指派给自己且接收人为to角色的消息标题 @您被指派处理任务
- 测试指派给未收录用户的消息标题 @user9被指派处理任务
- 测试未指派的消息标题 @被指派处理任务
- 测试不存在的对象类型的消息标题 @新的notexist已创建
- 测试数字员工任务完成的消息标题 @数字员工任务完成
- 测试数字员工任务失败的消息标题 @数字员工任务执行失败

*/

$message = new messageModelTest();

$users  = array('user1' => '用户1', 'user2' => '用户2');
$object = new stdclass();
$object->assignedTo = 'user2';

r($message->buildNoticeTitleTest('task', 'opened', 'user1', new stdclass(), 'to', $users))    && p() && e('新的任务已创建');           // 测试task被opened的消息标题
r($message->buildNoticeTitleTest('task', 'edited', 'user1', new stdclass(), 'to', $users))    && p() && e('任务信息已更新');           // 测试task被edited的消息标题
r($message->buildNoticeTitleTest('task', 'commented', 'user1', new stdclass(), 'to', $users)) && p() && e('任务有新备注');           // 测试task被commented的消息标题
r($message->buildNoticeTitleTest('task', 'closed', 'user1', new stdclass(), 'to', $users))    && p() && e('任务已关闭');           // 测试task被closed的消息标题
r($message->buildNoticeTitleTest('bug', 'resolved', 'user1', new stdclass(), 'to', $users))   && p() && e('Bug已解决');            // 测试bug被resolved的消息标题

r($message->buildNoticeTitleTest('task', 'assigned', 'user1', $object, 'to', $users))         && p() && e('用户2被指派处理任务');           // 测试指派给user2且接收人为to角色的消息标题
$object->assignedTo = 'user1';
r($message->buildNoticeTitleTest('task', 'assigned', 'user1', $object, 'cc', $users))         && p() && e('用户1被指派处理任务');           // 测试指派给自己且接收人为cc角色的消息标题
$object->assignedTo = 'user2';
r($message->buildNoticeTitleTest('task', 'assigned', 'user1', $object, 'cc', $users))         && p() && e('用户2被指派处理任务');           // 测试指派给user2且接收人为cc角色的消息标题
$object->assignedTo = 'user1';
r($message->buildNoticeTitleTest('task', 'assigned', 'user1', $object, 'to', $users))         && p() && e('您被指派处理任务');           // 测试指派给自己且接收人为to角色的消息标题

$object->assignedTo = 'user9';
r($message->buildNoticeTitleTest('task', 'assigned', 'user1', $object, 'to', $users))         && p() && e('user9被指派处理任务');           // 测试指派给未收录用户的消息标题
$object->assignedTo = '';
r($message->buildNoticeTitleTest('task', 'assigned', 'user1', $object, 'to', $users))         && p() && e('被指派处理任务');           // 测试未指派的消息标题

r($message->buildNoticeTitleTest('notexist', 'opened', 'user1', new stdclass(), 'to', $users)) && p() && e('新的notexist已创建');  // 测试不存在的对象类型的消息标题
r($message->buildNoticeTitleTest('aitask', 'finished', 'user1', new stdclass(), 'to', $users)) && p() && e('数字员工任务完成');    // 测试数字员工任务完成的消息标题
r($message->buildNoticeTitleTest('aitask', 'failed', 'user1', new stdclass(), 'to', $users))   && p() && e('数字员工任务执行失败'); // 测试数字员工任务失败的消息标题
