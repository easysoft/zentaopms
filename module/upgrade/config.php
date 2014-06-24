<?php
$config->upgrade = new stdclass();
$config->upgrade->lowerTables = array();
$config->upgrade->lowerTables['zt_caseStep']       = 'zt_casestep';
$config->upgrade->lowerTables['zt_docLib']         = 'zt_doclib';
$config->upgrade->lowerTables['zt_groupPriv']      = 'zt_grouppriv';
$config->upgrade->lowerTables['zt_productPlan']    = 'zt_productplan';
$config->upgrade->lowerTables['zt_projectProduct'] = 'zt_projectproduct';
$config->upgrade->lowerTables['zt_projectStory']   = 'zt_projectstory';
$config->upgrade->lowerTables['zt_storySpec']      = 'zt_storyspec';
$config->upgrade->lowerTables['zt_taskEstimate']   = 'zt_taskestimate';
$config->upgrade->lowerTables['zt_testResult']     = 'zt_testresult';
$config->upgrade->lowerTables['zt_testRun']        = 'zt_testrun';
$config->upgrade->lowerTables['zt_testTask']       = 'zt_testtask';
$config->upgrade->lowerTables['zt_userContact']    = 'zt_usercontact';
$config->upgrade->lowerTables['zt_userGroup']      = 'zt_usergroup';
$config->upgrade->lowerTables['zt_userQuery']      = 'zt_userquery';
$config->upgrade->lowerTables['zt_userTPL']        = 'zt_usertpl';
