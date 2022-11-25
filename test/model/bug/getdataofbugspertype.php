#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerType();
cid=1
pid=1

获取类型为codeerror的数据 >> 代码错误,49
获取类型为config的数据 >> 配置相关,34
获取类型为install的数据 >> 安装部署,34
获取类型为security的数据 >> 安全相关,33
获取类型为performance的数据 >> 性能问题,33
获取类型为standard的数据 >> 标准规范,33
获取类型为automation的数据 >> 测试脚本,33
获取类型为designdefect的数据 >> 设计缺陷,33
获取类型为others的数据 >> 其他,33

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerTypeTest()) && p('codeerror:name,value')    && e('代码错误,49');   // 获取类型为codeerror的数据
r($bug->getDataOfBugsPerTypeTest()) && p('config:name,value')       && e('配置相关,34');   // 获取类型为config的数据
r($bug->getDataOfBugsPerTypeTest()) && p('install:name,value')      && e('安装部署,34');   // 获取类型为install的数据
r($bug->getDataOfBugsPerTypeTest()) && p('security:name,value')     && e('安全相关,33');   // 获取类型为security的数据
r($bug->getDataOfBugsPerTypeTest()) && p('performance:name,value')  && e('性能问题,33');   // 获取类型为performance的数据
r($bug->getDataOfBugsPerTypeTest()) && p('standard:name,value')     && e('标准规范,33');   // 获取类型为standard的数据
r($bug->getDataOfBugsPerTypeTest()) && p('automation:name,value')   && e('测试脚本,33');   // 获取类型为automation的数据
r($bug->getDataOfBugsPerTypeTest()) && p('designdefect:name,value') && e('设计缺陷,33');   // 获取类型为designdefect的数据
r($bug->getDataOfBugsPerTypeTest()) && p('others:name,value')       && e('其他,33');       // 获取类型为others的数据