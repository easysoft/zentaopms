#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('storygrade');
su('admin');

/**

title=测试 customModel->setConcept();
timeout=0
cid=1

*/

$data['grade'] = array(
    0 => 1,
    1 => 2,
    2 => 3,
    3 => 4,
    4 => 5
);

$data['gradeName'] = array(
    0 => '一级',
    1 => '二级',
    2 => '三级',
    3 => '四级',
    4 => '五级'
);

