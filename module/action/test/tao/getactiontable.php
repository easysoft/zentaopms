#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getActionTable();
timeout=0
cid=14945

- 执行actionTest模块的getActionTableTest方法，参数是'today'  @`zt_actionrecent`
- 执行actionTest模块的getActionTableTest方法，参数是'yesterday'  @`zt_actionrecent`
- 执行actionTest模块的getActionTableTest方法，参数是'thisWeek'  @`zt_actionrecent`
- 执行actionTest模块的getActionTableTest方法，参数是'lastWeek'  @`zt_actionrecent`
- 执行actionTest模块的getActionTableTest方法，参数是'thisMonth'  @`zt_actionrecent`
- 执行actionTest模块的getActionTableTest方法，参数是'lastMonth'  @`zt_action`
- 执行actionTest模块的getActionTableTest方法，参数是'all'  @`zt_action`
- 执行actionTest模块的getActionTableTest方法，参数是''  @`zt_action`
- 执行actionTest模块的getActionTableTest方法，参数是'invalid'  @`zt_action`
- 执行actionTest模块的getActionTableTest方法，参数是'twoMonthsAgo'  @`zt_action`

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

r($actionTest->getActionTableTest('today')) && p() && e('`zt_actionrecent`');
r($actionTest->getActionTableTest('yesterday')) && p() && e('`zt_actionrecent`');
r($actionTest->getActionTableTest('thisWeek')) && p() && e('`zt_actionrecent`');
r($actionTest->getActionTableTest('lastWeek')) && p() && e('`zt_actionrecent`');
r($actionTest->getActionTableTest('thisMonth')) && p() && e('`zt_actionrecent`');
r($actionTest->getActionTableTest('lastMonth')) && p() && e('`zt_action`');
r($actionTest->getActionTableTest('all')) && p() && e('`zt_action`');
r($actionTest->getActionTableTest('')) && p() && e('`zt_action`');
r($actionTest->getActionTableTest('invalid')) && p() && e('`zt_action`');
r($actionTest->getActionTableTest('twoMonthsAgo')) && p() && e('`zt_action`');