#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareSqlPager();
timeout=0
cid=15207

- 步骤1：正常分页MySQL驱动 @SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 0, 10

- 步骤2：第一页分页参数 @SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 0, 20

- 步骤3：非MySQL驱动分页 @SELECT * FROM test_table LIMIT 0, 10

- 步骤4：大页码分页参数 @SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 90, 10

- 步骤5：每页记录数为1的分页 @SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 4, 1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 4. 创建模拟的Statement对象
class MockStatement
{
    public $limit;
    public $options;
    private $sql;

    public function __construct($sql = 'SELECT * FROM test_table')
    {
        $this->sql = $sql;
        $this->options = new stdclass();
        $this->options->options = array();
    }

    public function build()
    {
        $sql = $this->sql;
        
        if(isset($this->limit))
        {
            $sql .= ' LIMIT ' . $this->limit->offset . ', ' . $this->limit->rowCount;
        }
        
        if(!empty($this->options->options) && in_array('SQL_CALC_FOUND_ROWS', $this->options->options))
        {
            $sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
        }
        
        return $sql;
    }
}

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$statement1 = new MockStatement();
r($biTest->prepareSqlPagerTest($statement1, 10, 1, 'mysql')) && p() && e('SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 0, 10'); // 步骤1：正常分页MySQL驱动

$statement2 = new MockStatement();
r($biTest->prepareSqlPagerTest($statement2, 20, 1, 'mysql')) && p() && e('SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 0, 20'); // 步骤2：第一页分页参数

$statement3 = new MockStatement();
r($biTest->prepareSqlPagerTest($statement3, 10, 1, 'sqlite')) && p() && e('SELECT * FROM test_table LIMIT 0, 10'); // 步骤3：非MySQL驱动分页

$statement4 = new MockStatement();
r($biTest->prepareSqlPagerTest($statement4, 10, 10, 'mysql')) && p() && e('SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 90, 10'); // 步骤4：大页码分页参数

$statement5 = new MockStatement();
r($biTest->prepareSqlPagerTest($statement5, 1, 5, 'mysql')) && p() && e('SELECT SQL_CALC_FOUND_ROWS * FROM test_table LIMIT 4, 1'); // 步骤5：每页记录数为1的分页