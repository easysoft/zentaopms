#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->batchUpdate();
cid=1
pid=1

当product=1时,传入正常参数批量修改id=1,2,3的计划,打印id=1修改后的名字new >> list-1
当product=1时,传入正常参数批量修改id=1,2,3的计划,打印id=2修改后的名字new >> list-2
当product=1时,传入正常参数批量修改id=1,2,3的计划,打印id=3修改后的名字new >> list-3
当product=2时,传入正常参数批量修改id=4,5,6的计划,打印id=4修改后的名字new >> list-4
当product=2时,传入正常参数批量修改id=4,5,6的计划,打印id=5修改后的名字new >> list-5
当product=2时,传入正常参数批量修改id=4,5,6的计划,打印id=6修改后的名字new >> list-6

*/
$plan = new productPlan('admin');

$product = array();
$product[0] = 1;
$product[1] = 2;

$id = array();
$title = array();
$begin = array();
$end   = array();

$id1 = $id;
$id1[1] = 1;
$id1[2] = 2;
$id1[3] = 3;

$title1 = $title;
$title1[1] = 'list-1';
$title1[2] = 'list-2';
$title1[3] = 'list-3';

$begin1 = $begin;
$begin1[1] = '2021-05-10';
$begin1[2] = '2021-04-12';
$begin1[3] = '2021-04-01';

$end1   = $end;
$end1[1]   = '2022-11-30';
$end1[2]   = '2022-10-25';
$end1[3]   = '2022-10-23';

$id2    = $id;
$id2[4]    = 4;
$id2[5]    = 5;
$id2[6]    = 6;

$title2 = $title;
$title2[6] = 'list-6';
$title2[5] = 'list-5';
$title2[4] = 'list-4';

$begin2 = $begin;
$begin2[6] = '2021-05-10';
$begin2[5] = '2021-06-12';
$begin2[4] = '2021-06-01';

$end2   = $end;
$end2[6]   = '2022-11-30';
$end2[5]   = '2022-11-28';
$end2[4]   = '2022-11-23';

$branch1 = array();
$branch1['id']     = $id1;
$branch1['title']  = $title1;
$branch1['begin']  = $begin1;
$branch1['end']    = $end1;

$branch2 = array();
$branch2['id']     = $id2;
$branch2['title']  = $title2;
$branch2['begin']  = $begin2;
$branch2['end']    = $end2;

$batchUpdate = $plan->batchUpdate($product[0], $branch1);

$batchUpdate1 = $batchUpdate[1];
$batchUpdate2 = $batchUpdate[2];
$batchUpdate3 = $batchUpdate[3];

$newBatchUpdate = $plan->batchUpdate($product[1], $branch2);
$batchUpdate4 = $newBatchUpdate[4];
$batchUpdate5 = $newBatchUpdate[5];
$batchUpdate6 = $newBatchUpdate[6];

r($batchUpdate1) && p('0:new') && e('list-1'); //当product=1时,传入正常参数批量修改id=1,2,3的计划,打印id=1修改后的名字new
r($batchUpdate2) && p('0:new') && e('list-2'); //当product=1时,传入正常参数批量修改id=1,2,3的计划,打印id=2修改后的名字new
r($batchUpdate3) && p('0:new') && e('list-3'); //当product=1时,传入正常参数批量修改id=1,2,3的计划,打印id=3修改后的名字new
r($batchUpdate4) && p('0:new') && e('list-4'); //当product=2时,传入正常参数批量修改id=4,5,6的计划,打印id=4修改后的名字new
r($batchUpdate5) && p('0:new') && e('list-5'); //当product=2时,传入正常参数批量修改id=4,5,6的计划,打印id=5修改后的名字new
r($batchUpdate6) && p('0:new') && e('list-6'); //当product=2时,传入正常参数批量修改id=4,5,6的计划,打印id=6修改后的名字new
?>
