#!/usr/bin/env php
<?php

include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
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
