#!/usr/bin/env php
<?php

/**

title=项目需求指派和批量指派需求
timeout=0
cid=1

- 单个指派
 - 最终测试状态 @SUCCESS
 - 测试结果 @指派成功
- 批量指派
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量指派成功

*/

chdir(__DIR__);
include '../lib/assign.ui.class.php';
