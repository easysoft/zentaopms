#!/usr/bin/env php
<?php

/**

title=测试 bugZen::prepareBrowseParams();
timeout=0
cid=15465

- 测试 browseType 为 all 时的参数准备属性moduleID @0
- 测试 browseType 为 all 时的参数准备属性queryID @0
- 测试 browseType 为 bymodule 时的参数准备属性moduleID @5
- 测试 browseType 为 bysearch 时的参数准备属性queryID @10
- 测试 orderBy 添加 id 排序规则 @13
- 测试分页器类型正确属性pagerClass @pager
- 测试分页器 recTotal 参数传递属性recTotal @100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$bugTest = new bugZenTest();

r($bugTest->prepareBrowseParamsTest('all', 0, 'id_desc', 100, 20, 1)) && p('moduleID') && e('0'); // 测试 browseType 为 all 时的参数准备
r($bugTest->prepareBrowseParamsTest('all', 0, 'id_desc', 100, 20, 1)) && p('queryID') && e('0'); // 测试 browseType 为 all 时的参数准备
r($bugTest->prepareBrowseParamsTest('bymodule', 5, 'id_desc', 100, 20, 1)) && p('moduleID') && e('5'); // 测试 browseType 为 bymodule 时的参数准备
r($bugTest->prepareBrowseParamsTest('bysearch', 10, 'id_desc', 100, 20, 1)) && p('queryID') && e('10'); // 测试 browseType 为 bysearch 时的参数准备
r(strlen($bugTest->prepareBrowseParamsTest('all', 0, 'status', 100, 20, 1)['realOrderBy'])) && p() && e('13'); // 测试 orderBy 添加 id 排序规则
r($bugTest->prepareBrowseParamsTest('all', 0, 'id_desc', 100, 20, 1)) && p('pagerClass') && e('pager'); // 测试分页器类型正确
r($bugTest->prepareBrowseParamsTest('all', 0, 'id_desc', 100, 20, 1)) && p('recTotal') && e('100'); // 测试分页器 recTotal 参数传递