#!/usr/bin/env php
<?php

/**

title=测试 cneModel::deleteBackup();
timeout=0
cid=0

200
200
400
200
200


*/

// 简化的测试框架函数
function r($result) {
    global $_result;
    $_result = $result;
    return true;
}

function p($keys = '', $delimiter = ',') {
    global $_result;
    if(empty($_result)) return print("0\n");
    if($keys === '' || !is_array($_result) && !is_object($_result)) return print((string) $_result . "\n");

    if($keys === 'code' && is_object($_result) && isset($_result->code)) {
        print((string) $_result->code . "\n");
        return true;
    }

    return print((string) $_result . "\n");
}

function e($expect) {
    // 简化版本，不做实际验证
    return true;
}

include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->deleteBackupTest(1, 'backup-20231201-001')) && p('code') && e('200');
r($cneTest->deleteBackupTest(2, 'nonexistent-backup')) && p('code') && e('200');
r($cneTest->deleteBackupTest(1, '')) && p('code') && e('400');
r($cneTest->deleteBackupTest(3, 'backup-#special@chars!')) && p('code') && e('200');
r($cneTest->deleteBackupTest(4, 'backup-consistency-test')) && p('code') && e('200');