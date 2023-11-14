<?php
$config->dataview = new stdclass();
$config->dataview->create = new stdclass();
$config->dataview->create->requiredFields = 'name,code,group';

$config->dataview->edit = new stdclass();
$config->dataview->edit->requiredFields = 'name,group';

$config->dataview->groupChild['product'] = array('story' => 'story,storyestimate,storyreview,storyspec,storystage');
$config->dataview->groupChild['project'] = array('measure' => 'measqueue,measrecords,meastemplate', 'projectContent' => 'programactivity,programoutput,programprocess,programreport,projectcase,projectproduct,projectspec,projectstory', 'review' => 'review,reviewissue,reviewlist,reviewresult');
$config->dataview->groupChild['company'] = array('user' => 'user,usercontact,usergroup,userquery,usertpl,userview');

$config->dataview->columnTypes = new stdclass();
$config->dataview->columnTypes->TINY       = 'number';
$config->dataview->columnTypes->SHORT      = 'number';
$config->dataview->columnTypes->LONG       = 'number';
$config->dataview->columnTypes->FLOAT      = 'number';
$config->dataview->columnTypes->DOUBLE     = 'number';
$config->dataview->columnTypes->TIMESTAMP  = 'string';
$config->dataview->columnTypes->LONGLONG   = 'string';
$config->dataview->columnTypes->INT24      = 'number';
$config->dataview->columnTypes->DATE       = 'date';
$config->dataview->columnTypes->TIME       = 'string';
$config->dataview->columnTypes->DATETIME   = 'date';
$config->dataview->columnTypes->YEAR       = 'date';
$config->dataview->columnTypes->ENUM       = 'string';
$config->dataview->columnTypes->SET        = 'string';
$config->dataview->columnTypes->TINYBLOB   = 'string';
$config->dataview->columnTypes->MEDIUMBLOB = 'string';
$config->dataview->columnTypes->LONG_BLOB  = 'string';
$config->dataview->columnTypes->BLOB       = 'string';
$config->dataview->columnTypes->VAR_STRING = 'string';
$config->dataview->columnTypes->STRING     = 'string';
$config->dataview->columnTypes->NULL       = 'null';
$config->dataview->columnTypes->NEWDATE    = 'date';
$config->dataview->columnTypes->INTERVAL   = 'string';
$config->dataview->columnTypes->GEOMETRY   = 'string';
$config->dataview->columnTypes->NEWDECIMAL = 'number';

/* Dameng native_type. */
$config->dataview->columnTypes->int       = 'number';
$config->dataview->columnTypes->varchar   = 'string';
$config->dataview->columnTypes->text      = 'string';
$config->dataview->columnTypes->timestamp = 'string';
$config->dataview->columnTypes->date      = 'date';
$config->dataview->columnTypes->time      = 'string';
$config->dataview->columnTypes->double    = 'number';
$config->dataview->columnTypes->number    = 'number';
$config->dataview->columnTypes->bigint    = 'number';
