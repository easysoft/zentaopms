#!/usr/bin/env php
<?php
/**

title=测试图片解析失败时终止保存并保留上传文件。

- 解析失败返回稳定的失败标识。 @1
- 文件记录与文件内容均保持原样。 @1
- 原始正文及上传会话仍可用于重试。 @1

*/
// A failed image scan must never reach destructive cleanup.
ini_set('pcre.jit', '0');
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
global $tester, $config;

zenData('file')->gen(1);
$file = $tester->loadModel('file');
$originalPath = $file->savePath;
$originalType = $config->requestType;
$originalRoot = $config->webRoot;
$originalLimit = ini_get('pcre.recursion_limit');
$directory = sys_get_temp_dir() . '/image-parse-failure-' . bin2hex(random_bytes(6)) . '/';
mkdir($directory, 0700);
$file->savePath = $directory;
$config->requestType = 'PATH_INFO';
$config->webRoot = '/zentao/';
$uid = 'image-parse-failure';
$_SESSION['album'][$uid] = array(1);
try
{
    $tester->dao->update(TABLE_FILE)->set('pathname')->eq('retained.png')->set('objectType')->eq('')->set('objectID')->eq(0)->where('id')->eq(1)->exec();
    file_put_contents($directory . 'retained', 'original image bytes');
    $before = $tester->dao->select('*')->from(TABLE_FILE)->where('id')->eq(1)->fetch();
    $html = '<img ' . str_repeat('data-x="x" ', 100) . 'src="/zentao/file-read-1.png" />';
    $data = (object)array('spec' => $html);
    $failure = array();

    ini_set('pcre.recursion_limit', '50');
    try
    {
        $file->processImgURL($data, 'spec', $uid);
        $file->updateObjectID($uid, 1001, 'story');
        $file->autoDelete($uid);
    }
    catch(EndResponseException $response) { $failure = json_decode($response->getContent(), true); }
    finally { ini_set('pcre.recursion_limit', $originalLimit); }

    r(($failure['result'] ?? '') === 'fail' && ($failure['code'] ?? '') === 'editor_image_parse_failed') && p() && e('1');
    $after = $tester->dao->select('*')->from(TABLE_FILE)->where('id')->eq(1)->fetch();
    r($after == $before && is_file($directory . 'retained') && file_get_contents($directory . 'retained') === 'original image bytes') && p() && e('1');
    r($data->spec === $html && $_SESSION['album'][$uid] === array(1) && empty($_SESSION['album']['used'][$uid])) && p() && e('1');
}
finally
{
    foreach(glob($directory . '*') as $path) unlink($path);
    rmdir($directory);
    $file->savePath = $originalPath;
    $config->requestType = $originalType;
    $config->webRoot = $originalRoot;
    ini_set('pcre.recursion_limit', $originalLimit);
    unset($_SESSION['album'][$uid], $_SESSION['album']['used'][$uid]);
}
