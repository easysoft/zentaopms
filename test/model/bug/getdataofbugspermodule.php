#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerModule();
cid=1
pid=1

获取module0数据 >> /,306
获取module1821数据 >> /产品模块1,1
获取module1822数据 >> /产品模块2,1
获取module1823数据 >> /产品模块3,1
获取module1825数据 >> /产品模块5,1
获取module1826数据 >> /产品模块6,1
获取module1827数据 >> /产品模块7,1
获取module1831数据 >> /产品模块11,1
获取module1832数据 >> /产品模块12,1
获取module1833数据 >> /产品模块13,1

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerModuleTest()) && p('0:name,value')    && e('/,306');         // 获取module0数据
r($bug->getDataOfBugsPerModuleTest()) && p('1821:name,value') && e('/产品模块1,1');  // 获取module1821数据
r($bug->getDataOfBugsPerModuleTest()) && p('1822:name,value') && e('/产品模块2,1');  // 获取module1822数据
r($bug->getDataOfBugsPerModuleTest()) && p('1823:name,value') && e('/产品模块3,1');  // 获取module1823数据
r($bug->getDataOfBugsPerModuleTest()) && p('1825:name,value') && e('/产品模块5,1');  // 获取module1825数据
r($bug->getDataOfBugsPerModuleTest()) && p('1826:name,value') && e('/产品模块6,1');  // 获取module1826数据
r($bug->getDataOfBugsPerModuleTest()) && p('1827:name,value') && e('/产品模块7,1');  // 获取module1827数据
r($bug->getDataOfBugsPerModuleTest()) && p('1831:name,value') && e('/产品模块11,1'); // 获取module1831数据
r($bug->getDataOfBugsPerModuleTest()) && p('1832:name,value') && e('/产品模块12,1'); // 获取module1832数据
r($bug->getDataOfBugsPerModuleTest()) && p('1833:name,value') && e('/产品模块13,1'); // 获取module1833数据