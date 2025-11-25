#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getByID();
timeout=0
cid=17079

- 步骤1：正常获取存在的度量项属性name @按系统统计的所有层级的项目集总数
- 步骤2：验证最小有效ID返回正确的对象属性id @1
- 步骤3：ID为0应返回false @0
- 步骤4：不存在的大ID值应返回false @0
- 步骤5：指定字段列表时name字段不存在属性name @~~
- 步骤6：数组格式字段列表，code字段不存在属性code @~~
- 步骤7：负数ID应返回false @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$metricTest = new metricTest();

// 4. 执行7个测试步骤
r($metricTest->getByID(1)) && p('name') && e('按系统统计的所有层级的项目集总数');                    // 步骤1：正常获取存在的度量项
r($metricTest->getByID(1)) && p('id') && e('1');                                                    // 步骤2：验证最小有效ID返回正确的对象
r($metricTest->getByID(0)) && p() && e('0');                                                        // 步骤3：ID为0应返回false
r($metricTest->getByID(999999)) && p() && e('0');                                                  // 步骤4：不存在的大ID值应返回false
r($metricTest->getByID(1, 'id,code')) && p('name') && e('~~');                                     // 步骤5：指定字段列表时name字段不存在
r($metricTest->getByID(1, array('id', 'name'))) && p('code') && e('~~');                          // 步骤6：数组格式字段列表，code字段不存在
r($metricTest->getByID(-1)) && p() && e('0');                                                      // 步骤7：负数ID应返回false