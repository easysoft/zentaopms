#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->loadYaml('status')->gen(10);

/**

title=bugModel->getDataOfBugsPerStatus();
timeout=0
cid=15372

- 获取状态为激活的数据
 - 第active条的name属性 @激活
 - 第active条的value属性 @2

- 获取状态为已解决的数据
 - 第resolved条的name属性 @已解决
 - 第resolved条的value属性 @2

- 取状态为关闭的数据
 - 第closed条的name属性 @已关闭
 - 第closed条的value属性 @6

*/

$bug = new bugTest();
r($bug->getDataOfBugsPerStatusTest()) && p('active:name,value')   && e('激活,2');   //获取状态为激活的数据
r($bug->getDataOfBugsPerStatusTest()) && p('resolved:name,value') && e('已解决,2'); //获取状态为已解决的数据
r($bug->getDataOfBugsPerStatusTest()) && p('closed:name,value')   && e('已关闭,6'); //取状态为关闭的数据