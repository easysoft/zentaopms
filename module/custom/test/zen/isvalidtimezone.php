#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/**

title=测试 customZen::isValidTimezone();
timeout=0
cid=0

- 检查上海时区是否有效 @1
- 检查柏林时区是否有效 @1
- 检查未知时区是否有效 @0
- 检查空时区是否有效 @0
- 检查路径样式时区是否有效 @0

*/

$customZen = new customZenTest();

r($customZen->isValidTimezoneTest('Asia/Shanghai')) && p() && e('1');
r($customZen->isValidTimezoneTest('Europe/Berlin')) && p() && e('1');
r($customZen->isValidTimezoneTest('Unknown/Timezone')) && p() && e('0');
r($customZen->isValidTimezoneTest('')) && p() && e('0');
r($customZen->isValidTimezoneTest('../../etc/passwd')) && p() && e('0');
