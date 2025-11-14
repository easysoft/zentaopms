#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel->isOpenMethod();
timeout=0
cid=15665

- 查看转换拼音后的数组长度 @3
- 查看转换拼音后的数组第一个元素属性测试1 @ceshi1 cs1
- 查看转换拼音后的数组第二个元素属性测试2 @ceshi2 cs2
- 查看转换拼音后的数组第三个元素属性测试3 @ceshi3 cs3
- 查看转换拼音后的数组长度 @3
- 查看转换拼音后的数组第一个元素属性测试拼音1 @ceshipinyin1 cspy1
- 查看转换拼音后的数组第二个元素属性测试拼音2 @ceshipinyin2 cspy2
- 查看转换拼音后的数组第三个元素属性测试拼音3 @ceshipinyin3 cspy3

*/

$items = array('测试1', '测试2', '测试3');
$items = commonModel::convert2Pinyin($items);

r(count($items)) && p()        && e('3');          // 查看转换拼音后的数组长度
r($items)        && p('测试1') && e('ceshi1 cs1'); // 查看转换拼音后的数组第一个元素
r($items)        && p('测试2') && e('ceshi2 cs2'); // 查看转换拼音后的数组第二个元素
r($items)        && p('测试3') && e('ceshi3 cs3'); // 查看转换拼音后的数组第三个元素

$items = array('测试拼音1', '测试拼音2', '测试拼音3');
$items = commonModel::convert2Pinyin($items);

r(count($items)) && p()            && e('3');                  // 查看转换拼音后的数组长度
r($items)        && p('测试拼音1') && e('ceshipinyin1 cspy1'); // 查看转换拼音后的数组第一个元素
r($items)        && p('测试拼音2') && e('ceshipinyin2 cspy2'); // 查看转换拼音后的数组第二个元素
r($items)        && p('测试拼音3') && e('ceshipinyin3 cspy3'); // 查看转换拼音后的数组第三个元素