#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('webhook')->gen(2);

/**

title=测试 webhookModel->getByID();
timeout=0
cid=1

- 通过ID查创建人属性createdBy @admin
- 通过ID查名字属性name @钉钉群机器人
- 查ID为2的创建人属性createdBy @admin
- 查ID为2的名字属性name @钉钉工作消息
- 传入不存在的情况 @0

*/

$webhook = new webhookTest();

$result1 = $webhook->getByIDTest(1);
$result2 = $webhook->getByIDTest(2);
$result3 = $webhook->getByIDTest(1111);

r($result1) && p('createdBy')   && e('admin');        //通过ID查创建人
r($result1) && p('name')        && e('钉钉群机器人'); //通过ID查名字
r($result2) && p('createdBy')   && e('admin');        //查ID为2的创建人
r($result2) && p('name')        && e('钉钉工作消息'); //查ID为2的名字
r($result3) && p('')            && e('0');            //传入不存在的情况