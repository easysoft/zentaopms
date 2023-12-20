#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

/**

title=测试 mrModel->unlink();
timeout=0
cid=1

- 取消关联需求
 - 属性id @1
 - 属性objectType @story
 - 属性action @deletemr
- 取消关联bug
 - 属性id @2
 - 属性objectType @bug
 - 属性action @deletemr
- 取消关联任务
 - 属性id @3
 - 属性objectType @task
 - 属性action @deletemr
- 取消关联错误的类型 @0

*/

zdTable('action')->gen(0);
zdTable('relation')->config('relation')->gen(30);

$mrModel = new mrTest();
r($mrModel->unlinkTester(1, 'story')) && p('id,objectType,action') && e('1,story,deletemr'); // 取消关联需求
r($mrModel->unlinkTester(1, 'bug'))   && p('id,objectType,action') && e('2,bug,deletemr');   // 取消关联bug
r($mrModel->unlinkTester(1, 'task'))  && p('id,objectType,action') && e('3,task,deletemr');  // 取消关联任务

r($mrModel->unlinkTester(2, 'test')) && p() && e('0');  // 取消关联错误的类型