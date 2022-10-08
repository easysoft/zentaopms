#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->getPairs();
cid=1
pid=1

测试获取文件 1 2 3 4 5 的 标题 >> 这是一个文件名称1.txt,这是一个文件名称2.doc,这是一个文件名称3.docx,这是一个文件名称4.dot,这是一个文件名称5.wps
测试获取文件 6 7 8 9 10 的 标题 >> 这是一个文件名称6.wri,这是一个文件名称7.pdf,这是一个文件名称8.ppt,这是一个文件名称9.pptx,这是一个文件名称10.xls
测试获取文件 11 12 13 14 15 的 标题 >> 这是一个文件名称11.xlsx,这是一个文件名称12.ett,这是一个文件名称13.xlt,这是一个文件名称14.xlsm,这是一个文件名称15.csv
测试获取文件 16 17 18 19 20 的 标题 >> 这是一个文件名称16.jpg,这是一个文件名称17.jpeg,这是一个文件名称18.png,这是一个文件名称19.psd,这是一个文件名称20.gif
测试获取文件 21 22 23 24 25 的 标题 >> 这是一个文件名称21.ico,这是一个文件名称22.bmp,这是一个文件名称23.swf,这是一个文件名称24.avi,这是一个文件名称25.rmvb
测试获取文件 1 2 3 4 5 的 扩展名 >> txt,doc,docx,dot,wps
测试获取文件 6 7 8 9 10 的 扩展名 >> wri,pdf,ppt,pptx,xls
测试获取文件 11 12 13 14 15 的 扩展名 >> xlsx,ett,xlt,xlsm,csv
测试获取文件 16 17 18 19 20 的 扩展名 >> jpg,jpeg,png,psd,gif
测试获取文件 21 22 23 24 25 的 扩展名 >> ico,bmp,swf,avi,rmvb

*/
$fileIDs = array('1,2,3,4,5', '6,7,8,9,10', '11,12,13,14,15', '16,17,18,19,20', '21,22,23,24,25');
$titles  = array('title', 'extension');

$file = new fileTest();

r($file->getPairsTest($fileIDs[0], $titles[0])) && p('1,2,3,4,5')      && e('这是一个文件名称1.txt,这是一个文件名称2.doc,这是一个文件名称3.docx,这是一个文件名称4.dot,这是一个文件名称5.wps');       // 测试获取文件 1 2 3 4 5 的 标题
r($file->getPairsTest($fileIDs[1], $titles[0])) && p('6,7,8,9,10')     && e('这是一个文件名称6.wri,这是一个文件名称7.pdf,这是一个文件名称8.ppt,这是一个文件名称9.pptx,这是一个文件名称10.xls');      // 测试获取文件 6 7 8 9 10 的 标题
r($file->getPairsTest($fileIDs[2], $titles[0])) && p('11,12,13,14,15') && e('这是一个文件名称11.xlsx,这是一个文件名称12.ett,这是一个文件名称13.xlt,这是一个文件名称14.xlsm,这是一个文件名称15.csv'); // 测试获取文件 11 12 13 14 15 的 标题
r($file->getPairsTest($fileIDs[3], $titles[0])) && p('16,17,18,19,20') && e('这是一个文件名称16.jpg,这是一个文件名称17.jpeg,这是一个文件名称18.png,这是一个文件名称19.psd,这是一个文件名称20.gif');  // 测试获取文件 16 17 18 19 20 的 标题
r($file->getPairsTest($fileIDs[4], $titles[0])) && p('21,22,23,24,25') && e('这是一个文件名称21.ico,这是一个文件名称22.bmp,这是一个文件名称23.swf,这是一个文件名称24.avi,这是一个文件名称25.rmvb');  // 测试获取文件 21 22 23 24 25 的 标题
r($file->getPairsTest($fileIDs[0], $titles[1])) && p('1,2,3,4,5')      && e('txt,doc,docx,dot,wps');                                                                                                 // 测试获取文件 1 2 3 4 5 的 扩展名
r($file->getPairsTest($fileIDs[1], $titles[1])) && p('6,7,8,9,10')     && e('wri,pdf,ppt,pptx,xls');                                                                                                 // 测试获取文件 6 7 8 9 10 的 扩展名
r($file->getPairsTest($fileIDs[2], $titles[1])) && p('11,12,13,14,15') && e('xlsx,ett,xlt,xlsm,csv');                                                                                                // 测试获取文件 11 12 13 14 15 的 扩展名
r($file->getPairsTest($fileIDs[3], $titles[1])) && p('16,17,18,19,20') && e('jpg,jpeg,png,psd,gif');                                                                                                 // 测试获取文件 16 17 18 19 20 的 扩展名
r($file->getPairsTest($fileIDs[4], $titles[1])) && p('21,22,23,24,25') && e('ico,bmp,swf,avi,rmvb');                                                                                                 // 测试获取文件 21 22 23 24 25 的 扩展名