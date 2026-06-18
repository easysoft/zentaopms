#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getVersion();
timeout=0
cid=16659

- 执行gitlabTest模块的getVersionTest方法，参数是'https://gitlab.example.com', 'glpat-test1234567890abcdef' 属性version @*
- 执行gitlabTest模块的getVersionTest方法，参数是'https://gitlab.example.com', 'invalid-token'  @~~
- 执行gitlabTest模块的getVersionTest方法，参数是'https://invalid-host.com', 'glpat-test1234567890abcdef'  @~~
- 执行gitlabTest模块的getVersionTest方法，参数是'', 'glpat-test1234567890abcdef'  @~~
- 执行gitlabTest模块的getVersionTest方法，参数是'https://gitlab.example.com', ''  @~~
- 执行gitlabTest模块的getVersionTest方法，参数是'incomplete-url', 'glpat-test1234567890abcdef'  @~~
- 执行gitlabTest模块的getVersionTest方法，参数是'https://gitlab.example.com/', 'glpat-test1234567890abcdef' 属性version @*

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->getVersionTest('https://gitlab.example.com', 'glpat-test1234567890abcdef')) && p('version') && e('15.8.2-ee');
r($gitlabTest->getVersionTest('https://gitlab.example.com', 'invalid-token') === null) && p() && e('1');
r($gitlabTest->getVersionTest('https://invalid-host.com', 'glpat-test1234567890abcdef') === null) && p() && e('1');
r($gitlabTest->getVersionTest('', 'glpat-test1234567890abcdef') === null) && p() && e('1');
r($gitlabTest->getVersionTest('https://gitlab.example.com', '') === null) && p() && e('1');
r($gitlabTest->getVersionTest('incomplete-url', 'glpat-test1234567890abcdef') === null) && p() && e('1');
r($gitlabTest->getVersionTest('https://gitlab.example.com/', 'glpat-test1234567890abcdef')) && p('version') && e('15.8.2-ee');
