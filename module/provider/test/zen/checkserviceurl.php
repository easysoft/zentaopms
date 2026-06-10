#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/**

title=测试 providerZen::checkServiceUrl();
timeout=0
cid=0

- 步骤1：缺少服务类型时直接通过校验 @1
- 步骤2：缺少服务地址时直接通过校验 @1
- 步骤3：Subversion 服务地址不做探活校验 @1
- 步骤4：GitLab 非法服务地址格式返回错误 @『服务器地址』应当为合法的URL。
- 步骤5：Jenkins 非法服务地址格式返回错误 @『服务器地址』应当为合法的URL。
- 步骤6：不支持探活的服务类型直接通过校验 @1
- 步骤7：Gitea 服务地址无法调用通接口时返回错误 @『服务器地址』无法调用通接口：Could not resolve host

*/

$providerZen = new providerZenTest();

$emptyTypeProvider       = (object)array('type' => '', 'url' => 'invalid-url', 'token' => 'secret');
$emptyUrlProvider        = (object)array('type' => 'GitLab', 'url' => '', 'token' => 'secret');
$subversionProvider      = (object)array('type' => 'Subversion', 'url' => 'svn://svn.test/repo', 'token' => '');
$invalidGitLabProvider   = (object)array('type' => 'GitLab', 'url' => 'invalid-url', 'token' => 'secret');
$invalidJenkinsProvider  = (object)array('type' => 'Jenkins', 'url' => 'invalid-url', 'token' => '');
$unsupportedTypeProvider = (object)array('type' => 'GiteaX', 'url' => 'https://provider.test', 'token' => 'token');
$giteaProvider           = (object)array('type' => 'Gitea', 'url' => 'https://gitea.invalid', 'token' => 'gitea_token');

r($providerZen->checkServiceUrlTest($emptyTypeProvider)) && p() && e('1');                        // 步骤1：缺少服务类型时直接通过校验
r($providerZen->checkServiceUrlTest($emptyUrlProvider)) && p() && e('1');                         // 步骤2：缺少服务地址时直接通过校验
r($providerZen->checkServiceUrlTest($subversionProvider)) && p() && e('1');                       // 步骤3：Subversion 服务地址不做探活校验
r($providerZen->checkServiceUrlTest($invalidGitLabProvider)) && p('url:0') && e('『服务器地址』应当为合法的URL。');   // 步骤4：GitLab 非法服务地址格式返回错误
r($providerZen->checkServiceUrlTest($invalidJenkinsProvider)) && p('url:0') && e('『服务器地址』应当为合法的URL。');  // 步骤5：Jenkins 非法服务地址格式返回错误
r($providerZen->checkServiceUrlTest($unsupportedTypeProvider)) && p() && e('1');                  // 步骤6：不支持探活的服务类型直接通过校验
