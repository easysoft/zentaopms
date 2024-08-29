#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=变更研发需求测试
timeout=0
cid=80

- 缺少需求名称，变更失败
 -  测试结果 @创建产品表单页提示信息正确
 -  最终测试状态 @SUCCESS
- 使用默认选项变更研发需求 最终测试状态 @SUCCESS
- 创建正常需求后检查创建需求信息是否正确
 - 属性module @story
 - 属性method @view

*/
chdir(__DIR__);
