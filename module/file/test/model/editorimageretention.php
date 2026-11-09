#!/usr/bin/env php
<?php
/**

title=测试编辑器图片保存、关联与清理链路。

- 正文及验收标准引用的图片记录均保留。 @4
- 图片全部关联到需求，且具有编辑器标记。 @4
- 引用中的图片文件均保留，未使用的上传文件已清理。 @1
- 内部引用可重新渲染为图片地址。 @1
- 其他编辑会话不受清理影响。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
global $tester, $config;

zenData('file')->gen(5);
$file = $tester->loadModel('file');
$originalPath = $file->savePath;
$originalType = $config->requestType;
$originalRoot = $config->webRoot;
$originalHost = $_SERVER['HTTP_HOST'] ?? null;
$directory = sys_get_temp_dir() . '/editor-retention-' . bin2hex(random_bytes(6)) . '/';
mkdir($directory, 0700);
$file->savePath = $directory;
$config->requestType = 'PATH_INFO';
$config->webRoot = '/zentao/';
$_SERVER['HTTP_HOST'] = 'retention.example';
$uid = 'image-retention';
$_SESSION['album'][$uid] = array(1, 2, 3, 4, 5);
$_SESSION['album']['other-editor'] = array(6);
try
{
    for($id = 1; $id <= 5; $id++)
    {
        $tester->dao->update(TABLE_FILE)->set('pathname')->eq("image$id.png")->set('objectType')->eq('')->set('objectID')->eq(0)->where('id')->eq($id)->exec();
        file_put_contents($directory . "image$id", 'original image bytes');
    }
    $data = new stdclass();
    $data->spec = "<img src=\"https://retention.example/zentao/file-read-1.png\"\nalt=\"newline\"><img src=\"/zentao/file-read-2.png\"\talt=\"tab\">";
    $data->verify = "<img alt='example' src='https://retention.example/zentao/file-read-3.png'/><img src=\"{4.png}\" />";
    $file->processImgURL($data, 'spec,verify', $uid);
    $file->updateObjectID($uid, 1001, 'story');
    $file->processImgURL((object)array('comment' => ''), 'comment', $uid);
    $file->autoDelete($uid);

    r($tester->dao->select('COUNT(*) AS count')->from(TABLE_FILE)->where('id')->in(array(1, 2, 3, 4))->fetch('count')) && p() && e('4');
    r($tester->dao->select('COUNT(*) AS count')->from(TABLE_FILE)->where('objectID')->eq(1001)->andWhere('objectType')->eq('story')->andWhere('extra')->eq('editor')->fetch('count')) && p() && e('4');
    r(is_file($directory . 'image1') && is_file($directory . 'image2') && is_file($directory . 'image3') && is_file($directory . 'image4') && !is_file($directory . 'image5') && !$file->getById(5)) && p() && e('1');
    r(substr_count($file->setImgSize($data->spec . $data->verify), '/zentao/file-read-') === 4) && p() && e('1');
    r($_SESSION['album']['other-editor'] === array(6)) && p() && e('1');
}
finally
{
    foreach(glob($directory . '*') as $path) unlink($path);
    rmdir($directory);
    $file->savePath = $originalPath;
    $config->requestType = $originalType;
    $config->webRoot = $originalRoot;
    if($originalHost === null) unset($_SERVER['HTTP_HOST']);
    else $_SERVER['HTTP_HOST'] = $originalHost;
    unset($_SESSION['album'][$uid], $_SESSION['album']['used'][$uid], $_SESSION['album']['other-editor']);
}
