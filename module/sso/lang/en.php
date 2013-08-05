<?php
$lang->sso->common    = 'SSO';
$lang->sso->browse    = 'App list';
$lang->sso->create    = 'Create App';
$lang->sso->edit      = 'Edit App';
$lang->sso->delete    = 'Delete App';
$lang->sso->code      = 'Code';
$lang->sso->title     = 'Name';
$lang->sso->key       = 'Key';
$lang->sso->ip        = 'IP list';
$lang->sso->createKey = 'New one';

$lang->sso->confirmDelete = 'Are you sure to delete this App?';

$lang->sso->note = new stdClass();
$lang->sso->note->title = 'app name';
$lang->sso->note->code  = 'app code';
$lang->sso->note->ip    = "Use comma between two IPs, and support IP segment, for example 192.168.1.*";

$lang->sso->error = new stdClass();
$lang->sso->error->title = 'Please input name';
$lang->sso->error->code  = 'Please input code';
$lang->sso->error->key   = 'Please input key';
$lang->sso->error->ip    = 'Please input IP';

$lang->sso->instruction = <<<EOT
<p><strong>Example</strong>：Name is 'Test'", Code is 'test', Key is '20c8eb0d522d2e1a09d4ea18e4df3a59',IP list is "192.168.11.*,127.0.0.1"。</p>
<p><strong>1.User Auth</strong></p>
<p>Application request API of user auth, check if the account and password is correct, to realize single sign-on (SSO).</p>
<p>The API is from sso module, auth method. POST data is account and encrypted string [md5(md5(password) + key)]. Return user info(json) if success, return fail if fail.</p>
<p>Example: url is 'http:://www.demo.com/sso-auth-test'，POST string is 'account=admin&authcode=c44c577432230ad8e67160d3f9f0b91c'.</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;Note: 'test' is App code, 'c44c577432230ad8e67160d3f9f0b91' is md5(md5('123456') + '20c8eb0d522d2e1a09d4ea18e4df3a59')</p>
<p><strong>2.Get User List</strong></p>
<p>Application request API of user list, get all user info of zentao.</p>
<p>The API if from sso module, users method. POST data is key. Return user list (json) if success, return fail if fail.</p>
<p>Example:url is 'http:://www.demo.com/sso-users-test', POST string is 'key=20c8eb0d522d2e1a09d4ea18e4df3a59'.</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;Note: 'test' is App code, '20c8eb0d522d2e1a09d4ea18e4df3a59' is App key.</p>
<p><strong>3.Get Dept List</strong></p>
<p>Application request API of dept list, get all dept info of zentao.</p>
<p>The API if from sso module, depts method. POST data is key. Return dept list (json) if success, return fail if fail.</p>
<p>Example:url is 'http:://www.demo.com/sso-depts-test', POST string is 'key=20c8eb0d522d2e1a09d4ea18e4df3a59'.</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;Note: 'test' is App code, '20c8eb0d522d2e1a09d4ea18e4df3a59' is App key.</p>
EOT;
