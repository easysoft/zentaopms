#!/usr/bin/env php
<?php

/**

title=测试 groupZen::buildProjectAdminForm();
timeout=0
cid=16731

- 步骤1：正常完整表单数据处理 @1
- 步骤2：空表单数据处理 @1
- 步骤3：部分字段为空的表单数据 @1
- 步骤4：全选功能测试 @1
- 步骤5：单行多用户管理权限测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';

su('admin');

$groupZenTest = new groupZenTest();

$postData1 = array(
    'members' => array(
        1 => array('user1', 'user2'),
        2 => array('user3')
    ),
    'program' => array(
        1 => array(1, 2),
        2 => array(3)
    ),
    'project' => array(
        1 => array(10, 20),
        2 => array(30)
    ),
    'product' => array(
        1 => array(100, 200),
        2 => array(300)
    ),
    'execution' => array(
        1 => array(1000),
        2 => array(2000)
    ),
    'programAll' => array(),
    'projectAll' => array(),
    'productAll' => array(),
    'executionAll' => array()
);

$postData2 = array();

$postData3 = array(
    'members' => array(
        1 => array('user1'),
        2 => array(),
        3 => array('user3')
    ),
    'program' => array(
        1 => array(1),
        3 => array(3)
    )
);

$postData4 = array(
    'members' => array(
        1 => array('user1'),
        2 => array('user2')
    ),
    'programAll' => array(1 => 'on'),
    'projectAll' => array(2 => 'on'),
    'productAll' => array(),
    'executionAll' => array()
);

$postData5 = array(
    'members' => array(
        1 => array('admin', 'user1', 'user2')
    ),
    'program' => array(
        1 => array(1, 2, 3)
    ),
    'project' => array(
        1 => array(10, 20, 30)
    ),
    'product' => array(
        1 => array(100, 200, 300)
    ),
    'execution' => array(
        1 => array(1000, 2000)
    )
);

$result1 = $groupZenTest->buildProjectAdminFormTest($postData1);
r(isset($result1[1]['accounts'][0]) && $result1[1]['accounts'][0] == 'user1' ? 1 : 0) && p() && e('1'); // 步骤1：正常完整表单数据处理

$result2 = $groupZenTest->buildProjectAdminFormTest($postData2);
r(empty($result2) ? 1 : 0) && p() && e('1'); // 步骤2：空表单数据处理

$result3 = $groupZenTest->buildProjectAdminFormTest($postData3);
r(isset($result3[1]['accounts'][0]) && $result3[1]['accounts'][0] == 'user1' && isset($result3[3]['accounts'][0]) && $result3[3]['accounts'][0] == 'user3' ? 1 : 0) && p() && e('1'); // 步骤3：部分字段为空的表单数据

$result4 = $groupZenTest->buildProjectAdminFormTest($postData4);
r(isset($result4[1]['program'][0]) && $result4[1]['program'][0] == 'all' ? 1 : 0) && p() && e('1'); // 步骤4：全选功能测试

$result5 = $groupZenTest->buildProjectAdminFormTest($postData5);
r(isset($result5[1]['accounts']) && count($result5[1]['accounts']) == 3 ? 1 : 0) && p() && e('1'); // 步骤5：单行多用户管理权限测试