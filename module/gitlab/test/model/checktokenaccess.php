#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::checkTokenAccess();
timeout=0
cid=16635

- 使用空的数据验证token权限 @return false
- 使用错误的host验证token权限 @return false
- 使用正确的host,错误的token验证token权限 @return null
- 通过host,token验证token权限 @success
- 通过host,权限不足的token验证token权限 @no access

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$gitlabTest = new gitlabModelTest();

r($gitlabTest->checkTokenAccessTest('', '')) && p() && e('return false');
r($gitlabTest->checkTokenAccessTest('http://10.0.1.161:5108', '')) && p() && e('return false');
r($gitlabTest->checkTokenAccessTest('http://10.0.7.242:9980', '')) && p() && e('return null');
r($gitlabTest->checkTokenAccessTest('http://10.0.7.242:9980', 'x88fZokrp5hShia2jyBN')) && p() && e('success');
r($gitlabTest->checkTokenAccessTest('http://10.0.7.242:9980', 'wVFHE6NZA-cJy-3U2y2J')) && p() && e('no access');
