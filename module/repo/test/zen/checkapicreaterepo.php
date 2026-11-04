#!/usr/bin/env php
<?php
/**

title=测试 repoZen->checkApiCreateRepo();
timeout=0
cid=0

- 仅必填字段即可通过
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 允许空描述和空成员列表
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 支持多个产品和有效私有成员
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 接受正整数上界
 - 属性result @true
 - 属性code @200
 - 属性errorCount @0
- 汇总全部缺失的必填字段
 - 属性result @false
 - 属性code @400
 - 属性fields @space|name|product
- 空间不可缺失
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 空间不可为null
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 空间不可为零
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 空间不可为负数
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 不将数字字符串转换为空间整数
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 空间不可为浮点数
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 空间不可为布尔值
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 空间不可为数组
 - 属性result @false
 - 属性code @400
 - 属性fields @space
- 返回明确的空间字段错误属性message @A positive integer space ID is required.
- 名称不可缺失
 - 属性result @false
 - 属性code @400
 - 属性fields @name
- 名称不可为null
 - 属性result @false
 - 属性code @400
 - 属性fields @name
- 名称不可为空字符串
 - 属性result @false
 - 属性code @400
 - 属性fields @name
- 名称不可仅包含空白
 - 属性result @false
 - 属性code @400
 - 属性fields @name
- 名称不可为数字
 - 属性result @false
 - 属性code @400
 - 属性fields @name
- 名称不可为数组
 - 属性result @false
 - 属性code @400
 - 属性fields @name
- 返回明确的名称字段错误属性message @A repository name is required.
- 产品不可缺失
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品不可为null
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品列表不可为空
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品不可为字符串
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品不可为关联数组
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品列表索引必须从零连续
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 检查每个产品ID的类型
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品ID不可为零
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品ID不可为负数
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品ID不可为浮点数
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品ID不可为布尔值
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品ID不可为null
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 产品列表不可嵌套数组
 - 属性result @false
 - 属性code @400
 - 属性fields @product
- 区分产品列表格式错误属性message @A non-empty array of product IDs is required.
- 区分产品ID值错误属性message @Product IDs must be positive integers.
- 描述不可为null
 - 属性result @false
 - 属性code @400
 - 属性fields @desc
- 描述不可为数组
 - 属性result @false
 - 属性code @400
 - 属性fields @desc
- 描述必须保持字符串类型属性message @Description must be a string.
- 拒绝未支持的访问控制枚举
 - 属性result @false
 - 属性code @400
 - 属性fields @acl
- 访问控制不可为null
 - 属性result @false
 - 属性code @400
 - 属性fields @acl
- 访问控制使用严格类型比较
 - 属性result @false
 - 属性code @400
 - 属性fields @acl
- 访问控制枚举区分大小写属性message @ACL must be open or private.
- 成员不可为null
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 成员不可为字符串
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 成员不可为关联数组
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 成员列表索引必须从零连续
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 成员账号不可为数字
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 成员账号不可为空
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 检查全部成员账号是否存在
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 不接受有效用户查询之外的已删除账号
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 不接受关闭账号
 - 属性result @false
 - 属性code @400
 - 属性fields @members
- 返回成员列表格式错误属性message @Members must be an array of user accounts.
- 返回成员有效性错误属性message @Member does not exist or is inactive.
- 用户查询必须排除空账号、删除和关闭账号属性userOptions @noletter|noempty|nodeleted|noclosed
- 未传成员时无需查询用户 @1
- 校验成功不改写请求字段属性unchanged @1
- 多字段错误通过一次响应统一返回属性errorCount @1
- 成功用例恢复请求和HTTP状态 @1
- 失败用例恢复请求和HTTP状态 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/* These are query results, not database fixtures; the validation must request active users only. */
$test  = new repoZenTest();
$users = array('coder' => 'Coder', 'member' => 'Member');
$valid = array('space' => 10, 'name' => ' demo ', 'product' => array(12));

