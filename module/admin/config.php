<?php
$config->url = new stdclass();
$config->url->community = 'http://www.zentao.net';
$config->url->ask       = 'http://www.zentao.net/ask-browse.html';
$config->url->document  = 'http://www.zentao.net/help-book-zentaopmshelp.html';
$config->url->feedback  = 'http://www.zentao.net/forum-board-1074.html';
$config->url->faq       = 'http://www.zentao.net/ask-faq.html';
$config->url->extension = 'http://www.zentao.net/extension-browse.html';
$config->url->donation  = 'http://www.zentao.net/help-donation.html';
$config->url->service   = 'http://www.cnezsoft.com/article-browse-1078.html';

$config->win2Unix = new stdclass();
$config->win2Unix->renameTables = array(
            'zt_casestep'        => 'zt_caseStep'       ,
            'zt_doclib'          => 'zt_docLib'         ,
            'zt_grouppriv'       => 'zt_groupPriv'      ,
            'zt_productplan'     => 'zt_productPlan'    ,
            'zt_projectproduct'  => 'zt_projectProduct' ,
            'zt_projectstory'    => 'zt_projectStory'   ,
            'zt_storyspec'       => 'zt_storySpec'      ,
            'zt_taskestimate'    => 'zt_taskEstimate'   ,
            'zt_testresult'      => 'zt_testResult'     ,
            'zt_testrun'         => 'zt_testRun'        ,
            'zt_testtask'        => 'zt_testTask'       ,
            'zt_usercontact'     => 'zt_userContact'    ,
            'zt_usergroup'       => 'zt_userGroup'      ,
            'zt_userquery'       => 'zt_userQuery'      ,
            'zt_usertpl'         => 'zt_userTPL'        
        );
