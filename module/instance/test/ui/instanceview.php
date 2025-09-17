<?php
chdir(__DIR__);
include '../lib/ui/instance.ui.class.php';
$tester = new instance();
$instance = zenData('pipeline')->loadYaml('pipeline')->gen(7);
$type = 'GitFox';
r($tester->instanceView($type)) && p('message,status') && e('检查GitFox应用成功,SUCCESS'); //检查GitFox应用页面是否可以正常展示

$type = 'GitLab';
r($tester->instanceView($type)) && p('message,status') && e('检查GitLab应用成功,SUCCESS'); //检查GitLab应用页面是否可以正常展示

$type = 'Gogs';
r($tester->instanceView($type)) && p('message,status') && e('检查Gogs应用成功,SUCCESS'); //检查Gogs应用页面是否可以正常展示

$type = 'Gitea';
r($tester->instanceView($type)) && p('message,status') && e('检查Gitea应用成功,SUCCESS'); //检查Gitea应用页面是否可以正常展示

$type = 'Jenkins';
r($tester->instanceView($type)) && p('message,status') && e('检查Jenkins应用成功,SUCCESS'); //检查Jenkins应用页面是否可以正常展示

$type = 'SonarQube';
r($tester->instanceView($type)) && p('message,status') && e('检查SonarQube应用成功,SUCCESS'); //检查SonarQube应用页面是否可以正常展示

$type = 'Nexus';
r($tester->instanceView($type)) && p('message,status') && e('检查Nexus应用成功,SUCCESS'); //检查Nexus应用页面是否可以正常展示
