#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('user')->gen(1);
su('admin');

/**

title=bugModel->processImageForBatchCreate();
cid=1
pid=1

*/

$uploadImage = array();
$uploadImage[] = '0';
$uploadImage[] = '1';
$uploadImage[] = '2';
$uploadImage[] = '3';

$jpgFile = array();
$jpgFile['realpath']  = $tester->app->tmpRoot . '1.jpg';
$jpgFile['pathname']  = '1.jpg';
$jpgFile['title']     = '1';
$jpgFile['extension'] = 'jpg';

$jpegFile = array();
$jpegFile['realpath']  = $tester->app->tmpRoot . '2.jpeg';
$jpegFile['pathname']  = '2.jpeg';
$jpegFile['title']     = '2';
$jpegFile['extension'] = 'jpeg';

$pngFile = array();
$pngFile['realpath']  = $tester->app->tmpRoot . '3.png';
$pngFile['pathname']  = '3.png';
$pngFile['title']     = '3';
$pngFile['extension'] = 'png';

$gifFile = array();
$gifFile['realpath']  = $tester->app->tmpRoot . '4.gif';
$gifFile['pathname']  = '4.gif';
$gifFile['title']     = '4';
$gifFile['extension'] = 'gif';

$textFile = array();
$textFile['realpath']  = $tester->app->tmpRoot . 'text.txt';
$textFile['pathname']  = 'text.txt';
$textFile['title']     = 'text';
$textFile['extension'] = 'txt';

$bugImagesFiles  = array('jpg' => $jpgFile, 'jpeg' => $jpegFile, 'png' => $pngFile, 'gif' => $gifFile, 'text' => $textFile);
$uploadImageList = array('jpg', 'jpeg', 'png', 'gif', 'text');

foreach($bugImagesFiles as $bugImagesFile)
{
    if(!empty($bugImagesFile['realpath']) and !is_file($bugImagesFile['realpath']))
    {
        $theFile = fopen($bugImagesFile['realpath'], 'w');
        fclose($theFile);
    }
}

$bug1 = new stdclass();
$bug1->steps = '';

$bug2 = new stdclass();
$bug2->steps = '';

$bug3 = new stdclass();
$bug3->steps = '';

$bug4 = new stdclass();
$bug4->steps = '';

$bug5 = new stdclass();
$bug5->steps = '';

$bug6 = new stdclass();
$bug6->steps = '';

$bug = new bugTest();

r($bug->processImageForBatchCreateTest($bug1, $uploadImageList[0], $bugImagesFiles)) && p('pathname,title,extension,addedBy') && e('1.jpg,1,jpg,admin'); // 测试处理 jpg 类型的图片
r($bug->processImageForBatchCreateTest($bug2, $uploadImageList[1], $bugImagesFiles)) && p('pathname,title,extension,addedBy') && e('2.jpeg,2,jpeg,admin'); // 测试处理 jpeg 类型的图片
r($bug->processImageForBatchCreateTest($bug3, $uploadImageList[2], $bugImagesFiles)) && p('pathname,title,extension,addedBy') && e('3.png,3,png,admin'); // 测试处理 png 类型的图片
r($bug->processImageForBatchCreateTest($bug4, $uploadImageList[3], $bugImagesFiles)) && p('pathname,title,extension,addedBy') && e('4.gif,4,gif,admin'); // 测试处理 gif 类型的图片
r($bug->processImageForBatchCreateTest($bug6, $uploadImageList[4], $bugImagesFiles)) && p('pathname,title,extension,addedBy') && e('text.txt,text,txt,~~'); // 测试处理 文本类型

$zfile = $tester->app->loadClass('zfile');

foreach($bugImagesFiles as $bugImagesFile)
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
