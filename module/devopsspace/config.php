<?php
global $lang;
$config->devopsspace->actionList = array();
$config->devopsspace->actionList['repo']['icon']     = 'code';
$config->devopsspace->actionList['repo']['text']     = $lang->devopsspace->repo;
$config->devopsspace->actionList['repo']['hint']     = $lang->devopsspace->repo;
$config->devopsspace->actionList['repo']['showText'] = true;
$config->devopsspace->actionList['repo']['url']      = array('module' => 'repo', 'method' => 'maintain', 'params' => 'space={id}');

$config->devopsspace->actionList['artifactrepo']['icon']     = 'stack';
$config->devopsspace->actionList['artifactrepo']['text']     = $lang->devopsspace->artifactrepo;
$config->devopsspace->actionList['artifactrepo']['hint']     = $lang->devopsspace->artifactrepo;
$config->devopsspace->actionList['artifactrepo']['showText'] = true;
$config->devopsspace->actionList['artifactrepo']['url']      = array('module' => 'artifactrepo', 'method' => 'browse', 'params' => 'id={id}');
