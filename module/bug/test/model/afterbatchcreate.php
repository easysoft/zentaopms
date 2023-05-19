#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('user')->gen(1);
zdTable('action')->gen(1);
zdTable('file')->gen(1);
zdTable('product')->gen(10);

zdTable('bug')->config('bug_afterbatchcreate')->gen(20);
zdTable('project')->config('project_afterbatchcreate')->gen(1);
zdTable('kanbanregion')->config('kanbanregion_afterbatchcreate')->gen(1);
zdTable('kanbanlane')->config('kanbanlane_afterbatchcreate')->gen(1);
zdTable('kanbancolumn')->config('kanbancolumn_afterbatchcreate')->gen(9);
zdTable('kanbancell')->config('kanbancell_afterbatchcreate')->gen(9);

su('admin');

/**

title=bugModel->afterBatchCreate();
cid=1
pid=1

*/

$bugIdList   = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16);
$laneIdList  = array(0, 1);
$output      = array(array(), array('columnID' => 1));
$uploadImage = array('', '文件');
$file        = array(false, array('title' => '文件'));

$bug = new bugTest();

r($bug->afterBatchCreateTest($bugIdList[0],  $laneIdList[0], $output[0], $uploadImage[0], $file[0])) && p() && e('action:3,file:,cards:');                                      // 测试进行批量创建bug 1  后的操作 无laneID 无columnID 无uploadImage 无file
r($bug->afterBatchCreateTest($bugIdList[1],  $laneIdList[1], $output[1], $uploadImage[0], $file[0])) && p() && e('action:4,file:,cards:,2,1,3,5,7,9,11,13,15,17,19,');          // 测试进行批量创建bug 2  后的操作 有laneID 无columnID 无uploadImage 无file
r($bug->afterBatchCreateTest($bugIdList[2],  $laneIdList[0], $output[0], $uploadImage[0], $file[0])) && p() && e('action:5,file:,cards:');                                      // 测试进行批量创建bug 3  后的操作 无laneID 有columnID 无uploadImage 无file
r($bug->afterBatchCreateTest($bugIdList[3],  $laneIdList[1], $output[1], $uploadImage[0], $file[0])) && p() && e('action:6,file:,cards:,4,2,1,3,5,7,9,11,13,15,17,19,');        // 测试进行批量创建bug 4  后的操作 有laneID 有columnID 无uploadImage 无file
r($bug->afterBatchCreateTest($bugIdList[4],  $laneIdList[1], $output[1], $uploadImage[1], $file[0])) && p() && e('action:7,file:,cards:,5,4,2,1,3,5,7,9,11,13,15,17,19,');      // 测试进行批量创建bug 5  后的操作 有laneID 有columnID 有uploadImage 无file
r($bug->afterBatchCreateTest($bugIdList[5],  $laneIdList[1], $output[1], $uploadImage[0], $file[1])) && p() && e('action:8,file:,cards:,6,5,4,2,1,3,5,7,9,11,13,15,17,19,');    // 测试进行批量创建bug 6  后的操作 有laneID 有columnID 无uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[6],  $laneIdList[1], $output[1], $uploadImage[1], $file[1])) && p() && e('action:9,file:2,cards:,7,6,5,4,2,1,3,5,7,9,11,13,15,17,19,'); // 测试进行批量创建bug 7  后的操作 有laneID 有columnID 有uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[7],  $laneIdList[1], $output[0], $uploadImage[1], $file[1])) && p() && e('action:10,file:3,cards:');                                    // 测试进行批量创建bug 8  后的操作 有laneID 无columnID 有uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[8],  $laneIdList[0], $output[1], $uploadImage[1], $file[1])) && p() && e('action:11,file:4,cards:');                                    // 测试进行批量创建bug 9  后的操作 无laneID 有columnID 有uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[9],  $laneIdList[1], $output[0], $uploadImage[1], $file[1])) && p() && e('action:12,file:5,cards:');                                    // 测试进行批量创建bug 10 后的操作 无laneID 无columnID 有uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[10], $laneIdList[1], $output[0], $uploadImage[0], $file[1])) && p() && e('action:13,file:,cards:');                                     // 测试进行批量创建bug 11 后的操作 有laneID 无columnID 无uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[11], $laneIdList[0], $output[1], $uploadImage[0], $file[1])) && p() && e('action:14,file:,cards:');                                     // 测试进行批量创建bug 12 后的操作 无laneID 有columnID 无uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[12], $laneIdList[1], $output[0], $uploadImage[0], $file[1])) && p() && e('action:15,file:,cards:');                                     // 测试进行批量创建bug 13 后的操作 无laneID 无columnID 无uploadImage 有file
r($bug->afterBatchCreateTest($bugIdList[13], $laneIdList[1], $output[0], $uploadImage[1], $file[0])) && p() && e('action:16,file:,cards:');                                     // 测试进行批量创建bug 14 后的操作 有laneID 无columnID 有uploadImage 无file
r($bug->afterBatchCreateTest($bugIdList[14], $laneIdList[0], $output[1], $uploadImage[1], $file[0])) && p() && e('action:17,file:,cards:');                                     // 测试进行批量创建bug 15 后的操作 无laneID 有columnID 有uploadImage 无file
r($bug->afterBatchCreateTest($bugIdList[15], $laneIdList[1], $output[0], $uploadImage[1], $file[0])) && p() && e('action:18,file:,cards:');                                     // 测试进行批量创建bug 16 后的操作 无laneID 无columnID 有uploadImage 无file
