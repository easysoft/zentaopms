#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('module')->config('module_type')->gen(2);

/**

title=bugModel->getModulesForSidebar();
timeout=0
cid=1

- 获取产品1 下的 bug 模块，查看第一个模块的名称是否正确第0条的name属性 @这是一个模块1

- 获取产品1 下的 bug 模块，查看第二个模块的名称是否正确第1条的name属性 @这是一个模块2

*/

global $tester;
$bug = $tester->loadModel('bug');

$productIdList = array(1, 2);
$branch        = array('0');
r($bug->getModulesForSidebar($productIdList[0], $branch[0])) && p('0:name') && e('这是一个模块1'); //获取产品1 下的 bug 模块，查看第一个模块的名称是否正确
r($bug->getModulesForSidebar($productIdList[0], $branch[0])) && p('1:name') && e('这是一个模块2'); //获取产品1 下的 bug 模块，查看第二个模块的名称是否正确