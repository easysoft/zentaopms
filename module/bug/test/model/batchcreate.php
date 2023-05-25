#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('user')->gen(1);
zdTable('product')->gen(10);
zdTable('action')->gen(1);

zdTable('project')->config('project_batchcreate')->gen(1);
zdTable('kanbanregion')->config('kanbanregion_batchcreate')->gen(1);
zdTable('kanbanlane')->config('kanbanlane_batchcreate')->gen(1);
zdTable('kanbancolumn')->config('kanbancolumn_batchcreate')->gen(9);
zdTable('kanbancell')->config('kanbancell_batchcreate')->gen(9);

su('admin');

function initImage($imagesFiles)
{
    foreach($imagesFiles as $bugImagesFile)
    {
        if(!empty($bugImagesFile['realpath']) and !is_file($bugImagesFile['realpath']))
        {
            $theFile = fopen($bugImagesFile['realpath'], 'w');
            fclose($theFile);
        }
    }
}

function dropImage($imagesFiles)
{
    foreach($imagesFiles as $bugImagesFile)
    {
        if(!empty($bugImagesFile['realpath']) and is_file($bugImagesFile['realpath']))
        {
            $zfile->removeFile($bugImagesFile['realpath']);
        }
        if(!empty($bugImagesFile['pathname']) and is_file($bugImagesFile['pathname']))
        {
            $zfile->removeFile($bugImagesFile['pathname']);
        }
        if(!empty($bugImagesFile['title']) and is_file($bugImagesFile['title']))
        {
            $zfile->removeFile($bugImagesFile['title']);
        }
    }
}

/**

title=bugModel->batchCreate();
cid=1
pid=1

测试正常批量创建bug1 >> 批量bug一,trunk,3,codeerror,3,3,1,101
测试正常批量创建bug2 >> 批量bug五,trunk,codeerror,3,3,1,101
测试正常批量创建bug3 >> 批量bug九,1,config,3,3,1,0
测试短时间内重复批量创建bug >> 0
测试异常创建bug >> 『影响版本』不能为空。

*/

$productID     = 1;

$title          = array('批量bug一','批量bug二','批量bug三');
$openedBuild    = array(array('3'), array('trunk'), array('1'));
$type           = array('codeerror','notrepro','config');
$severity       = array('3','2','1');
$normal_create1 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity);

$title          = array('批量bug四','批量bug五','批量bug六');
$normal_create2 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity);

$execution     = array(0, 0, 0);
$title          = array('批量bug七','批量bug八','批量bug九');
$normal_create3 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity, 'executions' => $execution);

$laneIdList     = array(1, 2, 3);

$title          = array('批量bug十','批量bug十一','批量bug十二');
$normal_create4 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity, 'laneID' => $laneIdList, 'executions' => $execution);

$title          = array('批量bug十三','批量bug十四','批量bug十五');
$normal_create5 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity, 'laneID' => $laneIdList);

$title            = array('异常一','异常二','异常三');
$emptyBuild      = array(array('trunk'), array(''), array('1'));
$empty_build_create = array('title' => $title, 'openedBuilds' => $emptyBuild);

$emptyTitle         = array('名称','','名称三');
$empty_title_create = array('title' => $emptyTitle, 'openedBuilds' => $openedBuild);

$output = array(array(), array('laneID' => 1, 'columnID' => 1));

$jpgFile = array();
$jpgFile['realpath']  = $tester->app->tmpRoot . '1.jpg';
$jpgFile['pathname']  = '1.jpg';
$jpgFile['title']     = '1';
$jpgFile['extension'] = 'jpg';

$bugImagesFiles  = array(false, array('jpg' => $jpgFile));
$uploadImages    = array(false, array('jpg'));

initImage($bugImagesFiles[1]);

$bug = new bugTest();
r($bug->batchCreateObject($productID, $normal_create1, $output[0], $uploadImages[0], $bugImagesFiles[0])) && p('0:title,openedBuild,type,severity,pri,product,execution') && e('批量bug一,3,codeerror,3,3,1,11'); // 测试正常批量创建bug1
r($bug->batchCreateObject($productID, $normal_create2, $output[1], $uploadImages[0], $bugImagesFiles[0])) && p('1:title,openedBuild,type,severity,pri,product,execution') && e('批量bug五,trunk,notrepro,3,3,1,11');   // 测试正常批量创建bug2 有output
r($bug->batchCreateObject($productID, $normal_create3, $output[0], $uploadImages[1], $bugImagesFiles[1])) && p('2:title,openedBuild,type,severity,pri,product,execution') && e('批量bug九,1,config,3,3,1,0');            // 测试正常批量创建bug3 上传图片
dropImage($bugImagesFiles[1]);
initImage($bugImagesFiles[1]);
r($bug->batchCreateObject($productID, $normal_create4, $output[0], $uploadImages[0], $bugImagesFiles[0])) && p('0:title,openedBuild,type,severity,pri,product,execution') && e('批量bug十,3,codeerror,3,3,1,0');            // 测试正常批量创建bug4 bug有laneID
r($bug->batchCreateObject($productID, $normal_create5, $output[1], $uploadImages[1], $bugImagesFiles[1])) && p('1:title,openedBuild,type,severity,pri,product,execution') && e('批量bug十四,trunk,notrepro,3,3,1,11');            // 测试正常批量创建bug5 有output 上传图片 bug有laneID
dropImage($bugImagesFiles[1]);

r($bug->batchCreateObject($productID, $empty_build_create)) && p('') && e("『影响版本』不能为空。"); // 测试创建没有版本的bug
r($bug->batchCreateObject($productID, $empty_title_create)) && p('') && e("『Bug标题』不能为空。");  // 测试创建没有标题的bug
