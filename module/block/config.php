<?php
/**
 * The config file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$config->block = new stdclass();
$config->block->version = 2;
$config->block->editor  = new stdclass();
$config->block->editor->set = array('id' => 'html', 'tools' => 'simple');

$config->block->moduleIndex = array();
$config->block->moduleIndex['program'] = 'project';
$config->block->moduleIndex['project'] = 'execution';

$config->block->longBlock = array();
$config->block->longBlock['my']['guide']                = 'guide';
$config->block->longBlock['my']['welcome']              = 'welcome';
$config->block->longBlock['my']['assigntome']           = 'assigntome';
$config->block->longBlock['product']['statistic']       = 'statistic';
$config->block->longBlock['execution']['statistic']     = 'statistic';
$config->block->longBlock['qa']['statistic']            = 'statistic';
$config->block->longBlock['project']['waterfallreport'] = 'waterfallreport';
$config->block->longBlock['project']['waterfallissue']  = 'waterfallissue';
$config->block->longBlock['project']['waterfallrisk']   = 'waterfallrisk';

$config->block->shortBlock = array();
$config->block->shortBlock['product']['overview']          = 'overview';
$config->block->shortBlock['project']['overview']          = 'overview';
$config->block->shortBlock['project']['waterfallestimate'] = 'waterfallestimate';
$config->block->shortBlock['project']['waterfallprogress'] = 'waterfallprogress';
$config->block->shortBlock['']['contribute'] = 'contribute';

$config->statistic = new stdclass();
$config->statistic->storyStages = array('wait', 'planned', 'developing', 'testing', 'released');

$config->block->workMethods = 'task,story,requirement,bug,testcase,testtask,issue,risk,meeting';

global $lang;
$config->block->params['default'] = new stdclass();
$config->block->params['default']->count['name']    = $lang->block->count;
$config->block->params['default']->count['default'] = 20;
$config->block->params['default']->count['control'] = 'input';

$config->block->params['task'] = new stdclass();
$config->block->params['task']->type['name']       = $lang->block->type;
$config->block->params['task']->type['options']    = $lang->block->typeList->task;
$config->block->params['task']->type['control']    = 'picker';
$config->block->params['task']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['task']->orderBy['default'] = 'id_desc';
$config->block->params['task']->orderBy['options'] = $lang->block->orderByList->task;
$config->block->params['task']->orderBy['control'] = 'picker';
$config->block->params['task']->count = $config->block->params['default']->count;

$config->block->params['bug'] = new stdclass();
$config->block->params['bug']->type['name']       = $lang->block->type;
$config->block->params['bug']->type['options']    = $lang->block->typeList->bug;
$config->block->params['bug']->type['control']    = 'picker';
$config->block->params['bug']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['bug']->orderBy['default'] = 'id_desc';
$config->block->params['bug']->orderBy['options'] = $lang->block->orderByList->bug;
$config->block->params['bug']->orderBy['control'] = 'picker';
$config->block->params['bug']->count = $config->block->params['default']->count;

$config->block->params['case'] = new stdclass();
$config->block->params['case']->type['name']       = $lang->block->type;
$config->block->params['case']->type['options']    = $lang->block->typeList->case;
$config->block->params['case']->type['control']    = 'picker';
$config->block->params['case']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['case']->orderBy['default'] = 'id_desc';
$config->block->params['case']->orderBy['options'] = $lang->block->orderByList->case;
$config->block->params['case']->orderBy['control'] = 'picker';
$config->block->params['case']->count = $config->block->params['default']->count;

$config->block->params['testtask'] = new stdclass();
$config->block->params['testtask']->type['name']    = $lang->block->type;
$config->block->params['testtask']->type['options'] = $lang->block->typeList->testtask;
$config->block->params['testtask']->type['control'] = 'picker';
$config->block->params['testtask']->count = $config->block->params['default']->count;

$config->block->params['story'] = new stdclass();
$config->block->params['story']->type['name']       = $lang->block->type;
$config->block->params['story']->type['options']    = $lang->block->typeList->story;
$config->block->params['story']->type['control']    = 'picker';
$config->block->params['story']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['story']->orderBy['default'] = 'id_desc';
$config->block->params['story']->orderBy['options'] = $lang->block->orderByList->story;
$config->block->params['story']->orderBy['control'] = 'picker';
$config->block->params['story']->count = $config->block->params['default']->count;

$config->block->params['plan'] = $config->block->params['default'];

$config->block->params['release'] = $config->block->params['default'];

$config->block->params['project'] = new stdclass();
$config->block->params['project']->type['name']       = $lang->block->type;
$config->block->params['project']->type['options']    = $lang->block->typeList->projectAll;
$config->block->params['project']->type['control']    = 'picker';
$config->block->params['project']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['project']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['project']->orderBy['control'] = 'picker';
$config->block->params['project']->count = $config->block->params['default']->count;

$config->block->params['build'] = $config->block->params['default'];

$config->block->params['product'] = new stdclass();
$config->block->params['product']->type['name']    = $lang->block->type;
$config->block->params['product']->type['options'] = $lang->block->typeList->product;
$config->block->params['product']->type['control'] = 'picker';
$config->block->params['product']->count           = $config->block->params['default']->count;

$config->block->params['productstatistic'] = new stdclass();
$config->block->params['productstatistic']->type['name']    = $lang->block->type;
$config->block->params['productstatistic']->type['options'] = $lang->block->typeList->product;
$config->block->params['productstatistic']->type['control'] = 'picker';
$config->block->params['productstatistic']->count = $config->block->params['default']->count;

$config->block->params['projectstatistic'] = new stdclass();
$config->block->params['projectstatistic']->type['name']    = $lang->block->type;
$config->block->params['projectstatistic']->type['options'] = $lang->block->typeList->project;
$config->block->params['projectstatistic']->type['control'] = 'picker';
$config->block->params['projectstatistic']->count = $config->block->params['default']->count;

$config->block->params['bugstatistic'] = new stdclass();
$config->block->params['bugstatistic']->type['name']    = $lang->block->type;
$config->block->params['bugstatistic']->type['options'] = $lang->block->typeList->product;
$config->block->params['bugstatistic']->type['control'] = 'picker';
$config->block->params['bugstatistic']->count = $config->block->params['default']->count;

$config->block->params['executionstatistic'] = new stdclass();
$config->block->params['executionstatistic']->type['name']    = $lang->block->type;
$config->block->params['executionstatistic']->type['options'] = $lang->block->typeList->execution;
$config->block->params['executionstatistic']->type['control'] = 'picker';
$config->block->params['executionstatistic']->count = $config->block->params['default']->count;

$config->block->params['qastatistic'] = new stdclass();
$config->block->params['qastatistic']->type['name']    = $lang->block->type;
$config->block->params['qastatistic']->type['options'] = $lang->block->typeList->product;
$config->block->params['qastatistic']->type['control'] = 'picker';
$config->block->params['qastatistic']->count = $config->block->params['default']->count;

$config->block->params['waterfallissue'] = new stdclass();
$config->block->params['waterfallissue']->type['name']       = $lang->block->type;
$config->block->params['waterfallissue']->type['options']    = $lang->block->typeList->issue;
$config->block->params['waterfallissue']->type['control']    = 'picker';
$config->block->params['waterfallissue']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['waterfallissue']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['waterfallissue']->orderBy['control'] = 'picker';
$config->block->params['waterfallissue']->count = $config->block->params['default']->count;

$config->block->params['waterfallrisk'] = new stdclass();
$config->block->params['waterfallrisk']->type['name']       = $lang->block->type;
$config->block->params['waterfallrisk']->type['options']    = $lang->block->typeList->risk;
$config->block->params['waterfallrisk']->type['control']    = 'picker';
$config->block->params['waterfallrisk']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['waterfallrisk']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['waterfallrisk']->orderBy['control'] = 'picker';
$config->block->params['waterfallrisk']->count = $config->block->params['default']->count;

$config->block->params['scrumissue'] = new stdclass();
$config->block->params['scrumissue']->type['name']       = $lang->block->type;
$config->block->params['scrumissue']->type['options']    = $lang->block->typeList->issue;
$config->block->params['scrumissue']->type['control']    = 'picker';
$config->block->params['scrumissue']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['scrumissue']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['scrumissue']->orderBy['control'] = 'picker';
$config->block->params['scrumissue']->count = $config->block->params['default']->count;

$config->block->params['scrumrisk'] = new stdclass();
$config->block->params['scrumrisk']->type['name']       = $lang->block->type;
$config->block->params['scrumrisk']->type['options']    = $lang->block->typeList->risk;
$config->block->params['scrumrisk']->type['control']    = 'picker';
$config->block->params['scrumrisk']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['scrumrisk']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['scrumrisk']->orderBy['control'] = 'picker';
$config->block->params['scrumrisk']->count = $config->block->params['default']->count;

$config->block->params['execution'] = new stdclass();
$config->block->params['execution']->type['name']    = $lang->block->type;
$config->block->params['execution']->type['options'] = $lang->block->typeList->execution;
$config->block->params['execution']->type['control'] = 'picker';
$config->block->params['execution']->count = $config->block->params['default']->count;

$config->block->params['assigntome'] = new stdclass();
$config->block->params['assigntome']->count = $config->block->params['default']->count;

$config->block->params['scrumtest'] = new stdclass();
$config->block->params['scrumtest']->type['name']    = $lang->block->type;
$config->block->params['scrumtest']->type['options'] = $lang->block->typeList->testtask;
$config->block->params['scrumtest']->type['control'] = 'picker';
$config->block->params['scrumtest']->count = $config->block->params['default']->count;

$config->block->params['scrumlist'] = new stdclass();
$config->block->params['scrumlist']->type['name']    = $lang->block->type;
$config->block->params['scrumlist']->type['options'] = $lang->block->typeList->scrum;
$config->block->params['scrumlist']->type['control'] = 'picker';
$config->block->params['scrumlist']->count = $config->block->params['default']->count;

$config->block->params['scrumProduct'] = $config->block->params['default']->count;

$config->block->params['projectDynamic'] = $config->block->params['default']->count;

$config->block->params['productDoc'] = $config->block->params['default']->count;

$config->block->params['projectDoc'] = $config->block->params['default']->count;

$config->block->params['singlestory']   = $config->block->params['story'];
$config->block->params['singlerelease'] = $config->block->params['release'];
$config->block->params['singleplan']    = $config->block->params['plan'];
unset($config->block->params['singlestory']->type['options']['reviewedBy']);

$config->block->modules['project'] = new stdclass();
$config->block->modules['project']->moreLinkList = new stdclass();
$config->block->modules['project']->moreLinkList->recentproject  = 'project|browse|';
$config->block->modules['project']->moreLinkList->statistic      = 'project|browse|';
$config->block->modules['project']->moreLinkList->project        = 'project|browse|';
$config->block->modules['project']->moreLinkList->cmmireport     = 'weekly|index|';
$config->block->modules['project']->moreLinkList->cmmiestimate   = 'workestimation|index|';
$config->block->modules['project']->moreLinkList->cmmiissue      = 'issue|browse|';
$config->block->modules['project']->moreLinkList->cmmirisk       = 'risk|browse|';
$config->block->modules['project']->moreLinkList->scrumlist      = 'project|execution|';
$config->block->modules['project']->moreLinkList->scrumtest      = 'project|testtask|';
$config->block->modules['project']->moreLinkList->scrumproduct   = 'product|all|';
$config->block->modules['project']->moreLinkList->sprint         = 'project|execution|';
$config->block->modules['project']->moreLinkList->projectdynamic = 'project|dynamic|';

$config->block->modules['scrumproject'] = new stdclass();
$config->block->modules['scrumproject']->moreLinkList = new stdclass();
$config->block->modules['scrumproject']->moreLinkList->sprint    = 'project|execution|';
$config->block->modules['scrumproject']->moreLinkList->scrumlist = 'project|execution|';
$config->block->modules['scrumproject']->moreLinkList->scrumtest = 'project|testtask|';

$config->block->modules['product'] = new stdclass();
$config->block->modules['product']->moreLinkList = new stdclass();
$config->block->modules['product']->moreLinkList->list  = 'product|all|';
$config->block->modules['product']->moreLinkList->story = 'my|story|type=%s';

$config->block->modules['execution'] = new stdclass();
$config->block->modules['execution']->moreLinkList = new stdclass();
$config->block->modules['execution']->moreLinkList->list = 'execution|all|status=%s&executionID=';
$config->block->modules['execution']->moreLinkList->task = 'my|task|type=%s';

$config->block->modules['qa'] = new stdclass();
$config->block->modules['qa']->moreLinkList = new stdclass();
$config->block->modules['qa']->moreLinkList->bug      = 'my|bug|type=%s';
$config->block->modules['qa']->moreLinkList->case     = 'my|testcase|type=%s';
$config->block->modules['qa']->moreLinkList->testtask = 'testtask|browse|type=%s';

$config->block->modules['todo'] = new stdclass();
$config->block->modules['todo']->moreLinkList = new stdclass();
$config->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';

$config->block->modules['common'] = new stdclass();
$config->block->modules['common']->moreLinkList = new stdclass();
$config->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$config->block->modules['doc'] = new stdclass();
$config->block->modules['doc']->moreLinkList = new stdclass();
$config->block->modules['doc']->moreLinkList->docmycollection = 'doc|myspace|type=collect&libID=0&moduleID=0&browseType=all&param=0&orderBy=editedDate_desc';
$config->block->modules['doc']->moreLinkList->productdoc      = 'doc|productspace|';
$config->block->modules['doc']->moreLinkList->projectdoc      = 'doc|projectspace|';

$config->block->defaultSize = array(1 => 3);

$config->block->size = array();
$config->block->size['product']['list']             = array(2 => 6, 1 => 6);
$config->block->size['product']['story']            = array(2 => 6, 1 => 6);
$config->block->size['product']['plan']             = array(2 => 6, 1 => 6);
$config->block->size['product']['bug']              = array(2 => 6, 1 => 6);
$config->block->size['product']['release']          = array(2 => 6, 1 => 6);
$config->block->size['product']['statistic']        = array(2 => 5, 1 => 11);
$config->block->size['product']['bugstatistic']     = array(2 => 5, 1 => 8);
$config->block->size['product']['monthlyprogress']  = array(2 => 5, 1 => 13);
$config->block->size['product']['annualworkload']   = array(2 => 6, 1 => 13);
$config->block->size['product']['overview']         = array(1 => 3, 3 => 3);
$config->block->size['product']['releasestatistic'] = array(1 => 8, 2 => 5);

$config->block->size['project']['overview']      = array(1 => 3);
$config->block->size['project']['statistic']     = array(2 => 5, 1 => 8);
$config->block->size['project']['recentproject'] = array(2 => 4, 1 => 9);
$config->block->size['project']['project']       = array(2 => 6, 1 => 6);

$config->block->size['scrumproject']['scrumoverview']  = array(2 => 5, 1 => 8);
$config->block->size['scrumproject']['scrumlist']      = array(2 => 6, 1 => 6);
$config->block->size['scrumproject']['scrumtest']      = array(2 => 6, 1 => 6);
$config->block->size['scrumproject']['sprint']         = array(1 => 3);
$config->block->size['scrumproject']['projectdynamic'] = array(1 => 8, 2 => 8);

$config->block->size['waterfallproject']['waterfallgantt'] = array(2 => 5, 1 => 8);
$config->block->size['waterfallproject']['projectdynamic'] = array(1 => 8, 2 => 8);

$config->block->size['execution']['overview']  = array(1 => 3);
$config->block->size['execution']['statistic'] = array(2 => 5, 1 => 8);
$config->block->size['execution']['list']      = array(2 => 6, 1 => 6);
$config->block->size['execution']['task']      = array(2 => 6, 1 => 6);
$config->block->size['execution']['build']     = array(2 => 6, 1 => 6);

$config->block->size['qa']['statistic'] = array(2 => 5, 1 => 8);
$config->block->size['qa']['testtask']  = array(2 => 6, 1 => 6);
$config->block->size['qa']['bug']       = array(2 => 6, 1 => 6);
$config->block->size['qa']['case']      = array(2 => 6, 1 => 6);

$config->block->size['doc']['docstatistic']    = array(2 => 3, 1 => 3);
$config->block->size['doc']['docmycollection'] = array(2 => 4, 1 => 4);
$config->block->size['doc']['docrecentupdate'] = array(2 => 4, 1 => 4);
$config->block->size['doc']['productdoc']      = array(2 => 6, 1 => 6);
$config->block->size['doc']['projectdoc']      = array(2 => 6, 1 => 6);
$config->block->size['doc']['docdynamic']      = array(1 => 8);
$config->block->size['doc']['docviewlist']     = array(1 => 4);
$config->block->size['doc']['doccollectlist']  = array(1 => 4);

$config->block->size['welcome']['welcome']                 = array(2 => 3);
$config->block->size['guide']['guide']                     = array(2 => 6);
$config->block->size['assigntome']['assigntome']           = array(2 => 6, 1 => 6);
$config->block->size['dynamic']['dynamic']                 = array(1 => 8);
$config->block->size['zentaodynamic']['zentaodynamic']     = array(1 => 4);
$config->block->size['teamachievement']['teamachievement'] = array(1 => 6);

$config->block->size['singleproduct']['singlestatistic']       = array(2 => 5, 1 => 11);
$config->block->size['singleproduct']['singlebugstatistic']    = array(2 => 5, 1 => 8);
$config->block->size['singleproduct']['roadmap']               = array(2 => 6, 1 => 6);
$config->block->size['singleproduct']['singlestory']           = array(2 => 6, 1 => 6);
$config->block->size['singleproduct']['singleplan']            = array(2 => 6, 1 => 6);
$config->block->size['singleproduct']['singlerelease']         = array(2 => 6, 1 => 6);
$config->block->size['singleproduct']['singledynamic']         = array(1 => 8);
$config->block->size['singleproduct']['singlemonthlyprogress'] = array(3 => 5, 1 => 15);
