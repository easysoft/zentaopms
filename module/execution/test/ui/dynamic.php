#!/usr/bin/env php
<?php

/**
title=执行动态
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/dynamic.ui.class.php';

$user = zenData('user');
$user->id->range('1-100');
$user->account->range('admin, user1, user2, user3, user4, user5');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(6);
