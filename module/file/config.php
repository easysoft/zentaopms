<?php
$config->file->mimes['xml']     = 'text/xml';
$config->file->mimes['html']    = 'text/html';
$config->file->mimes['csv']     = 'text/csv';
$config->file->mimes['default'] = 'application/octet-stream';

$config->file->imageExtensions = array('jpeg', 'jpg', 'gif', 'png');
$config->file->image2Compress  = array('.jpg', '.bmp', '.jpeg');

$config->file->charset = array('UTF-8' => 'UTF-8', 'GBK' => 'GBK', 'BIG5' => 'BIG5');

$config->file->ueditor["imageActionName"]     = "uploadimage";
$config->file->ueditor["imageFieldName"]      = "upfile";
$config->file->ueditor["imageMaxSize"]        = 2048000;
$config->file->ueditor["imageAllowFiles"]     = array(".png", ".jpg", ".jpeg", ".gif", ".bmp");
$config->file->ueditor["imageCompressEnable"] = true;
$config->file->ueditor["imageCompressBorder"] = 1600;
$config->file->ueditor["imageInsertAlign"]    = "none";
$config->file->ueditor["imageUrlPrefix"]      = "";
$config->file->ueditor["imagePathFormat"]     = "";

$config->file->ueditor["snapscreenActionName"]  = "uploadimage";
$config->file->ueditor["snapscreenInsertAlign"] = "none";
$config->file->ueditor["snapscreenUrlPrefix"]   = "";
$config->file->ueditor["snapscreenPathFormat"]  = "";

$config->file->ueditor["videoActionName"] = "uploadvideo";
$config->file->ueditor["videoFieldName"]  = "upfile";
$config->file->ueditor["videoMaxSize"]    = 102400000;
$config->file->ueditor["videoAllowFiles"] = array(".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid");
$config->file->ueditor["videoUrlPrefix"]  = "";
$config->file->ueditor["videoPathFormat"] = "";
