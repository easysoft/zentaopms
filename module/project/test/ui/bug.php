#!/usr/bin/env php
<?php

/**

title=项目下bug列表操作检查
timeout=0
cid=1

- 执行tester模块的checkTab方法，参数是'allTab', '10'
 - 最终测试状态 @SUCCESS
 - 测试结果 @allTab下显示条数正确
- 执行tester模块的checkTab方法，参数是'unresolvedTab', '4'
 - 最终测试状态 @SUCCESS
 - 测试结果 @unresolvedTab下显示条数正确
- 执行tester模块的switchProduct方法，参数是'firstProduct', '5'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换firstProduct查看数据成功
- 执行tester模块的switchProduct方法，参数是'secondProduct', '5'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换secondProduct查看数据成功
