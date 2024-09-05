#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=激活研发需求测试
timeout=0
cid=89
- 激活关闭前是草稿状态的研发需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 激活关闭前是激活状态求的研发需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 激活需求成功，最终测试状态 @success
 */
chdir (__DIR__);
include '../lib/activatestory.ui.class.php';

$product = zenData('product');
