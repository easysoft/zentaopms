#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getDiffs();
timeout=0
cid=1

- 使用空的MR @0
- 使用正确的MR第0条的fileName属性 @README.md

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('mr')->config('mr')->gen(1);
su('admin');

$mrModel = $tester->loadModel('mr');
r($mrModel->getDiffs(new stdclass())) && p() && e('0'); //使用空的MR

$MR = $mrModel->fetchByID(1);
r($mrModel->getDiffs($MR)) && p('0:fileName') && e('README.md'); //使用正确的MR