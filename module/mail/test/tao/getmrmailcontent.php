#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getMRMailContent();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('mr')->gen(1);

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->config->webRoot = '/';
$mr = $mailModel->fetchById(1, 'mr');

r($mailModel->getMRMailContent($mr, ''))                  && p() && e('0'); //只传入mr
r($mailModel->getMRMailContent($mr, 'compilefail'))       && p() && e("您提交的合并请求：<a href='/mr-view-1.html'>Test MR</a> 流水线任务执行失败，查看执行结果。"); //传入动作：compilefail
r($mailModel->getMRMailContent($mr, 'compilepass'))       && p() && e("您提交的合并请求：<a href='/mr-view-1.html'>Test MR</a> 流水线任务执行通过。");               //传入动作：compilepass
r($mailModel->getMRMailContent($mr, 'compilepass', 'cc')) && p() && e("有一个合并请求：<a href='/mr-view-1.html'>Test MR</a> 待审核。");                             //传入动作：compilepass，发信给抄送人
