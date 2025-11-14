#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->loadYaml('type')->gen(12);

/**

title=bugModel->getDataOfBugsPerType();
timeout=0
cid=15373

- 获取类型为codeerror的数据
 - 第codeerror条的name属性 @代码错误
 - 第codeerror条的value属性 @3

- 获取类型为config的数据
 - 第config条的name属性 @配置相关
 - 第config条的value属性 @2

- 获取类型为install的数据
 - 第install条的name属性 @安装部署
 - 第install条的value属性 @1

- 获取类型为security的数据
 - 第security条的name属性 @安全相关
 - 第security条的value属性 @1

- 获取类型为performance的数据
 - 第performance条的name属性 @性能问题
 - 第performance条的value属性 @1

- 获取类型为standard的数据
 - 第standard条的name属性 @标准规范
 - 第standard条的value属性 @1

- 获取类型为automation的数据
 - 第automation条的name属性 @测试脚本
 - 第automation条的value属性 @1

- 获取类型为designdefect的数据
 - 第designdefect条的name属性 @设计缺陷
 - 第designdefect条的value属性 @1

- 获取类型为others的数据
 - 第others条的name属性 @其他
 - 第others条的value属性 @1

*/

$bug = new bugTest();
r($bug->getDataOfBugsPerTypeTest()) && p('codeerror:name,value')    && e('代码错误,3'); //获取类型为codeerror的数据
r($bug->getDataOfBugsPerTypeTest()) && p('config:name,value')       && e('配置相关,2'); //获取类型为config的数据
r($bug->getDataOfBugsPerTypeTest()) && p('install:name,value')      && e('安装部署,1'); //获取类型为install的数据
r($bug->getDataOfBugsPerTypeTest()) && p('security:name,value')     && e('安全相关,1'); //获取类型为security的数据
r($bug->getDataOfBugsPerTypeTest()) && p('performance:name,value')  && e('性能问题,1'); //获取类型为performance的数据
r($bug->getDataOfBugsPerTypeTest()) && p('standard:name,value')     && e('标准规范,1'); //获取类型为standard的数据
r($bug->getDataOfBugsPerTypeTest()) && p('automation:name,value')   && e('测试脚本,1'); //获取类型为automation的数据
r($bug->getDataOfBugsPerTypeTest()) && p('designdefect:name,value') && e('设计缺陷,1'); //获取类型为designdefect的数据
r($bug->getDataOfBugsPerTypeTest()) && p('others:name,value')       && e('其他,1');     //获取类型为others的数据