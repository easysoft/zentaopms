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

$table = zenData('pipeline');
$table->id->range('1-5');
$table->type->range('gitlab{5}');
$table->name->range('gitlab-test{2},gitlab-prod{2},gitlab-dev{1}');
$table->url->range('https://gitlab.example.com{2},http://gitlab.test.com{2},https://gitlab.dev.com{1}');
$table->token->range('glpat-test1234567890abcdef{2},glpat-prod{2},glpat-dev{1}');
$table->account->range('admin{3},user{2}');
$table->deleted->range('0{5}');
$table->gen(5);

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->getVersionTest('https://gitlab.example.com', 'glpat-test1234567890abcdef')) && p('version') && e('*');
r($gitlabTest->getVersionTest('https://gitlab.example.com', 'invalid-token')) && p() && e('~~');
r($gitlabTest->getVersionTest('https://invalid-host.com', 'glpat-test1234567890abcdef')) && p() && e('~~');
r($gitlabTest->getVersionTest('', 'glpat-test1234567890abcdef')) && p() && e('~~');
r($gitlabTest->getVersionTest('https://gitlab.example.com', '')) && p() && e('~~');
r($gitlabTest->getVersionTest('incomplete-url', 'glpat-test1234567890abcdef')) && p() && e('~~');
r($gitlabTest->getVersionTest('https://gitlab.example.com/', 'glpat-test1234567890abcdef')) && p('version') && e('*');