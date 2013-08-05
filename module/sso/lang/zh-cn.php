<?php
$lang->sso->common    = '单点登录';
$lang->sso->browse    = '应用列表';
$lang->sso->create    = '添加应用';
$lang->sso->edit      = '编辑应用';
$lang->sso->delete    = '删除应用';
$lang->sso->code      = '代号';
$lang->sso->title     = '名称';
$lang->sso->key       = '密钥';
$lang->sso->ip        = 'IP列表';
$lang->sso->createKey = '重新生成密钥';

$lang->sso->confirmDelete = '您确定删除该应用吗？';

$lang->sso->note = new stdClass();
$lang->sso->note->title = '授权应用名称';
$lang->sso->note->code  = '授权应用代号';
$lang->sso->note->ip    = "允许该应用使用这些ip访问，多个ip使用逗号隔开。支持IP段，如192.168.1.*";

$lang->sso->error = new stdClass();
$lang->sso->error->title = '名称不能为空';
$lang->sso->error->code  = '代号不能为空';
$lang->sso->error->key   = '密钥不能为空';
$lang->sso->error->ip    = 'IP列表不能为空';

$lang->sso->instruction = <<<EOT
<p><strong>示例应用</strong>：名称为"测试"，代号为"test"，密钥为"20c8eb0d522d2e1a09d4ea18e4df3a59"，IP列表为"192.168.11.*,127.0.0.1"。</p>
<p><strong>1.用户验证</strong></p>
<p>授权应用请求禅道的用户验证API，检查用户在该应用输入的用户名和密码是否正确，实现单点登录。</p>
<p>API地址为sso模块的auth方法，POST数据为登录用户的用户名account和密码与密钥形成的加密字符串md5(md5(password) + key)，成功则返回用户信息（json格式），失败则返回fail。</p>
<p>示例：请求地址 http:://www.demo.com/sso-auth-test，POST字符串 account=admin&authcode=c44c577432230ad8e67160d3f9f0b91c。</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;注：test为应用代号，c44c577432230ad8e67160d3f9f0b91为md5(md5('123456') + '20c8eb0d522d2e1a09d4ea18e4df3a59')</p>
<p><strong>2.获取用户列表</strong></p>
<p>授权应用访问禅道的用户列表API，获取禅道所有用户信息。</p>
<p>API地址为sso模块的users方法，POST数据为应用密钥，成功返回用户列表（json格式），失败返回fail。</p>
<p>示例：请求地址 http:://www.demo.com/sso-users-test，POST字符串 key=20c8eb0d522d2e1a09d4ea18e4df3a59。</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;注：test为应用代号，20c8eb0d522d2e1a09d4ea18e4df3a59为应用密钥</p>
<p><strong>3.获取部门列表</strong></p>
<p>授权应用访问禅道的部门列表API，获取禅道所有部门信息。</p>
<p>API地址为sso模块的depts方法，POST数据为应用密钥，成功返回用户列表（json格式），失败返回fail。</p>
<p>示例：请求地址 http:://www.demo.com/sso-depts-test，POST字符串 key=20c8eb0d522d2e1a09d4ea18e4df3a59。</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;注：test为应用代号，20c8eb0d522d2e1a09d4ea18e4df3a59为应用密钥</p>
EOT;
