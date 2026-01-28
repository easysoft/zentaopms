#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileTao::updateJobLastSyncDate();
timeout=0
cid=15759

- 执行compile模块的updateJobLastSyncDateTest方法，参数是1, $now  @2025-01-15 10:30:00
- 执行compile模块的updateJobLastSyncDateTest方法，参数是2, $futureDate  @2025-12-31 23:59:59
- 执行compile模块的updateJobLastSyncDateTest方法，参数是3, $pastDate  @2024-01-01 00:00:00
- 执行compile模块的updateJobLastSyncDateTest方法，参数是999, $now  @0
- 执行compile模块的updateJobLastSyncDateTest方法，参数是1, '0000-00-00 00:00:00'  @0000-00-00 00:00:00

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 准备测试数据：从YAML文件加载job表配置并生成测试数据
zendata('job')->loadYaml('job_updatejoblastsyncdate', false, 2)->gen(3);

su('admin');

$compile = new compileTaoTest();

// 测试步骤1：更新存在的job的lastSyncDate为当前时间
$now = '2025-01-15 10:30:00';
r($compile->updateJobLastSyncDateTest(1, $now)) && p() && e('2025-01-15 10:30:00');

// 测试步骤2：更新存在的job的lastSyncDate为未来时间
$futureDate = '2025-12-31 23:59:59';
r($compile->updateJobLastSyncDateTest(2, $futureDate)) && p() && e('2025-12-31 23:59:59');

// 测试步骤3：更新存在的job的lastSyncDate为历史时间
$pastDate = '2024-01-01 00:00:00';
r($compile->updateJobLastSyncDateTest(3, $pastDate)) && p() && e('2024-01-01 00:00:00');

// 测试步骤4：更新不存在的job的lastSyncDate
r($compile->updateJobLastSyncDateTest(999, $now)) && p() && e('0');

// 测试步骤5：更新job的lastSyncDate为NULL值
r($compile->updateJobLastSyncDateTest(1, '0000-00-00 00:00:00')) && p() && e('0000-00-00 00:00:00');