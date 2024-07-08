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

$config->dataview->fieldTypes = new stdclass();
$config->dataview->fieldTypes->TINY       = 'number';
$config->dataview->fieldTypes->SHORT      = 'number';
$config->dataview->fieldTypes->LONG       = 'number';
$config->dataview->fieldTypes->FLOAT      = 'number';
$config->dataview->fieldTypes->DOUBLE     = 'number';
$config->dataview->fieldTypes->TIMESTAMP  = 'string';
$config->dataview->fieldTypes->LONGLONG   = 'string';
$config->dataview->fieldTypes->INT24      = 'number';
$config->dataview->fieldTypes->DATE       = 'date';
$config->dataview->fieldTypes->TIME       = 'string';
$config->dataview->fieldTypes->DATETIME   = 'date';
$config->dataview->fieldTypes->YEAR       = 'date';
$config->dataview->fieldTypes->ENUM       = 'string';
$config->dataview->fieldTypes->SET        = 'string';
$config->dataview->fieldTypes->TINYBLOB   = 'string';
$config->dataview->fieldTypes->MEDIUMBLOB = 'string';
