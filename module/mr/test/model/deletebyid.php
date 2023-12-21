#!/usr/bin/env php
<?php

/**

title=测试 mrModel::deleteByID();
timeout=0
cid=0

- 没有关联对象的合并请求
 - 第1条的id属性 @1
 - 第1条的objectType属性 @mr
 - 第1条的action属性 @deleted
- 有关联对象的合并请求 @7
- 有关联对象的合并请求
 - 第2条的objectType属性 @mr
 - 第2条的action属性 @deleted
 - 第3条的objectType属性 @story
 - 第3条的action属性 @removemr
 - 第7条的objectType属性 @bug
 - 第7条的action属性 @removemr

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

su('admin');
zdTable('action')->gen(0);
zdTable('relation')->config('relation')->gen(5);
$mr = zdTable('mr')->config('mr');
$mr->synced->range('0');
$mr->gen(3);

$mrModel = new mrTest();

r($mrModel->deleteByIDTester(2)) && p('1:id,objectType,action') && e('1,mr,deleted');   // 没有关联对象的合并请求

$result = $mrModel->deleteByIDTester(1);
r(count($result)) && p() && e('7'); // 有关联对象的合并请求
r($result) && p('2:objectType,action;3:objectType,action;7:objectType,action') && e('mr,deleted,story,removemr,bug,removemr'); // 有关联对象的合并请求