#!/usr/bin/env php
<?php

/**
title=项目发布关联和移除Bug
timeout=0
cid=73

- 项目发布关联bug最终测试状态 @SUCCESS
- 单个移除bug最终测试状态 @SUCCESS
- 移除全部bug测试结果 @移除全部bug成功

*/
chdir(__DIR__);
include '../lib/releaselinkbug.ui.class.php';

zendata('bug')->loadYaml('bug', false, 1)->gen(5);
zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
