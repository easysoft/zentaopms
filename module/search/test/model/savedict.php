#!/usr/bin/env php
<?php

/**

title=测试 searchModel::saveDict();
timeout=0
cid=0

- 步骤1：保存混合字典时返回成功 @1
- 步骤2：只保存合法的 5 位数字字典项 @2
- 步骤3：合法字典项会写入原始值 @你
- 步骤4：再次保存时会新增新的合法字典项 @1
- 步骤5：重复 key 不会覆盖已保存内容 @你
- 步骤6：非法长度 key 不会被写入 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

su('admin');

$search = new searchModelTest();

$firstSave = $search->saveDictTest(array('20320' => '你', '22909' => '好', '1234' => '短', 'abcde' => 'bad', '-1' => 'neg', '70000' => 'overflow', '30000' => ''));
$dictPairs = $tester->dao->select('`key`,value')->from(TABLE_SEARCHDICT)->orderBy('`key`')->fetchPairs();

$secondSave = $search->saveDictTest(array('20320' => '你2', '21040' => '啊'));
$dictPairsAfter = $tester->dao->select('`key`,value')->from(TABLE_SEARCHDICT)->orderBy('`key`')->fetchPairs();

r($firstSave)                        && p() && e('1'); // 步骤1：保存混合字典时返回成功
r(count($dictPairs))                 && p() && e('2'); // 步骤2：只保存合法的 5 位数字字典项
r($dictPairs[20320])                 && p() && e('你'); // 步骤3：合法字典项会写入原始值
r(isset($dictPairsAfter[21040]))     && p() && e('1'); // 步骤4：再次保存时会新增新的合法字典项
r($dictPairsAfter[20320])            && p() && e('你'); // 步骤5：重复 key 不会覆盖已保存内容
r(isset($dictPairsAfter[1234]))      && p() && e('0'); // 步骤6：非法长度 key 不会被写入
