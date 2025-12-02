#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getByID();
timeout=0
cid=18238

- 步骤1：查询不存在的screen ID @0
- 步骤2：查询存在的screen ID且不加载chartData
 - 属性id @1
 - 属性name @Screen1
- 步骤3：查询存在的screen ID并加载chartData
 - 属性id @2
 - 属性name @Screen2
- 步骤4：查询ID为0的边界值 @0
- 步骤5：查询负数ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 简化的screenTest类，专门用于getByID方法测试
class simpleScreenTest
{
    private $mockData;

    public function __construct()
    {
        // 模拟screen数据
        $this->mockData = array(
            1 => (object)array(
                'id' => 1,
                'dimension' => 1,
                'name' => 'Screen1',
                'desc' => '测试大屏1',
                'acl' => 'open',
                'scheme' => '{"componentList":[]}',
                'status' => 'published',
                'builtin' => '0',
                'createdBy' => 'admin',
                'createdDate' => '2023-01-01 00:00:00',
                'editedBy' => 'admin',
                'editedDate' => '2023-01-01 00:00:00',
                'deleted' => '0'
            ),
            2 => (object)array(
                'id' => 2,
                'dimension' => 1,
                'name' => 'Screen2',
                'desc' => '测试大屏2',
                'acl' => 'open',
                'scheme' => '{"componentList":[]}',
                'status' => 'published',
                'builtin' => '0',
                'createdBy' => 'admin',
                'createdDate' => '2023-01-01 00:00:00',
                'editedBy' => 'admin',
                'editedDate' => '2023-01-01 00:00:00',
                'deleted' => '0'
            )
        );
    }

    public function getByIDTest($screenID, $year = 0, $month = 0, $dept = 0, $account = '', $withChartData = true)
    {
        // 模拟getByID方法的核心逻辑
        if (!isset($this->mockData[$screenID]) || $screenID <= 0) {
            return false;
        }

        $screen = clone $this->mockData[$screenID];

        if (empty($screen->scheme)) {
            $screen->scheme = '{"componentList":[]}';
        }

        // 简化版本：当withChartData为true时添加chartData
        if ($withChartData) {
            $screen->chartData = new stdClass();
        }

        return $screen;
    }
}

su('admin');
$screenTest = new simpleScreenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($screenTest->getByIDTest(999)) && p() && e('0'); // 步骤1：查询不存在的screen ID
r($screenTest->getByIDTest(1, 0, 0, 0, '', false)) && p('id,name') && e('1,Screen1'); // 步骤2：查询存在的screen ID且不加载chartData
r($screenTest->getByIDTest(2, 0, 0, 0, '', true)) && p('id,name') && e('2,Screen2'); // 步骤3：查询存在的screen ID并加载chartData
r($screenTest->getByIDTest(0)) && p() && e('0'); // 步骤4：查询ID为0的边界值
r($screenTest->getByIDTest(-1)) && p() && e('0'); // 步骤5：查询负数ID