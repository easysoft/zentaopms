#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/**

title=测试 providerZen::getCheckApiUrl();
timeout=0
cid=0

- 步骤1：GitHub 公共地址转换为标准用户接口 @https://api.github.com/user
- 步骤2：GitHub API 地址补全 user 接口 @https://api.github.com/user
- 步骤3：GitHub 企业版地址补全 api/v3/user 接口 @https://github.enterprise.test/api/v3/user
- 步骤4：GitLab 地址补全 api/v4/user 接口 @https://gitlab.test/api/v4/user
- 步骤5：Jenkins 地址补全 api/json 接口 @https://jenkins.test/api/json
- 步骤6：不支持的服务类型返回空接口地址 @0

*/

$providerZen = new providerZenTest();

r($providerZen->getCheckApiUrlTest('GitHub', 'https://github.com')) && p() && e('https://api.github.com/user');                        // 步骤1：GitHub 公共地址转换为标准用户接口
r($providerZen->getCheckApiUrlTest('GitHub', 'https://api.github.com')) && p() && e('https://api.github.com/user');                   // 步骤2：GitHub API 地址补全 user 接口
r($providerZen->getCheckApiUrlTest('GitHub', 'https://github.enterprise.test')) && p() && e('https://github.enterprise.test/api/v3/user'); // 步骤3：GitHub 企业版地址补全 api/v3/user 接口
r($providerZen->getCheckApiUrlTest('GitLab', 'https://gitlab.test')) && p() && e('https://gitlab.test/api/v4/user');                 // 步骤4：GitLab 地址补全 api/v4/user 接口
r($providerZen->getCheckApiUrlTest('Jenkins', 'https://jenkins.test')) && p() && e('https://jenkins.test/api/json');                 // 步骤5：Jenkins 地址补全 api/json 接口
r(strlen($providerZen->getCheckApiUrlTest('Subversion', 'svn://svn.test/repo'))) && p() && e('0');                                   // 步骤6：不支持的服务类型返回空接口地址
