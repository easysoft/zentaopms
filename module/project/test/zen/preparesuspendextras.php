#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareSuspendExtras();
timeout=0
cid=0

- 执行projectzenTest模块的prepareSuspendExtrasTest方法，参数是1, $testPostData1
 - 属性id @1
 - 属性status @suspended
 - 属性lastEditedBy @admin
- 执行projectzenTest模块的prepareSuspendExtrasTest方法，参数是999, $testPostData2
 - 属性id @999
 - 属性status @suspended
- 执行projectzenTest模块的prepareSuspendExtrasTest方法，参数是2, $emptyPostData
 - 属性id @2
 - 属性status @suspended
 - 属性suspendedDate @2025-09-16
- 执行projectzenTest模块的prepareSuspendExtrasTest方法，参数是3, $testPostData3
 - 属性id @3
 - 属性status @suspended
 - 属性lastEditedBy @admin
- 执行projectzenTest模块的prepareSuspendExtrasTest方法，参数是0, $testPostData1
 - 属性id @0
 - 属性status @suspended

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 定义测试需要的常量
if(!defined('DT_DATETIME1')) define('DT_DATETIME1', 'Y-m-d H:i:s');
if(!defined('DT_DATE1')) define('DT_DATE1', 'Y-m-d');

zenData('project');

su('admin');

$projectzenTest = new projectzenTest();

// 创建测试用的postData对象
class testPostData {
    public function add($key, $value) {
        $this->{$key} = $value;
        return $this;
    }

    public function setDefault($key, $value) {
        if(!property_exists($this, $key) || !isset($this->{$key})) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public function stripTags($fields, $allowedTags) {
        return $this;
    }

    public function get() {
        return $this;
    }
}

$testPostData1 = new testPostData();
$testPostData1->comment = 'Test suspension comment';

$testPostData2 = new testPostData();

$testPostData3 = new testPostData();
$testPostData3->customField = 'custom value';
$testPostData3->desc = 'Custom description';

$emptyPostData = new testPostData();

r($projectzenTest->prepareSuspendExtrasTest(1, $testPostData1)) && p('id,status,lastEditedBy') && e('1,suspended,admin');
r($projectzenTest->prepareSuspendExtrasTest(999, $testPostData2)) && p('id,status') && e('999,suspended');
r($projectzenTest->prepareSuspendExtrasTest(2, $emptyPostData)) && p('id,status,suspendedDate') && e('2,suspended,2025-09-16');
r($projectzenTest->prepareSuspendExtrasTest(3, $testPostData3)) && p('id,status,lastEditedBy') && e('3,suspended,admin');
r($projectzenTest->prepareSuspendExtrasTest(0, $testPostData1)) && p('id,status') && e('0,suspended');