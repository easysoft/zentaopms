#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('company')->gen(1);
zenData('user')->gen(2);
zenData('config')->gen(0);

su('user1');

/**

title=测试 miscModel->getMetriclibRemind();
timeout=0
cid=1

- 非管理员用户返回空字符串。 @0
- 管理员用户开源版返回空字符串。 @0
- 管理员用户企业版返回正常内容。 @1
- 管理员用户企业版返回正常内容后数据库中记录已显示过。 @1
- 管理员用户企业版返回正常内容一次后第二次返回空字符串。 @0

*/

global $tester, $config;
$config->edition = 'open';

$misc   = $tester->loadModel('misc');
$remind = "<p>新增更新度量库索引功能，更新索引后可大幅度提升相关度量项的查询速度，可以到『后台->系统设置->度量库』页面更新。</p><p class='text-center mt-4'><a href='admin-metriclib.html' id='metriclibButton' class='btn primary wide' data-app='admin'>查看</a></p>";

r($misc->getMetriclibRemind()) && p() && e(0); //非管理员用户返回空字符串。

su('admin');

r($misc->getMetriclibRemind()) && p() && e(0); //管理员用户开源版返回空字符串。

$config->edition = 'biz';
r($misc->getMetriclibRemind() == $remind) && p() && e(1); //管理员用户企业版返回正常内容。

$showed = $tester->loadModel('setting')->getItem('owner=admin&module=common&section=global&key=metriclibShowed');

r($showed) && p() && e(1); //管理员用户企业版返回正常内容后数据库中记录已显示过。

$config->global->metriclibShowed = $showed;

r($misc->getMetriclibRemind()) && p() && e(0); //管理员用户企业版返回正常内容一次后第二次返回空字符串。