#!/usr/bin/env php
<?php

/**

title=项目下用例列表操作检查
timeout=0
cid=1

- 执行tester模块的checkTab方法，参数是'allTab', '4'
 - 最终测试状态 @SUCCESS
 - 测试结果 @allTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'waitingTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @waitingTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'storyChangedTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @storyChangedTab下显示用例数正确
