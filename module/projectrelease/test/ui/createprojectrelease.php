#!/usr/bin/env php
<?php

/**

title=创建项目发布
timeout=0
cid=73

- 创建项目发布时检查必填校验测试结果 @创建项目发布表单页必填提示信息正确
- 创建一个已发布的发布，且选择已有应用测试结果 @创建项目发布成功
- 创建一个未开始的发布，且勾选新建应用测试结果 @创建项目发布成功

*/
chdir(__DIR__);
include '../lib/createprojectrelease.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('product')->loadYaml('product', false, 1)->gen(1);
