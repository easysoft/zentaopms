#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('notify')->gen(0);
zenData('user')->gen(3);

su('admin');

/**

title=测试 kanbanModel->saveCardNotice();
timeout=0
cid=18193

- 保存两条卡片到期提醒后的通知数量 @2
- 验证第一条通知的详情
 - 属性status @wait
 - 属性action @0
 - 属性createdBy @admin
 - 属性sendTime @~~

*/

$cards = array(
    'admin' => array(
        (object)array('id' => 1, 'name' => '卡片1', 'kanban' => 1, 'deadline' => '2026-03-28')
    ),
    'user1' => array(
        (object)array('id' => 2, 'name' => '卡片2', 'kanban' => 2, 'deadline' => '2026-03-28')
    )
);

$kanban = new kanbanModelTest();
$result = $kanban->saveCardNoticeTest($cards);

r(count($result)) && p() && e('2'); // 保存两条卡片到期提醒后的通知数量
r($result[1])     && p('status,action,createdBy,sendTime') && e('wait,0,admin,~~');  // 验证第一条通知的详情