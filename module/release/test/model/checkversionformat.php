#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->checkVersionFormat();
timeout=0
cid=1

- 执行releaseTester模块的checkVersionFormatTest方法，参数是'Aa1-._'  @1
- 执行releaseTester模块的checkVersionFormatTest方法，参数是'a1-'  @1
- 执行releaseTester模块的checkVersionFormatTest方法，参数是'' 第name条的0属性 @版本号只能包含大小写英文字母、数字、减号（-）、点（.） 、下划线（_）
- 执行releaseTester模块的checkVersionFormatTest方法，参数是'中文' 第name条的0属性 @版本号只能包含大小写英文字母、数字、减号（-）、点（.） 、下划线（_）
- 执行releaseTester模块的checkVersionFormatTest方法，参数是'%zen' 第name条的0属性 @版本号只能包含大小写英文字母、数字、减号（-）、点（.） 、下划线（_）

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

su('admin');
$releaseTester = new releaseTest();
r($releaseTester->checkVersionFormatTest('Aa1-._')) && p() && e('1');
r($releaseTester->checkVersionFormatTest('a1-'))    && p() && e('1');

r($releaseTester->checkVersionFormatTest(''))     && p('name:0') && e('版本号只能包含大小写英文字母、数字、减号（-）、点（.） 、下划线（_）');
r($releaseTester->checkVersionFormatTest('中文')) && p('name:0') && e('版本号只能包含大小写英文字母、数字、减号（-）、点（.） 、下划线（_）');
r($releaseTester->checkVersionFormatTest('%zen')) && p('name:0') && e('版本号只能包含大小写英文字母、数字、减号（-）、点（.） 、下划线（_）');