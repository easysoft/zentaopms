<?php
$config->custom = new stdClass();
$config->custom->canAdd['story']    = 'reasonList,sourceList,priList';
$config->custom->canAdd['task']     = 'priList,typeList,reasonList';
$config->custom->canAdd['bug']      = 'priList,severityList,osList,browserList,typeList,resolutionList';
$config->custom->canAdd['testcase'] = 'priList,typeList,stageList,resultList,statusList';
$config->custom->canAdd['testtask'] = 'priList';
$config->custom->canAdd['todo']     = 'priList,typeList';
$config->custom->canAdd['user']     = 'roleList';
$config->custom->canAdd['block']    = '';

$config->custom->requiredModules[15] = 'product';
$config->custom->requiredModules[20] = 'story';
$config->custom->requiredModules[25] = 'productplan';
$config->custom->requiredModules[30] = 'release';

$config->custom->requiredModules[35] = 'project';
$config->custom->requiredModules[40] = 'task';
$config->custom->requiredModules[45] = 'build';

$config->custom->requiredModules[50] = 'bug';
$config->custom->requiredModules[55] = 'testcase';
$config->custom->requiredModules[60] = 'testsuite';
$config->custom->requiredModules[65] = 'testreport';
$config->custom->requiredModules[70] = 'testtask';

$config->custom->requiredModules[75] = 'doc';

$config->custom->requiredModules[85] = 'user';

$config->custom->fieldList['product']['create']      = 'name,code,line,PO,QD,RD,type,desc';
$config->custom->fieldList['product']['edit']        = 'name,code,line,PO,QD,RD,type,desc,status';
$config->custom->fieldList['story']['create']        = 'product,plan,source,sourceNote,title,pri,estimate,mailto,keywords,spec,verify';
$config->custom->fieldList['story']['change']        = 'title,spec,verify,comment';
$config->custom->fieldList['story']['close']         = 'closedReason,comment';
$config->custom->fieldList['story']['review']        = 'reviewedDate,assignedTo,reviewedBy,comment';
$config->custom->fieldList['productplan']            = 'title,begin,end,desc';
$config->custom->fieldList['release']                = 'name,build,date,desc';
$config->custom->fieldList['project']['create']      = 'name,code,begin,end,days,type,desc';
$config->custom->fieldList['project']['edit']        = 'name,code,begin,end,days,type,desc,PO,PM,QD,RD';
$config->custom->fieldList['task']['create']         = 'type,story,name,pri,estimate,desc,estStarted,deadline,mailto';
$config->custom->fieldList['task']['edit']           = 'type,assignedTo,story,name,pri,estimate,desc,estStarted,deadline,mailto';
$config->custom->fieldList['task']['finish']         = 'consumed,finishedDate,comment';
$config->custom->fieldList['task']['activate']       = 'assignedTo,left,comment';
$config->custom->fieldList['build']                  = 'product,name,builder,date,scmPath,filePath,desc';
$config->custom->fieldList['bug']['create']          = 'product,project,openedBuild,assignedTo,deadline,type,os,browser,title,severity,pri,steps,story,task,mailto,keywords';
$config->custom->fieldList['bug']['edit']            = 'product,plan,project,openedBuild,assignedTo,deadline,type,os,browser,title,severity,pri,steps,story,task,mailto,keywords';
$config->custom->fieldList['bug']['resolve']         = 'resolution,resolvedBuild,resolvedDate,assignedTo,comment';
$config->custom->fieldList['testcase']['create']     = 'product,type,stage,story,title,pri,precondition,keywords';
$config->custom->fieldList['testcase']['edit']       = 'product,type,stage,story,title,pri,precondition,keywords,status';
$config->custom->fieldList['testsuite']              = 'name,desc';
$config->custom->fieldList['testcase']['createcase'] = 'lib,type,stage,title,pri,precondition,keywords';
$config->custom->fieldList['testreport']             = 'begin,end,owner,members,title,report';
$config->custom->fieldList['testtask']               = 'project,build,,owner,pri,begin,end,status,name,desc';
$config->custom->fieldList['doc']                    = 'title,keywords,content';
$config->custom->fieldList['user']['create']         = 'dept,account,realname,password,password1,password2,role,email,commiter';
$config->custom->fieldList['user']['edit']           = 'dept,account,realname,role,email,commiter,skype,qq,mobile,phone,address,zipcode,wangwang,gtalk';
