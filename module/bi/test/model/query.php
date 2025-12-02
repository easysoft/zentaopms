#!/usr/bin/env php
<?php

/**

title=测试 biModel::query();
timeout=0
cid=15211

- 步骤1：正常SQL查询 @0
- 步骤2：空SQL查询 @1
- 步骤3：无效SQL查询 @1
- 步骤4：使用mysql驱动 @0
- 步骤5：不使用过滤器 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->password->range('123456{10}');
$user->email->range('admin@test.com,user1@test.com,user2@test.com,user3@test.com,user4@test.com,user5@test.com,user6@test.com,user7@test.com,user8@test.com,user9@test.com');
$user->role->range('admin{1},dev{5},qa{2},pm{2}');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 创建一个模拟的状态对象类
class MockStateObj
{
    public $sql = '';
    public $mode = 'text';
    public $pager = array('recPerPage' => 10, 'pageID' => 1);
    public $queryData = array();
    public $fieldSettings = array();
    public $fieldRelatedObject = array();
    private $error = '';

    public function __construct($sql = 'SELECT 1 as id')
    {
        $this->sql = $sql;
    }

    public function getFilters() { return array(); }
    public function beforeQuerySql() { return true; }
    public function setError($error) { $this->error = $error; return $this; }
    public function isError() { return !empty($this->error); }
    public function setPager($total, $recPerPage, $pageID) { $this->pager['total'] = $total; }
    public function setFieldSettings($settings) { $this->fieldSettings = $settings; }
    public function setFieldRelatedObject($object) { $this->fieldRelatedObject = $object; }
    public function buildQuerySqlCols() { return true; }
    public function getError() { return $this->error; }

    public function __toString() { return 'MockStateObj'; }
}

// 测试参数准备
$validSql = 'SELECT 1 as test_col';
$emptySql = '';
$invalidSql = 'INVALID SQL STATEMENT';
$complexSql = 'SELECT id, account FROM zt_user WHERE id > 0 LIMIT 1';
$countSql = 'SELECT COUNT(*) as total FROM zt_user';

// 测试用例执行
$validStateObj = new MockStateObj($validSql);
r($biTest->queryTest($validStateObj)) && p() && e(0); // 步骤1：正常SQL查询

$emptyStateObj = new MockStateObj($emptySql);
r($biTest->queryTest($emptyStateObj)) && p() && e(1); // 步骤2：空SQL查询

$invalidStateObj = new MockStateObj($invalidSql);
r($biTest->queryTest($invalidStateObj)) && p() && e(1); // 步骤3：无效SQL查询

$mysqlStateObj = new MockStateObj($complexSql);
r($biTest->queryTest($mysqlStateObj, 'mysql')) && p() && e(0); // 步骤4：使用mysql驱动

$noFilterStateObj = new MockStateObj($countSql);
r($biTest->queryTest($noFilterStateObj, 'mysql', false)) && p() && e(0); // 步骤5：不使用过滤器