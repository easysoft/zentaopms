#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('bug')->loadYaml('module')->gen(10);
zenData('module')->loadYaml('grade')->gen(10);

/**

title=bugModel->getDataOfBugsPerModule();
timeout=0
cid=15368

- 获取未设置模块的数据
 - 第0条的name属性 @/
 - 第0条的value属性 @2

- 获取一级模块也是父模块module1数据
 - 第1条的name属性 @/这是一个模块1
 - 第1条的value属性 @2

- 获取一级模块不是父模块module2数据
 - 第2条的name属性 @/这是一个模块2
 - 第2条的value属性 @2

- 获取二级模块module6数据
 - 第6条的name属性 @/这是一个模块1/这是一个模块6
 - 第6条的value属性 @1

*/

$bug = new bugModelTest();
r($bug->getDataOfBugsPerModuleTest()) && p('0:name,value')    && e('/,2');                         // 获取未设置模块的数据
r($bug->getDataOfBugsPerModuleTest()) && p('1:name,value') && e('/这是一个模块1,2');               // 获取一级模块也是父模块module1数据
r($bug->getDataOfBugsPerModuleTest()) && p('2:name,value') && e('/这是一个模块2,2');               // 获取一级模块不是父模块module2数据
r($bug->getDataOfBugsPerModuleTest()) && p('6:name,value') && e('/这是一个模块1/这是一个模块6,1'); // 获取二级模块module6数据