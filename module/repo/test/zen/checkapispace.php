#!/usr/bin/env php
<?php
/**

title=测试 repoZen->checkApiSpace();
timeout=0
cid=0

- 空间ID为零时返回400
 - 属性result @false
 - 属性code @400
 - 属性message @Invalid space ID.
- 负数空间ID返回400
 - 属性result @false
 - 属性code @400
 - 属性message @Invalid space ID.
- 无效ID不查询空间或权限
 - 属性spaceReads @0
 - 属性privReads @0
 - 属性memberReads @0
- 不存在的空间返回404
 - 属性result @false
 - 属性code @404
 - 属性message @Space does not exist.
- 已删除空间返回404
 - 属性result @false
 - 属性code @404
 - 属性message @Space does not exist.
- 删除检查先于成员和权限查询
 - 属性spaceLookups @50
 - 属性privReads @0
 - 属性memberReads @0
- 普通用户可以读取公开继承授权空间
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 公开继承授权空间沿用外层方法权限
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 私有空间成员可以读取空间
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 其他空间成员身份不能读取目标私有空间
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 非成员不能读取私有空间
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 公开独立授权空间需要列表权限
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 空权限数组不会被放行
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 显式关闭的权限不会被放行
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 其他模块的同名权限不会被放行
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 独立授权公开空间有列表权限即可读取
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 私有独立授权空间成员也需要方法权限
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 私有独立授权空间成员有列表权限可以读取
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 方法权限不能绕过私有空间成员检查
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 创建权限不能替代列表权限
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 私有继承授权空间成员可以创建
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 私有继承授权空间不要求repo.create权限
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 私有继承授权空间持有创建权限即可创建
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 独立授权创建不能缺少createrepo权限
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 独立授权创建不能缺少create权限
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 列表权限不能替代创建权限
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 独立授权公开空间同时具备两种创建权限可以创建
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 独立授权私有空间成员具备创建权限可以创建
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 创建权限不能绕过私有空间可见性
 - 属性result @false
 - 属性code @403
 - 属性message @Space is not allowed.
- 对传入的空间ID读取对象和权限
 - 属性spaceLookups @20
 - 属性privLookups @20
 - 属性memberReads @1
- 管理员可以读取私有空间
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 管理员可以在独立授权私有空间创建
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 管理员通过存在性检查后无需读取权限
 - 属性spaceLookups @40
 - 属性privReads @0
 - 属性memberReads @0
- 管理员也不能访问已删除空间
 - 属性result @false
 - 属性code @404
 - 属性message @Space does not exist.
- 管理员也不能访问不存在空间
 - 属性result @false
 - 属性code @404
 - 属性message @Space does not exist.
- 管理员也不能使用无效空间ID
 - 属性result @false
 - 属性code @400
 - 属性message @Invalid space ID.
- 拒绝访问只返回一次错误属性errorCount @1
- 成功检查不污染登录态和HTTP状态 @1
- 拒绝检查不污染登录态和HTTP状态 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$test = new repoZenTest();
$context = array(
    'user' => (object)array('account' => 'coder', 'admin' => false),
    'spaces' => array(
        10 => (object)array('id' => 10, 'acl' => 'open',    'auth' => 'extend', 'deleted' => 0),
        20 => (object)array('id' => 20, 'acl' => 'private', 'auth' => 'extend', 'deleted' => 0),
        30 => (object)array('id' => 30, 'acl' => 'open',    'auth' => 'reset',  'deleted' => 0),
        40 => (object)array('id' => 40, 'acl' => 'private', 'auth' => 'reset',  'deleted' => 0),
        50 => (object)array('id' => 50, 'acl' => 'open',    'auth' => 'extend', 'deleted' => 1),
        60 => (object)array('id' => 60, 'acl' => 'private', 'auth' => 'extend', 'deleted' => 0),
    ),
    'members' => array(20 => array('coder' => (object)array('role' => 'member')), 40 => array('coder' => (object)array('role' => 'member')), 60 => array('other' => (object)array('role' => 'member'))),
    'privs' => array(),
);
$listPrivs   = array_replace($context, array('privs' => array(30 => array('repo' => array('maintain' => 1)), 40 => array('repo' => array('maintain' => 1)))));
$createOnly  = array_replace($context, array('privs' => array(20 => array('repo' => array('create' => 1)), 30 => array('repo' => array('create' => 1)), 40 => array('repo' => array('create' => 1)))));
$actionOnly  = array_replace($context, array('privs' => array(20 => array('repo' => array('createrepo' => 1)), 30 => array('repo' => array('createrepo' => 1)), 40 => array('repo' => array('createrepo' => 1)))));
$bothPrivs   = array_replace($context, array('privs' => array(30 => array('repo' => array('create' => 1, 'createrepo' => 1)), 40 => array('repo' => array('create' => 1, 'createrepo' => 1)))));
$outsider    = array_replace($bothPrivs, array('user' => (object)array('account' => 'other', 'admin' => false)));
$admin       = array_replace($context, array('user' => (object)array('account' => 'admin', 'admin' => true)));
$emptyPrivs  = array_replace($context, array('privs' => array(30 => array())));
$deniedPrivs = array_replace($context, array('privs' => array(30 => array('repo' => array('maintain' => 0)))));
$otherPrivs  = array_replace($context, array('privs' => array(30 => array('space' => array('maintain' => 1)))));

r($test->checkApiSpaceTest(0, 'maintain', $context)) && p('result,code,message') && e('false,400,Invalid space ID.'); // 空间ID为零时返回400
r($test->checkApiSpaceTest(-1, 'maintain', $context)) && p('result,code,message') && e('false,400,Invalid space ID.'); // 负数空间ID返回400
r($test->checkApiSpaceTest(0, 'maintain', $context)) && p('spaceReads,privReads,memberReads') && e('0,0,0'); // 无效ID不查询空间或权限
r($test->checkApiSpaceTest(99, 'maintain', $context)) && p('result,code,message') && e('false,404,Space does not exist.'); // 不存在的空间返回404
r($test->checkApiSpaceTest(50, 'maintain', $context)) && p('result,code,message') && e('false,404,Space does not exist.'); // 已删除空间返回404
r($test->checkApiSpaceTest(50, 'maintain', $context)) && p('spaceLookups,privReads,memberReads') && e('50,0,0'); // 删除检查先于成员和权限查询
r($test->checkApiSpaceTest(10, 'maintain', $context)) && p('result,code,errorCount') && e('true,200,0'); // 普通用户可以读取公开继承授权空间
r($test->checkApiSpaceTest(10, 'createrepo', $context)) && p('result,code,errorCount') && e('true,200,0'); // 公开继承授权空间沿用外层方法权限
r($test->checkApiSpaceTest(20, 'maintain', $context)) && p('result,code,errorCount') && e('true,200,0'); // 私有空间成员可以读取空间
r($test->checkApiSpaceTest(60, 'maintain', $context)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 其他空间成员身份不能读取目标私有空间
r($test->checkApiSpaceTest(20, 'maintain', $outsider)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 非成员不能读取私有空间
r($test->checkApiSpaceTest(30, 'maintain', $context)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 公开独立授权空间需要列表权限
r($test->checkApiSpaceTest(30, 'maintain', $emptyPrivs)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 空权限数组不会被放行
r($test->checkApiSpaceTest(30, 'maintain', $deniedPrivs)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 显式关闭的权限不会被放行
r($test->checkApiSpaceTest(30, 'maintain', $otherPrivs)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 其他模块的同名权限不会被放行
r($test->checkApiSpaceTest(30, 'maintain', $listPrivs)) && p('result,code,errorCount') && e('true,200,0'); // 独立授权公开空间有列表权限即可读取
r($test->checkApiSpaceTest(40, 'maintain', $context)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 私有独立授权空间成员也需要方法权限
r($test->checkApiSpaceTest(40, 'maintain', $listPrivs)) && p('result,code,errorCount') && e('true,200,0'); // 私有独立授权空间成员有列表权限可以读取
r($test->checkApiSpaceTest(40, 'maintain', array_replace($listPrivs, array('user' => $outsider['user'])))) && p('result,code,message') && e('false,403,Space is not allowed.'); // 方法权限不能绕过私有空间成员检查
r($test->checkApiSpaceTest(30, 'maintain', $createOnly)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 创建权限不能替代列表权限
r($test->checkApiSpaceTest(20, 'createrepo', $context)) && p('result,code,errorCount') && e('true,200,0'); // 私有继承授权空间成员可以创建
r($test->checkApiSpaceTest(20, 'createrepo', $actionOnly)) && p('result,code,errorCount') && e('true,200,0'); // 私有继承授权空间不要求repo.create权限
r($test->checkApiSpaceTest(20, 'createrepo', $createOnly)) && p('result,code,errorCount') && e('true,200,0'); // 私有继承授权空间持有创建权限即可创建
r($test->checkApiSpaceTest(30, 'createrepo', $createOnly)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 独立授权创建不能缺少createrepo权限
r($test->checkApiSpaceTest(30, 'createrepo', $actionOnly)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 独立授权创建不能缺少create权限
r($test->checkApiSpaceTest(30, 'createrepo', $listPrivs)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 列表权限不能替代创建权限
r($test->checkApiSpaceTest(30, 'createrepo', $bothPrivs)) && p('result,code,errorCount') && e('true,200,0'); // 独立授权公开空间同时具备两种创建权限可以创建
r($test->checkApiSpaceTest(40, 'createrepo', $bothPrivs)) && p('result,code,errorCount') && e('true,200,0'); // 独立授权私有空间成员具备创建权限可以创建
r($test->checkApiSpaceTest(40, 'createrepo', $outsider)) && p('result,code,message') && e('false,403,Space is not allowed.'); // 创建权限不能绕过私有空间可见性
r($test->checkApiSpaceTest(20, 'maintain', $context)) && p('spaceLookups,privLookups,memberReads') && e('20,20,1'); // 对传入的空间ID读取对象和权限
r($test->checkApiSpaceTest(20, 'maintain', $admin)) && p('result,code,errorCount') && e('true,200,0'); // 管理员可以读取私有空间
r($test->checkApiSpaceTest(40, 'createrepo', $admin)) && p('result,code,errorCount') && e('true,200,0'); // 管理员可以在独立授权私有空间创建
r($test->checkApiSpaceTest(40, 'createrepo', $admin)) && p('spaceLookups,privReads,memberReads') && e('40,0,0'); // 管理员通过存在性检查后无需读取权限
r($test->checkApiSpaceTest(50, 'createrepo', $admin)) && p('result,code,message') && e('false,404,Space does not exist.'); // 管理员也不能访问已删除空间
r($test->checkApiSpaceTest(99, 'maintain', $admin)) && p('result,code,message') && e('false,404,Space does not exist.'); // 管理员也不能访问不存在空间
r($test->checkApiSpaceTest(0, 'maintain', $admin)) && p('result,code,message') && e('false,400,Invalid space ID.'); // 管理员也不能使用无效空间ID
r($test->checkApiSpaceTest(60, 'maintain', $context)) && p('errorCount') && e('1'); // 拒绝访问只返回一次错误

$originalUser = $app->user;
$originalStatus = http_response_code();
http_response_code(202);
$test->checkApiSpaceTest(40, 'createrepo', $bothPrivs);
r($app->user === $originalUser && http_response_code() === 202) && p() && e('1'); // 成功检查不污染登录态和HTTP状态
$test->checkApiSpaceTest(40, 'createrepo', $outsider);
r($app->user === $originalUser && http_response_code() === 202) && p() && e('1'); // 拒绝检查不污染登录态和HTTP状态
http_response_code($originalStatus === false ? 200 : $originalStatus);
