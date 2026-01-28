#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProductView();
timeout=0
cid=19624

- 执行userTest模块的getProductViewTest方法，参数是'admin', $allProducts, $manageObjects1, $whiteList1  @1,2,3,4,5

- 执行userTest模块的getProductViewTest方法，参数是'user1', $allProducts, $manageObjects2, $whiteList2  @1,4

- 执行userTest模块的getProductViewTest方法，参数是'user1', $allProducts, $manageObjects3, $whiteList3  @1,2,4

- 执行userTest模块的getProductViewTest方法，参数是'user5', $allProducts, $manageObjects4, $whiteList4  @1,4

- 执行userTest模块的getProductViewTest方法，参数是'user2', $allProducts, $manageObjects5, $whiteList5  @1,2,3,4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->loadYaml('user_getproductview', false, 2)->gen(10);

$productTable = zenData('product');
$productTable->loadYaml('product_getproductview', false, 2)->gen(10);

$aclTable = zenData('acl');
$aclTable->loadYaml('acl_getproductview', false, 2)->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试数据
$allProducts = array(
    1 => (object)array('id' => 1, 'name' => '产品1', 'acl' => 'open', 'PO' => 'admin', 'QD' => 'admin', 'RD' => 'admin', 'createdBy' => 'admin', 'reviewer' => '', 'PMT' => '', 'feedback' => '', 'ticket' => '', 'program' => 0),
    2 => (object)array('id' => 2, 'name' => '产品2', 'acl' => 'private', 'PO' => 'admin', 'QD' => 'admin', 'RD' => 'admin', 'createdBy' => 'admin', 'reviewer' => '', 'PMT' => '', 'feedback' => '', 'ticket' => '', 'program' => 0),
    3 => (object)array('id' => 3, 'name' => '产品3', 'acl' => 'custom', 'PO' => 'admin', 'QD' => 'admin', 'RD' => 'admin', 'createdBy' => 'admin', 'reviewer' => '', 'PMT' => '', 'feedback' => '', 'ticket' => '', 'program' => 0),
    4 => (object)array('id' => 4, 'name' => '产品4', 'acl' => 'open', 'PO' => 'admin', 'QD' => 'admin', 'RD' => 'admin', 'createdBy' => 'admin', 'reviewer' => '', 'PMT' => '', 'feedback' => '', 'ticket' => '', 'program' => 0),
    5 => (object)array('id' => 5, 'name' => '产品5', 'acl' => 'private', 'PO' => 'admin', 'QD' => 'admin', 'RD' => 'admin', 'createdBy' => 'admin', 'reviewer' => '', 'PMT' => '', 'feedback' => '', 'ticket' => '', 'program' => 0)
);

// 测试步骤1：管理员账号可以访问所有产品
$manageObjects1 = array('products' => array('isAdmin' => true));
$whiteList1 = array();
r($userTest->getProductViewTest('admin', $allProducts, $manageObjects1, $whiteList1)) && p() && e('1,2,3,4,5');

// 测试步骤2：普通用户访问公开产品
$manageObjects2 = array('products' => array('isAdmin' => false, 'list' => ''));
$whiteList2 = array();
r($userTest->getProductViewTest('user1', $allProducts, $manageObjects2, $whiteList2)) && p() && e('1,4');

// 测试步骤3：用户访问私有产品但在白名单中
$manageObjects3 = array('products' => array('isAdmin' => false, 'list' => ''));
$whiteList3 = array('product' => array(2 => array('user1' => 'user1')));
r($userTest->getProductViewTest('user1', $allProducts, $manageObjects3, $whiteList3)) && p() && e('1,2,4');

// 测试步骤4：用户访问私有产品但不在白名单中
$manageObjects4 = array('products' => array('isAdmin' => false, 'list' => ''));
$whiteList4 = array();
r($userTest->getProductViewTest('user5', $allProducts, $manageObjects4, $whiteList4)) && p() && e('1,4');

// 测试步骤5：用户有产品管理权限
$manageObjects5 = array('products' => array('isAdmin' => false, 'list' => '2,3'));
$whiteList5 = array();
r($userTest->getProductViewTest('user2', $allProducts, $manageObjects5, $whiteList5)) && p() && e('1,2,3,4');