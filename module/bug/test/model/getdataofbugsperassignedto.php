#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->loadYaml('assignedto')->gen(10);

/**

title=bugModel->getDataOfBugsPerAssignedTo();
timeout=0
cid=15365

- 获取指派给用户 admin 的数据
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @5

- 获取指派给用户 zhangsan 的数据
 - 第zhangsan条的name属性 @zhangsan
 - 第zhangsan条的value属性 @3

- 获取指派给用户 lisi 的数据
 - 第lisi条的name属性 @lisi
 - 第lisi条的value属性 @2

*/

$bug = new bugTest();
r($bug->getDataOfBugsPerAssignedToTest()) && p('admin:name,value')    && e('admin,5');    //获取指派给用户 admin 的数据
r($bug->getDataOfBugsPerAssignedToTest()) && p('zhangsan:name,value') && e('zhangsan,3'); //获取指派给用户 zhangsan 的数据
r($bug->getDataOfBugsPerAssignedToTest()) && p('lisi:name,value')     && e('lisi,2');     //获取指派给用户 lisi 的数据