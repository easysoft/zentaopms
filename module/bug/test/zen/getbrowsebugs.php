#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBrowseBugs();
timeout=0
cid=0

- 步骤1:projectID为0时无法获取bugs @0
- 步骤2:projectID为0时无法获取bugs @0
- 步骤3:projectID为0时无法获取bugs @0
- 步骤4:projectID为0时无法获取bugs @0
- 步骤5:获取不存在产品的bugs @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(5);

$bug = zenData('bug');
$bug->id->range('1-15');
$bug->product->range('1{5},2{5},3{5}');
$bug->project->range('0');
$bug->branch->range('0');
$bug->module->range('0{5},10{5},20{5}');
$bug->title->range('Bug标题1,Bug标题2,Bug标题3')->prefix('Bug');
$bug->status->range('active{6},resolved{6},closed{3}');
$bug->openedBy->range('admin');
$bug->assignedTo->range('admin');
$bug->gen(15);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->gen(10);

su('admin');

$bugTest = new bugZenTest();

class mockPager
{
    public $pageID = 1;
    public $recPerPage = 20;
    public $recTotal = 0;
    public $pageTotal = 1;
    public $offset = 0;
    public function setRecTotal($total = 0) { $this->recTotal = $total; }
    public function setPageTotal($total = 1) { $this->pageTotal = $total; }
    public function setPageID($id = 1) { $this->pageID = $id; }
    public function limit() { return ''; }
}

$pager = new mockPager();

r($bugTest->getBrowseBugsTest(1, '0', 'all', array(), 0, 0, 'id_desc', $pager)) && p() && e('0'); // 步骤1:projectID为0时无法获取bugs
r($bugTest->getBrowseBugsTest(1, '0', 'all', array(), 0, 0, 'id_asc', $pager)) && p() && e('0'); // 步骤2:projectID为0时无法获取bugs
r($bugTest->getBrowseBugsTest(1, '0', 'unresolved', array(), 0, 0, 'id_desc', $pager)) && p() && e('0'); // 步骤3:projectID为0时无法获取bugs
r($bugTest->getBrowseBugsTest(2, '0', 'all', array(), 10, 0, 'id_desc', $pager)) && p() && e('0'); // 步骤4:projectID为0时无法获取bugs
r($bugTest->getBrowseBugsTest(999, '0', 'all', array(), 0, 0, 'id_desc', $pager)) && p() && e('0'); // 步骤5:获取不存在产品的bugs