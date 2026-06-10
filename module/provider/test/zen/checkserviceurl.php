#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/**

title=测试 providerZen::checkServiceUrl();
timeout=0
cid=0

- 步骤1：GitLab 服务地址可以调用通接口第result条的0属性 @1
- 步骤2：GitHub 地址转换为标准接口地址第requestUrl条的0属性 @https://api.github.com/user
- 步骤3：Jenkins 地址转换为接口地址第requestUrl条的0属性 @https://jenkins.test/api/json
- 步骤4：非法服务地址格式返回错误第url条的0属性 @『服务器地址』应当为合法的URL。
- 步骤5：Jenkins 非法服务地址格式返回错误第url条的0属性 @『服务器地址』应当为合法的URL。
- 步骤6：Gitea 服务地址无法调用通接口第url条的0属性 @『服务器地址』无法调用通接口：Could not resolve host
- 步骤7：Subversion 服务地址不校验且不发起请求第requestCount条的0属性 @0

*/

$providerZen = new providerZenTest();

$gitlabProvider = (object)array('type' => 'GitLab', 'url' => 'https://gitlab.test', 'token' => 'secret');
$gitlabResponse = array(
    'https://gitlab.test/api/v4/user' => array('{"id":1,"name":"admin"}', 200, 'body' => '{"id":1,"name":"admin"}', 'header' => array(), 'errno' => 0, 'info' => array(), 'response' => '{"id":1,"name":"admin"}')
);

$gitHubProvider = (object)array('type' => 'GitHub', 'url' => 'https://github.com', 'token' => 'github_token');
$gitHubResponse = array(
    'https://api.github.com/user' => array('{"login":"octocat"}', 200, 'body' => '{"login":"octocat"}', 'header' => array(), 'errno' => 0, 'info' => array(), 'response' => '{"login":"octocat"}')
);

$jenkinsProvider = (object)array('type' => 'Jenkins', 'url' => 'https://jenkins.test', 'token' => '');
$jenkinsResponse = array(
    'https://jenkins.test/api/json' => array('{"mode":"NORMAL"}', 200, 'body' => '{"mode":"NORMAL"}', 'header' => array(), 'errno' => 0, 'info' => array(), 'response' => '{"mode":"NORMAL"}')
);

$invalidProvider = (object)array('type' => 'GitLab', 'url' => 'invalid-url', 'token' => 'secret');
$invalidJenkinsProvider = (object)array('type' => 'Jenkins', 'url' => 'invalid-url', 'token' => '');

$giteaProvider = (object)array('type' => 'Gitea', 'url' => 'https://gitea.test', 'token' => 'gitea_token');
$giteaResponse = array(
    'https://gitea.test/api/v1/user' => array('', 0, 'body' => '', 'header' => array(), 'errno' => 6, 'info' => array(), 'response' => '')
);
$giteaErrors = array('https://gitea.test/api/v1/user' => array('Could not resolve host'));

$subversionProvider = (object)array('type' => 'Subversion', 'url' => 'svn://svn.test/repo', 'token' => '');

r($providerZen->checkServiceUrlTest($gitlabProvider, $gitlabResponse)) && p('result') && e('1');                    // 步骤1：GitLab 服务地址可以调用通接口
r($providerZen->checkServiceUrlTest($gitHubProvider, $gitHubResponse)) && p('requestUrl') && e('https://api.github.com/user'); // 步骤2：GitHub 地址转换为标准接口地址
r($providerZen->checkServiceUrlTest($jenkinsProvider, $jenkinsResponse)) && p('requestUrl') && e('https://jenkins.test/api/json'); // 步骤3：Jenkins 地址转换为接口地址
r($providerZen->checkServiceUrlTest($invalidProvider)) && p('url:0') && e('『服务器地址』应当为合法的URL。');         // 步骤4：非法服务地址格式返回错误
r($providerZen->checkServiceUrlTest($invalidJenkinsProvider)) && p('url:0') && e('『服务器地址』应当为合法的URL。');  // 步骤5：Jenkins 非法服务地址格式返回错误
r($providerZen->checkServiceUrlTest($giteaProvider, $giteaResponse, $giteaErrors)) && p('url:0') && e('『服务器地址』无法调用通接口：Could not resolve host'); // 步骤6：Gitea 服务地址无法调用通接口
r($providerZen->checkServiceUrlTest($subversionProvider)) && p('requestCount') && e('0');                           // 步骤7：Subversion 服务地址不校验且不发起请求
