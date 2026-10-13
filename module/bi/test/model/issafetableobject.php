#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::isSafeTableObject();
timeout=0
cid=0

- 小写字母对象名通过校验 @1
- 字母数字下划线对象名通过校验 @1
- 含路径穿越的对象名被拒绝 @0
- 含斜杠的对象名被拒绝 @0
- 含点的对象名被拒绝 @0
- 空对象名被拒绝 @0
- 含连字符的对象名被拒绝 @0

*/

$testObj = new biModelTest();

r($testObj->isSafeTableObjectTest('bug'))            && p() && e(1); // 小写字母对象名通过校验
r($testObj->isSafeTableObjectTest('ds_1'))           && p() && e(1); // 字母数字下划线对象名通过校验
r($testObj->isSafeTableObjectTest('../bug'))         && p() && e(0); // 含路径穿越的对象名被拒绝
r($testObj->isSafeTableObjectTest('bug/x'))          && p() && e(0); // 含斜杠的对象名被拒绝
r($testObj->isSafeTableObjectTest('bug.php'))        && p() && e(0); // 含点的对象名被拒绝
r($testObj->isSafeTableObjectTest(''))               && p() && e(0); // 空对象名被拒绝
r($testObj->isSafeTableObjectTest('bug-x'))          && p() && e(0); // 含连字符的对象名被拒绝
