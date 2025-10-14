#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/space.ui.class.php';
$tester = new space();
function randomString($length = 4)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $characterLength = strlen($characters);
    $randomString = '';
    for($i = 0; $i < $length; $i++)
    {
        $randomString .= $characters[random_int(0, $characterLength - 1)];
    }
    return $randomString;
}

$application = array(
    'customName'   => 'zdoo' . time(),
    'customDomain' => randomString(),
    'appType'      => 'GitLab',
    'name'         => 'GitLab' . time(),
    'url'          => getenv('GITLAB_URL'),
    'token'        => getenv('GITLAB_TOKEN')
);

$url  = array('id' => 55);

r($tester->createApplication($url, $application)) && p('message,status') && e('创建应用成功,SUCCESS'); //验证创建GitLab应用

$application = array(
    'customName'   => 'zdoo' . time(),
    'customDomain' => randomString(),
    'appType'      => 'GitFox',
    'name'         => 'GitFox' . time(),
    'url'          => getenv('GITFOX_URL'),
    'token'        => getenv('GITFOX_TOKEN')
);
r($tester->createApplication($url, $application)) && p('message,status') && e('创建应用成功,SUCCESS'); //验证创建GitFox应用
