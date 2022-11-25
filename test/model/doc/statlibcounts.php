#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->statLibCounts();
cid=1
pid=1

测试统计libID 1 3 5 的模块文件数量 >> 3;3;3
测试统计libID 2 4 6 的模块文件数量 >> 3;3;3
测试统计libID 7 9 11 的模块文件数量 >> 3;3;4
测试统计libID 8 10 12 的模块文件数量 >> 3;3;4
测试统计libID 13 14 15 的模块文件数量 >> 4;4;4

*/

$libIDList = array(array(1, 3, 5), array(2, 4, 6), array(7, 9, 11), array(8, 10, 12), array(13, 14, 15));

$doc = new docTest();

r($doc->statLibCountsTest($libIDList[0])) && p('1;3;5')    && e('3;3;3'); // 测试统计libID 1 3 5 的模块文件数量
r($doc->statLibCountsTest($libIDList[1])) && p('2;4;6')    && e('3;3;3'); // 测试统计libID 2 4 6 的模块文件数量
r($doc->statLibCountsTest($libIDList[2])) && p('7;9;11')   && e('3;3;4'); // 测试统计libID 7 9 11 的模块文件数量
r($doc->statLibCountsTest($libIDList[3])) && p('8;10;12')  && e('3;3;4'); // 测试统计libID 8 10 12 的模块文件数量
r($doc->statLibCountsTest($libIDList[4])) && p('13;14;15') && e('4;4;4'); // 测试统计libID 13 14 15 的模块文件数量