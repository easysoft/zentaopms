#!/usr/bin/env php
<?php

/**

title=测试 fileModel::getByGid();
timeout=0
cid=16502

- 执行fileTest模块的getByGidTest方法，参数是'test_gid_001' 
 - 属性gid @test_gid_001
 - 属性title @file1
- 执行fileTest模块的getByGidTest方法，参数是'gid_test_1' 属性title @gid_test_1
- 执行fileTest模块的getByGidTest方法，参数是'nonexistent_gid'  @0
- 执行fileTest模块的getByGidTest方法，参数是'special_gid_123' 
 - 属性gid @special_gid_123
 - 属性title @file3
- 执行fileTest模块的getByGidTest方法，参数是'normal_gid_abc' 
 - 属性gid @normal_gid_abc
 - 属性title @file4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 直接插入测试数据
global $tester;
$tester->dao->delete()->from(TABLE_FILE)->exec();

$files = array(
    array(
        'id' => 1,
        'pathname' => 'path1',
        'title' => 'file1',
        'extension' => 'txt',
        'size' => 100,
        'objectType' => 'story',
        'objectID' => 1,
        'gid' => 'test_gid_001',
        'addedBy' => 'admin',
        'addedDate' => '2023-01-01 10:00:00',
        'downloads' => 0,
        'extra' => '',
        'deleted' => '0'
    ),
    array(
        'id' => 2,
        'pathname' => 'path2',
        'title' => 'gid_test_1',
        'extension' => 'txt',
        'size' => 101,
        'objectType' => 'story',
        'objectID' => 2,
        'gid' => '',
        'addedBy' => 'admin',
        'addedDate' => '2023-01-02 10:00:00',
        'downloads' => 1,
        'extra' => '',
        'deleted' => '0'
    ),
    array(
        'id' => 3,
        'pathname' => 'path3',
        'title' => 'file3',
        'extension' => 'txt',
        'size' => 102,
        'objectType' => 'story',
        'objectID' => 3,
        'gid' => 'special_gid_123',
        'addedBy' => 'admin',
        'addedDate' => '2023-01-03 10:00:00',
        'downloads' => 2,
        'extra' => '',
        'deleted' => '0'
    ),
    array(
        'id' => 4,
        'pathname' => 'path4',
        'title' => 'file4',
        'extension' => 'txt',
        'size' => 103,
        'objectType' => 'story',
        'objectID' => 4,
        'gid' => 'normal_gid_abc',
        'addedBy' => 'admin',
        'addedDate' => '2023-01-04 10:00:00',
        'downloads' => 3,
        'extra' => '',
        'deleted' => '0'
    )
);

foreach($files as $file) {
    $tester->dao->insert(TABLE_FILE)->data($file)->exec();
}

su('admin');

$fileTest = new fileModelTest();

r($fileTest->getByGidTest('test_gid_001')) && p('gid,title') && e('test_gid_001,file1');
r($fileTest->getByGidTest('gid_test_1')) && p('title') && e('gid_test_1');
r($fileTest->getByGidTest('nonexistent_gid')) && p() && e('0');
r($fileTest->getByGidTest('special_gid_123')) && p('gid,title') && e('special_gid_123,file3');
r($fileTest->getByGidTest('normal_gid_abc')) && p('gid,title') && e('normal_gid_abc,file4');