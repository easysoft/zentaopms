#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';
/**
 *
 * title = 测试 groupModel->getPrivInfo();
 *
 * cid=1
 * pid=1
 *
 */

$group = new groupTest();


r($group->getPrivInfoTest(3324,'zh-cn')) && p('priv') && e("这是获取到的一条权限");

//测试获取 id为3324的权限信息