r($test->checkApiCreateRepoTest($valid, $users)) && p('result,code,errorCount') && e('true,200,0'); // 仅必填字段即可通过
r($test->checkApiCreateRepoTest($valid + array('desc' => '', 'acl' => 'open', 'members' => array()), $users)) && p('result,code,errorCount') && e('true,200,0'); // 允许空描述和空成员列表
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(12, 13), 'acl' => 'private', 'members' => array('coder', 'member'))), $users)) && p('result,code,errorCount') && e('true,200,0'); // 支持多个产品和有效私有成员
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => PHP_INT_MAX, 'product' => array(PHP_INT_MAX))), $users)) && p('result,code,errorCount') && e('true,200,0'); // 接受正整数上界
r($test->checkApiCreateRepoTest(array(), $users)) && p('result,code,fields') && e('false,400,space|name|product'); // 汇总全部缺失的必填字段
r($test->checkApiCreateRepoTest(array_diff_key($valid, array('space' => true)), $users)) && p('result,code,fields') && e('false,400,space'); // 空间不可缺失
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => null)), $users)) && p('result,code,fields') && e('false,400,space'); // 空间不可为null
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => 0)), $users)) && p('result,code,fields') && e('false,400,space'); // 空间不可为零
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => -1)), $users)) && p('result,code,fields') && e('false,400,space'); // 空间不可为负数
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => '10')), $users)) && p('result,code,fields') && e('false,400,space'); // 不将数字字符串转换为空间整数
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => 10.0)), $users)) && p('result,code,fields') && e('false,400,space'); // 空间不可为浮点数
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => true)), $users)) && p('result,code,fields') && e('false,400,space'); // 空间不可为布尔值
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => array(10))), $users)) && p('result,code,fields') && e('false,400,space'); // 空间不可为数组
r($test->checkApiCreateRepoTest(array_replace($valid, array('space' => 0)), $users)) && p('message') && e('A positive integer space ID is required.'); // 返回明确的空间字段错误
r($test->checkApiCreateRepoTest(array_diff_key($valid, array('name' => true)), $users)) && p('result,code,fields') && e('false,400,name'); // 名称不可缺失
r($test->checkApiCreateRepoTest(array_replace($valid, array('name' => null)), $users)) && p('result,code,fields') && e('false,400,name'); // 名称不可为null
r($test->checkApiCreateRepoTest(array_replace($valid, array('name' => '')), $users)) && p('result,code,fields') && e('false,400,name'); // 名称不可为空字符串
r($test->checkApiCreateRepoTest(array_replace($valid, array('name' => " \t\n")), $users)) && p('result,code,fields') && e('false,400,name'); // 名称不可仅包含空白
r($test->checkApiCreateRepoTest(array_replace($valid, array('name' => 123)), $users)) && p('result,code,fields') && e('false,400,name'); // 名称不可为数字
r($test->checkApiCreateRepoTest(array_replace($valid, array('name' => array('demo'))), $users)) && p('result,code,fields') && e('false,400,name'); // 名称不可为数组
r($test->checkApiCreateRepoTest(array_replace($valid, array('name' => '')), $users)) && p('message') && e('A repository name is required.'); // 返回明确的名称字段错误
r($test->checkApiCreateRepoTest(array_diff_key($valid, array('product' => true)), $users)) && p('result,code,fields') && e('false,400,product'); // 产品不可缺失
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => null)), $users)) && p('result,code,fields') && e('false,400,product'); // 产品不可为null
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array())), $users)) && p('result,code,fields') && e('false,400,product'); // 产品列表不可为空
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => '12')), $users)) && p('result,code,fields') && e('false,400,product'); // 产品不可为字符串
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array('id' => 12))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品不可为关联数组
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(1 => 12))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品列表索引必须从零连续
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(12, '13'))), $users)) && p('result,code,fields') && e('false,400,product'); // 检查每个产品ID的类型
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(0))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品ID不可为零
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(-1))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品ID不可为负数
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(12.0))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品ID不可为浮点数
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(true))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品ID不可为布尔值
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(null))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品ID不可为null
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array(array(12)))), $users)) && p('result,code,fields') && e('false,400,product'); // 产品列表不可嵌套数组
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array())), $users)) && p('message') && e('A non-empty array of product IDs is required.'); // 区分产品列表格式错误
r($test->checkApiCreateRepoTest(array_replace($valid, array('product' => array('12'))), $users)) && p('message') && e('Product IDs must be positive integers.'); // 区分产品ID值错误
r($test->checkApiCreateRepoTest($valid + array('desc' => null), $users)) && p('result,code,fields') && e('false,400,desc'); // 描述不可为null
r($test->checkApiCreateRepoTest($valid + array('desc' => array()), $users)) && p('result,code,fields') && e('false,400,desc'); // 描述不可为数组
r($test->checkApiCreateRepoTest($valid + array('desc' => 123), $users)) && p('message') && e('Description must be a string.'); // 描述必须保持字符串类型
r($test->checkApiCreateRepoTest($valid + array('acl' => 'custom'), $users)) && p('result,code,fields') && e('false,400,acl'); // 拒绝未支持的访问控制枚举
r($test->checkApiCreateRepoTest($valid + array('acl' => null), $users)) && p('result,code,fields') && e('false,400,acl'); // 访问控制不可为null
r($test->checkApiCreateRepoTest($valid + array('acl' => true), $users)) && p('result,code,fields') && e('false,400,acl'); // 访问控制使用严格类型比较
r($test->checkApiCreateRepoTest($valid + array('acl' => 'OPEN'), $users)) && p('message') && e('ACL must be open or private.'); // 访问控制枚举区分大小写
r($test->checkApiCreateRepoTest($valid + array('members' => null), $users)) && p('result,code,fields') && e('false,400,members'); // 成员不可为null
r($test->checkApiCreateRepoTest($valid + array('members' => 'coder'), $users)) && p('result,code,fields') && e('false,400,members'); // 成员不可为字符串
r($test->checkApiCreateRepoTest($valid + array('members' => array('owner' => 'coder')), $users)) && p('result,code,fields') && e('false,400,members'); // 成员不可为关联数组
r($test->checkApiCreateRepoTest($valid + array('members' => array(1 => 'coder')), $users)) && p('result,code,fields') && e('false,400,members'); // 成员列表索引必须从零连续
r($test->checkApiCreateRepoTest($valid + array('members' => array(12)), $users)) && p('result,code,fields') && e('false,400,members'); // 成员账号不可为数字
r($test->checkApiCreateRepoTest($valid + array('members' => array('')), $users)) && p('result,code,fields') && e('false,400,members'); // 成员账号不可为空
r($test->checkApiCreateRepoTest($valid + array('members' => array('coder', 'missing')), $users)) && p('result,code,fields') && e('false,400,members'); // 检查全部成员账号是否存在
r($test->checkApiCreateRepoTest($valid + array('members' => array('deleted')), $users)) && p('result,code,fields') && e('false,400,members'); // 不接受有效用户查询之外的已删除账号
r($test->checkApiCreateRepoTest($valid + array('members' => array('closed')), $users)) && p('result,code,fields') && e('false,400,members'); // 不接受关闭账号
r($test->checkApiCreateRepoTest($valid + array('members' => 'coder'), $users)) && p('message') && e('Members must be an array of user accounts.'); // 返回成员列表格式错误
r($test->checkApiCreateRepoTest($valid + array('members' => array('missing')), $users)) && p('message') && e('Member does not exist or is inactive.'); // 返回成员有效性错误
r($test->checkApiCreateRepoTest($valid + array('members' => array('coder')), $users)) && p('userOptions') && e('noletter|noempty|nodeleted|noclosed'); // 用户查询必须排除空账号、删除和关闭账号
$withoutMembers = $test->checkApiCreateRepoTest($valid, $users);
r($withoutMembers->userOptions === '') && p() && e('1'); // 未传成员时无需查询用户
r($test->checkApiCreateRepoTest($valid, $users)) && p('unchanged') && e('1'); // 校验成功不改写请求字段
r($test->checkApiCreateRepoTest(array(), $users)) && p('errorCount') && e('1'); // 多字段错误通过一次响应统一返回

$originalPost   = $_POST;
$originalStatus = http_response_code();
$_POST = array('sentinel' => 'original');
http_response_code(202);
$test->checkApiCreateRepoTest($valid, $users);
r($_POST === array('sentinel' => 'original') && http_response_code() === 202) && p() && e('1'); // 成功用例恢复请求和HTTP状态
$test->checkApiCreateRepoTest(array(), $users);
r($_POST === array('sentinel' => 'original') && http_response_code() === 202) && p() && e('1'); // 失败用例恢复请求和HTTP状态
$_POST = $originalPost;
http_response_code($originalStatus === false ? 200 : $originalStatus);
