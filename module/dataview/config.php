<?php
$config->dataview = new stdclass();
$config->dataview->create = new stdclass();
$config->dataview->create->requiredFields = 'name,code,group';

$config->dataview->edit = new stdclass();
$config->dataview->edit->requiredFields = 'name,group';

$config->dataview->groupChild['product'] = array('story' => 'story,storyestimate,storyreview,storyspec,storystage');
$config->dataview->groupChild['project'] = array('measure' => 'measqueue,measrecords,meastemplate', 'projectContent' => 'programactivity,programoutput,programprocess,programreport,projectcase,projectproduct,projectspec,projectstory', 'review' => 'review,reviewissue,reviewlist,reviewresult');
$config->dataview->groupChild['company'] = array('user' => 'user,usercontact,usergroup,userquery,usertpl,userview');

$config->dataview->multipleMappingFields = array();
$config->dataview->multipleMappingFields[] = 'testcase-stage';
