#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $product = zenData('product');
    $product->id->range('1-5');
    $product->name->range('Product1,Product2,Product3,Product4,Product5');
    $product->type->range('normal{3},branch{2}');
    $product->status->range('normal');
    $product->gen(5);

    $user = zenData('user');
    $user->id->range('1-5');
    $user->account->range('admin,user1,user2,user3,user4');
    $user->gen(5);

    $module = zenData('module');
    $module->id->range('1-10');
    $module->name->range('Module1,Module2,Module3,Module4,Module5,Module6,Module7,Module8,Module9,Module10');
    $module->type->range('bug');
    $module->root->range('1-5');
    $module->gen(10);
}

/**

title=测试 bugModel::buildSearchForm();
timeout=0
cid=15347

- 执行bugTest模块的buildSearchFormTest方法，参数是1, array 
 - 属性actionURL @/bug-browse-1.html
 - 属性queryID @1
 - 属性hasProductParams @1
- 执行bugTest模块的buildSearchFormTest方法，参数是0, array 
 - 属性actionURL @/bug-browse-all.html
 - 属性queryID @2
 - 属性hasProductParams @1
- 执行bugTest模块的buildSearchFormTest方法，参数是2, array 
 - 属性actionURL @/bug-browse-2.html
 - 属性queryID @3
 - 属性hasModuleParams @1
- 执行bugTest模块的buildSearchFormTest方法，参数是1, array 
 - 属性actionURL @/bug-search.html
 - 属性queryID @0
 - 属性hasProjectParams @1
- 执行bugTest模块的buildSearchFormTest方法，参数是3, array 
 - 属性actionURL @/bug-browse-3.html
 - 属性queryID @5
 - 属性hasProductParams @1

*/

global $tester;
$tester->loadModel('bug');
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

initData();

$bugTest = new bugTest();

r($bugTest->buildSearchFormTest(1, array('1' => 'Product1', '2' => 'Product2'), 1, '/bug-browse-1.html', '0')) && p('actionURL,queryID,hasProductParams') && e('/bug-browse-1.html,1,1');
r($bugTest->buildSearchFormTest(0, array(), 2, '/bug-browse-all.html', '0')) && p('actionURL,queryID,hasProductParams') && e('/bug-browse-all.html,2,1');
r($bugTest->buildSearchFormTest(2, array('2' => 'Product2'), 3, '/bug-browse-2.html', 'all')) && p('actionURL,queryID,hasModuleParams') && e('/bug-browse-2.html,3,1');
r($bugTest->buildSearchFormTest(1, array('1' => 'Product1'), 0, '/bug-search.html', '1')) && p('actionURL,queryID,hasProjectParams') && e('/bug-search.html,0,1');
r($bugTest->buildSearchFormTest(3, array('3' => 'Product3'), 5, '/bug-browse-3.html', '0')) && p('actionURL,queryID,hasProductParams') && e('/bug-browse-3.html,5,1');