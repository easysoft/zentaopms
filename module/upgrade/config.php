<?php
$config->upgrade = new stdclass();
$config->upgrade->lowerTables = array();
$config->upgrade->lowerTables[$config->db->prefix . 'caseStep']       = $config->db->prefix . 'casestep';
$config->upgrade->lowerTables[$config->db->prefix . 'docLib']         = $config->db->prefix . 'doclib';
$config->upgrade->lowerTables[$config->db->prefix . 'groupPriv']      = $config->db->prefix . 'grouppriv';
$config->upgrade->lowerTables[$config->db->prefix . 'productPlan']    = $config->db->prefix . 'productplan';
$config->upgrade->lowerTables[$config->db->prefix . 'projectProduct'] = $config->db->prefix . 'projectproduct';
$config->upgrade->lowerTables[$config->db->prefix . 'projectStory']   = $config->db->prefix . 'projectstory';
$config->upgrade->lowerTables[$config->db->prefix . 'storySpec']      = $config->db->prefix . 'storyspec';
$config->upgrade->lowerTables[$config->db->prefix . 'taskEstimate']   = $config->db->prefix . 'taskestimate';
$config->upgrade->lowerTables[$config->db->prefix . 'testResult']     = $config->db->prefix . 'testresult';
$config->upgrade->lowerTables[$config->db->prefix . 'testRun']        = $config->db->prefix . 'testrun';
$config->upgrade->lowerTables[$config->db->prefix . 'testTask']       = $config->db->prefix . 'testtask';
$config->upgrade->lowerTables[$config->db->prefix . 'userContact']    = $config->db->prefix . 'usercontact';
$config->upgrade->lowerTables[$config->db->prefix . 'userGroup']      = $config->db->prefix . 'usergroup';
$config->upgrade->lowerTables[$config->db->prefix . 'userQuery']      = $config->db->prefix . 'userquery';
$config->upgrade->lowerTables[$config->db->prefix . 'userTPL']        = $config->db->prefix . 'usertpl';
