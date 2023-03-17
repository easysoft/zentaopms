#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->fixModulePath();
cid=1
pid=1

修复root为 1 的module path >> ,1821,;,1821,2621,;,2221,2821,
修复root为 2 的module path >> ,1825,;,1825,2623,;,2225,2823,
修复root为 3 的module path >> ,1829,;,1829,2625,;,2229,2825,
修复root为 41 的module path >> ,1981,;,1981,2701,;,2381,2901,
修复root为 42 的module path >> ,1985,;,1985,2703,;,2385,2903,
修复root为 43 的module path >> ,1989,;,1989,2705,;,2389,2905,
修复root为 101 的module path >> ,21,;,22,;,21,3021,
修复root为 102 的module path >> ,24,;,25,;,23,3022
修复root为 103 的module path >> ,27,;,28,;,25,3023

*/
$root = array(1, 2, 3, 41, 42, 43, 101, 102, 103);
$type = array('story', 'task');

$tree = new treeTest();

r($tree->fixModulePathTest($root[0], $type[0])) && p('1821:path;2621:path;2821:path') && e(',1821,;,1821,2621,;,2221,2821,'); // 修复root为 1 的module path
r($tree->fixModulePathTest($root[1], $type[0])) && p('1825:path;2623:path;2823:path') && e(',1825,;,1825,2623,;,2225,2823,'); // 修复root为 2 的module path
r($tree->fixModulePathTest($root[2], $type[0])) && p('1829:path;2625:path;2825:path') && e(',1829,;,1829,2625,;,2229,2825,'); // 修复root为 3 的module path
r($tree->fixModulePathTest($root[3], $type[0])) && p('1981:path;2701:path;2901:path') && e(',1981,;,1981,2701,;,2381,2901,'); // 修复root为 41 的module path
r($tree->fixModulePathTest($root[4], $type[0])) && p('1985:path;2703:path;2903:path') && e(',1985,;,1985,2703,;,2385,2903,'); // 修复root为 42 的module path
r($tree->fixModulePathTest($root[5], $type[0])) && p('1989:path;2705:path;2905:path') && e(',1989,;,1989,2705,;,2389,2905,'); // 修复root为 43 的module path
r($tree->fixModulePathTest($root[6], $type[1])) && p('21:path;22:path;3021:path')     && e(',21,;,22,;,21,3021,');            // 修复root为 101 的module path
r($tree->fixModulePathTest($root[7], $type[1])) && p('24:path;25:path;3022:path')     && e(',24,;,25,;,23,3022');             // 修复root为 102 的module path
r($tree->fixModulePathTest($root[8], $type[1])) && p('27:path;28:path;3023:path')     && e(',27,;,28,;,25,3023');             // 修复root为 103 的module path
