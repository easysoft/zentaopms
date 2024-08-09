#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/space.ui.class.php';
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
    'name'         => 'app' . time(),
    'url'          => 'http://10.0.7.242:9980',
    'token'        => 'y2UBqwPPzaLxsniy8R6A'
);

$url  = array('id' => 55);

r($tester->createApplication($url, $application)) && p('message,status') && e('创建应用成功,SUCCESS'); //验证创建GitLab应用

$application = array(
    'customName'   => 'zdoo' . time(),
    'customDomain' => randomString(),
    'appType'      => 'GitFox',
    'name'         => 'app' . time(),
    'url'          => 'http://10.0.0.51:3000',
    'token'        => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE3MTAzMTAzOTAsImlzcyI6IkdpdG5lc3MiLCJwaWQiOjMsInRrbiI6eyJ0eXAiOiJwYXQiLCJpZCI6M319.CS5l5r2UqtHVBOJo2F_yOwCJCg9qCaRDReeyfqcHurQ'
);
r($tester->createApplication($url, $application)) && p('message,status') && e('创建应用成功,SUCCESS'); //验证创建GitFox应用
