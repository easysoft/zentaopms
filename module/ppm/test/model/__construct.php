#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::__construct();
timeout=0
cid=0

- 执行ppmModel模块的__constructTest方法  @ppm
- 执行ppmModel模块的__constructTest方法，参数是'ppm'  @ppm
- 执行ppmModel模块的__constructTest方法，参数是'pullreq'  @pullreq
- 执行ppmModel模块的__constructTest方法，参数是'story'  @ppm
- 执行ppmModel模块的__constructTest方法，参数是'execution'  @ppm

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->__constructTest()) && p() && e('ppm');
r($ppmModel->__constructTest('ppm')) && p() && e('ppm');
r($ppmModel->__constructTest('pullreq')) && p() && e('pullreq');
r($ppmModel->__constructTest('story')) && p() && e('ppm');
r($ppmModel->__constructTest('execution')) && p() && e('ppm');