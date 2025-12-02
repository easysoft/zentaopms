#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printZentaoDynamicBlock();
timeout=0
cid=15316

- 执行$result1->dynamics @1
- 执行$result2 @1
- 执行$result3->dynamics @1
- 执行$result4) && isset($result4->dynamics @1
- 执行dynamics) && is_array($result5模块的dynamics方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

$blockTest = new blockTest();

su('admin');

// 步骤1：测试网络连接状态检查（无网络情况）
$_SESSION['hasInternet'] = false;
$result1 = $blockTest->printZentaoDynamicBlockTest();
r(is_array($result1->dynamics)) && p() && e('1');

// 步骤2：测试网络连接状态检查（有网络情况）  
$_SESSION['hasInternet'] = true;
$_SESSION['isSlowNetwork'] = null;
$result2 = $blockTest->printZentaoDynamicBlockTest();
r(is_object($result2)) && p() && e('1');

// 步骤3：测试网络慢情况
$_SESSION['hasInternet'] = true;
$_SESSION['isSlowNetwork'] = true;
$result3 = $blockTest->printZentaoDynamicBlockTest();
r(is_array($result3->dynamics)) && p() && e('1');

// 步骤4：测试正常网络情况（模拟获取数据）
$_SESSION['hasInternet'] = true;
$_SESSION['isSlowNetwork'] = false;
$result4 = $blockTest->printZentaoDynamicBlockTest();
r(is_object($result4) && isset($result4->dynamics)) && p() && e('1');

// 步骤5：测试方法返回结构完整性
$_SESSION['hasInternet'] = true;
$result5 = $blockTest->printZentaoDynamicBlockTest();
r(isset($result5->dynamics) && is_array($result5->dynamics)) && p() && e('1');