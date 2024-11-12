#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->getGanttTasks();
cid=0

- 测试查询项目 11 执行 101 browseType 空 queryID 0 的甘特图任务 @1
- 测试查询项目 11 执行 101 browseType 空 queryID 1 的甘特图任务 @1
- 测试查询项目 11 执行 101 browseType bysearch queryID 0 的甘特图任务 @1
- 测试查询项目 11 执行 101 browseType bysearch queryID 1 的甘特图任务 @0
- 测试查询项目 12 执行 102 browseType 空 queryID 0 的甘特图任务 @2
- 测试查询项目 12 执行 102 browseType 空 queryID 1 的甘特图任务 @2
- 测试查询项目 12 执行 102 browseType bysearch queryID 0 的甘特图任务 @0
- 测试查询项目 12 执行 102 browseType bysearch queryID 1 的甘特图任务 @0
- 测试查询不存在的项目 101 执行 101 browseType 空 queryID 0 的甘特图任务 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
