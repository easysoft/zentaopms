#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* 生成评审动态数据，供reviewed场景使用。 */
$action = zenData('action');
$action->id->range('1,2');
$action->objectType->range('story');
$action->objectID->range('1');
$action->action->range('submitreview,reviewed');
$action->actor->range('user1');
$action->extra->range('pass');
$action->comment->range('同意');
$action->vision->range('rnd');
$action->gen(2);

zenData('user')->gen(1);

su('admin');

/* buildNoticeTextByAccount 需要读取 action 语言包中的对象类型名称和动作标签。 */
global $app;
$app->loadLang('action');
$app->lang->action->objectTypes['story'] = '需求';

/**

title=测试 messageModel::buildNoticeTextByAccount();
timeout=0
cid=17066

- 测试task被opened的通知内容 @用户1 创建了新的任务《任务一》。
- 测试task被edited的通知内容 @用户1 编辑了任务《任务一》的内容。
- 测试task被commented的通知内容 @用户1 在任务《任务一》下添加了新的备注。
- 测试task被closed的通知内容 @用户1 关闭了任务《任务一》。
- 测试指派给user2且接收人为to角色的通知内容 @用户1 将任务《任务一》指派给了用户2。
- 测试指派给自己且接收人为cc角色的通知内容 @用户1 将任务《任务一》指派给了用户1，您已被抄送。
- 测试指派给user2且接收人为cc角色的通知内容 @用户1 将任务《任务一》指派给了用户2，您已被抄送。
- 测试user1账号的通知内容 @user1 创建了新的任务《任务一》。
- 测试未收录账号的通知内容 @user1 创建了新的任务《任务一》。
- 测试cc角色无扩展模板时追加抄送后缀的通知内容 @用户1 创建了新的任务《任务一》。您已被抄送。
- 测试reviewed的通知内容 @您提交的需求《任务一》已由 用户1 处理，结果为：确认通过。
- 测试reviewed的通知内容 @用户1 提交的需求《任务一》已由 用户1 处理，结果为：确认通过。
- 测试数字员工任务完成的通知内容 @您的数字员工任务《任务一》已完成。
- 测试数字员工任务失败的通知内容 @您的数字员工任务《任务一》执行失败。

*/

$message = new messageModelTest();

$users       = array('user1' => '用户1', 'user2' => '用户2');
$object      = new stdclass();
$objectTitle = '任务一';

r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'opened', 1, 'task', $objectTitle, 'to', $users))    && p() && e('用户1 创建了新的任务《任务一》。');                 // 测试task被opened的通知内容
r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'edited', 1, 'task', $objectTitle, 'to', $users))    && p() && e('用户1 编辑了任务《任务一》的内容。');               // 测试task被edited的通知内容
r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'commented', 1, 'task', $objectTitle, 'to', $users)) && p() && e('用户1 在任务《任务一》下添加了新的备注。');           // 测试task被commented的通知内容
r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'closed', 1, 'task', $objectTitle, 'to', $users))    && p() && e('用户1 关闭了任务《任务一》。');                     // 测试task被closed的通知内容

$object->assignedTo = 'user2';
r($message->buildNoticeTextByAccountTest('user1', $object, 'assigned', 1, 'task', $objectTitle, 'to', $users)) && p() && e('用户1 将任务《任务一》指派给了用户2。');              // 测试指派给user2且接收人为to角色的通知内容
$object->assignedTo = 'user1';
r($message->buildNoticeTextByAccountTest('user1', $object, 'assigned', 1, 'task', $objectTitle, 'cc', $users)) && p() && e('用户1 将任务《任务一》指派给了用户1，您已被抄送。'); // 测试指派给自己且接收人为cc角色的通知内容
$object->assignedTo = 'user2';
r($message->buildNoticeTextByAccountTest('user1', $object, 'assigned', 1, 'task', $objectTitle, 'cc', $users)) && p() && e('用户1 将任务《任务一》指派给了用户2，您已被抄送。');    // 测试指派给user2且接收人为cc角色的通知内容

r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'opened', 1, 'task', $objectTitle))     && p() && e('user1 创建了新的任务《任务一》。');                    // 测试user1账号的通知内容
r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'opened', 1, 'task', $objectTitle))     && p() && e('user1 创建了新的任务《任务一》。');                   // 测试未收录账号的通知内容
r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'opened', 1, 'task', $objectTitle, 'cc', $users)) && p() && e('用户1 创建了新的任务《任务一》。您已被抄送。'); // 测试cc角色无扩展模板时追加抄送后缀的通知内容

$object->id = 1;
r($message->buildNoticeTextByAccountTest('user1', $object, 'reviewed', 2, 'story', $objectTitle, 'to', $users))   && p() && e('您提交的需求《任务一》已由 用户1 处理，结果为：确认通过。'); // 测试reviewed的通知内容
r($message->buildNoticeTextByAccountTest('user2', $object, 'reviewed', 2, 'story', $objectTitle, 'to', $users))   && p() && e('用户1 提交的需求《任务一》已由 用户1 处理，结果为：确认通过。'); // 测试reviewed的通知内容
r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'finished', 1, 'aitask', $objectTitle)) && p() && e('您的数字员工任务《任务一》已完成。');                   // 测试数字员工任务完成的通知内容
r($message->buildNoticeTextByAccountTest('user1', new stdclass(), 'failed', 1, 'aitask', $objectTitle))   && p() && e('您的数字员工任务《任务一》执行失败。'); // 测试数字员工任务失败的通知内容
