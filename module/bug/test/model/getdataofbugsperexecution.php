#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('bug')->loadYaml('execution')->gen(100);
zenData('project')->loadYaml('type')->gen(90);

/**

title=bugModel->getDataOfBugsPerExecution();
timeout=0
cid=15367

- 获取迭代11数据
 - 第11条的name属性 @/PROJECT21
 - 第11条的value属性 @10
 - 第11条的title属性 @/PROJECT21
- 获取迭代12数据
 - 第12条的name属性 @/PROJECT22
 - 第12条的value属性 @9
 - 第12条的title属性 @/PROJECT22
- 获取迭代13数据
 - 第13条的name属性 @/PROJECT23
 - 第13条的value属性 @8
 - 第13条的title属性 @/PROJECT23
- 名称很长的迭代展示
 - 第14条的name属性 @/一个超长的项目名称到底...
 - 第14条的value属性 @7
 - 第14条的title属性 @/一个超长的项目名称到底可以有多长就会加上省略号呢24
- 获取迭代15没有bug数据第15条的name属性 @Error: Cannot get index 15.

*/

$bug = new bugModelTest();
r($bug->getDataOfBugsPerExecutionTest()) && p('11:name,value,title') && e('/PROJECT21,10,/PROJECT21');                                                         // 获取迭代11数据
r($bug->getDataOfBugsPerExecutionTest()) && p('12:name,value,title') && e('/PROJECT22,9,/PROJECT22');                                                          // 获取迭代12数据
r($bug->getDataOfBugsPerExecutionTest()) && p('13:name,value,title') && e('/PROJECT23,8,/PROJECT23');                                                          // 获取迭代13数据
r($bug->getDataOfBugsPerExecutionTest()) && p('14:name,value,title') && e('/一个超长的项目名称到底...,7,/一个超长的项目名称到底可以有多长就会加上省略号呢24'); // 名称很长的迭代展示
r($bug->getDataOfBugsPerExecutionTest()) && p('15:name')             && e('Error: Cannot get index 15.');                                                      // 获取迭代15没有bug数据