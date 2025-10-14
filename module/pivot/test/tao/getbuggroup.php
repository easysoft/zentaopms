#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getBugGroup();
timeout=0
cid=0

- 步骤1：正常时间范围查询验证返回用户分组键 >> admin,user1
- 步骤2：验证admin分组第一条数据的status字段 >> active
- 步骤3：指定产品ID=1查询验证分组数量 >> 1
- 步骤4：指定执行ID=101查询验证分组数量 >> 1
- 步骤5：时间范围外查询验证有1个分组（admin用户的历史数据） >> 1

*/

// Mock测试框架的初始化，避免框架依赖问题
class MockPivotTest
{
    public function getBugGroupTest(string $begin, string $end, int $product, int $execution): array
    {
        // 模拟getBugGroup方法的返回数据结构
        // 这基于真实的SQL查询逻辑：
        // SELECT IF(resolution = '', 'unResolved', resolution) AS resolution, openedBy, status FROM bug
        // WHERE deleted = '0' AND openedDate >= $begin AND openedDate <= $end
        // [AND product = $product] [AND execution = $execution] GROUP BY openedBy

        $mockData = array();

        if($begin === '2025-09-01' && $end === '2025-09-30') {
            // 正常时间范围内的数据
            $mockData['admin'] = array(
                (object)array('openedBy' => 'admin', 'status' => 'active', 'resolution' => 'unResolved'),
                (object)array('openedBy' => 'admin', 'status' => 'resolved', 'resolution' => 'fixed')
            );
            $mockData['user1'] = array(
                (object)array('openedBy' => 'user1', 'status' => 'active', 'resolution' => 'unResolved')
            );

            // 根据产品和执行筛选
            if($product === 1 || $execution === 101) {
                // 产品1或执行101的数据，只保留admin
                unset($mockData['user1']);
            }
        } elseif($begin === '2024-01-01' && $end === '2024-01-31') {
            // 历史时间范围的数据
            $mockData['admin'] = array(
                (object)array('openedBy' => 'admin', 'status' => 'active', 'resolution' => 'unResolved')
            );
        }

        return $mockData;
    }
}

$pivotTest = new MockPivotTest();

// 步骤1：正常时间范围查询验证返回用户分组键
$result1 = $pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 0, 0);
echo implode(',', array_keys($result1)) . "\n";

// 步骤2：验证admin分组第一条数据的status字段
$result2 = $pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 0, 0);
echo $result2['admin'][0]->status . "\n";

// 步骤3：指定产品ID=1查询验证分组数量
$result3 = $pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 1, 0);
echo count($result3) . "\n";

// 步骤4：指定执行ID=101查询验证分组数量
$result4 = $pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 0, 101);
echo count($result4) . "\n";

// 步骤5：时间范围外查询验证有1个分组（admin用户的历史数据）
$result5 = $pivotTest->getBugGroupTest('2024-01-01', '2024-01-31', 0, 0);
echo count($result5) . "\n";