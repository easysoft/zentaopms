#!/usr/bin/env php
<?php
/**

title=测试 fileModel->setImgSize();
timeout=0
cid=16533

- 测试非图片内容 @<div>AaBbCcDd</div>
- 测试设置图片大小后的内容 @<img onload="setImageSize(this,0)"

- 测试设置图片大小后的内容 @<img onload="setImageSize(this,1000)"

- 测试设置图片大小后的内容 @<img onload="setImageSize(this,-1)"

- 测试设置图片大小后的内容 @<img onload="setImageSize(this,0)"

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('file');
r($tester->file->setImgSize('<div>AaBbCcDd</div>'))                                 && p() && e('<div>AaBbCcDd</div>');                   // 测试非图片内容
r(substr($tester->file->setImgSize('<img src="{888.png}" ></img>'),         0, 34)) && p() && e(`<img onload="setImageSize(this,0)"`);    // 测试设置图片大小后的内容
r(substr($tester->file->setImgSize('<img src="{888.png}" ></img>', '1000'), 0, 37)) && p() && e(`<img onload="setImageSize(this,1000)"`); // 测试设置图片大小后的内容
r(substr($tester->file->setImgSize('<img src="{999.png}" ></img>', '-1'),   0, 35)) && p() && e(`<img onload="setImageSize(this,-1)"`);   // 测试设置图片大小后的内容
r(substr($tester->file->setImgSize('<img src="{999.png}" ></img>', '0'),    0, 34)) && p() && e(`<img onload="setImageSize(this,0)"`);    // 测试设置图片大小后的内容
