<?php
/**
 * The config file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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

$config->block->params['task'] = clone $config->block->params['default'];
$config->block->params['task']->type['name']       = $lang->block->type;
$config->block->params['task']->type['options']    = $lang->block->typeList->task;
$config->block->params['task']->type['control']    = 'select';
$config->block->params['task']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['task']->orderBy['default'] = 'id_desc';
$config->block->params['task']->orderBy['options'] = $lang->block->orderByList->task;
$config->block->params['task']->orderBy['control'] = 'select';

$config->block->params['bug'] = clone $config->block->params['default'];
$config->block->params['bug']->type['name']       = $lang->block->type;
$config->block->params['bug']->type['options']    = $lang->block->typeList->bug;
$config->block->params['bug']->type['control']    = 'select';
$config->block->params['bug']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['bug']->orderBy['default'] = 'id_desc';
$config->block->params['bug']->orderBy['options'] = $lang->block->orderByList->bug;
$config->block->params['bug']->orderBy['control'] = 'select';

$config->block->params['case'] = clone $config->block->params['default'];
$config->block->params['case']->type['name']       = $lang->block->type;
$config->block->params['case']->type['options']    = $lang->block->typeList->case;
$config->block->params['case']->type['control']    = 'select';
$config->block->params['case']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['case']->orderBy['default'] = 'id_desc';
$config->block->params['case']->orderBy['options'] = $lang->block->orderByList->case;
$config->block->params['case']->orderBy['control'] = 'select';

$config->block->params['testtask'] = clone $config->block->params['default'];
$config->block->params['testtask']->type['name']       = $lang->block->type;
$config->block->params['testtask']->type['options']    = $lang->block->typeList->testtask;
$config->block->params['testtask']->type['control']    = 'select';

$config->block->params['story'] = clone $config->block->params['default'];
$config->block->params['story']->type['name']       = $lang->block->type;
$config->block->params['story']->type['options']    = $lang->block->typeList->story;
$config->block->params['story']->type['control']    = 'select';
$config->block->params['story']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['story']->orderBy['default'] = 'id_desc';
$config->block->params['story']->orderBy['options'] = $lang->block->orderByList->story;
$config->block->params['story']->orderBy['control'] = 'select';

$config->block->params['plan'] = clone $config->block->params['default'];

$config->block->params['release'] = clone $config->block->params['default'];

$config->block->params['project'] = clone $config->block->params['default'];
$config->block->params['project']->type['name']       = $lang->block->type;
$config->block->params['project']->type['options']    = $lang->block->typeList->projectAll;
$config->block->params['project']->type['control']    = 'select';
$config->block->params['project']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['project']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['project']->orderBy['control'] = 'select';

$config->block->params['projectTeam'] = clone $config->block->params['default'];
$config->block->params['projectTeam']->type['name']       = $lang->block->type;
$config->block->params['projectTeam']->type['options']    = $lang->block->typeList->projectAll;
$config->block->params['projectTeam']->type['control']    = 'select';
$config->block->params['projectTeam']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['projectTeam']->orderBy['options'] = $lang->block->orderByList->project;
$config->block->params['projectTeam']->orderBy['control'] = 'select';

$config->block->params['build'] = clone $config->block->params['default'];

$config->block->params['product'] = clone $config->block->params['default'];
$config->block->params['product']->type['name']    = $lang->block->type;
$config->block->params['product']->type['options'] = $lang->block->typeList->product;
$config->block->params['product']->type['control'] = 'select';

$config->block->params['productStatistic'] = clone $config->block->params['default'];
$config->block->params['productStatistic']->type['name']    = $lang->block->type;
$config->block->params['productStatistic']->type['options'] = $lang->block->typeList->product;
$config->block->params['productStatistic']->type['control'] = 'select';

$config->block->params['projectStatistic'] = clone $config->block->params['default'];
$config->block->params['projectStatistic']->type['name']    = $lang->block->type;
$config->block->params['projectStatistic']->type['options'] = $lang->block->typeList->project;
$config->block->params['projectStatistic']->type['control'] = 'select';

$config->block->params['executionStatistic'] = clone $config->block->params['default'];
$config->block->params['executionStatistic']->type['name']    = $lang->block->type;
$config->block->params['executionStatistic']->type['options'] = $lang->block->typeList->execution;
$config->block->params['executionStatistic']->type['control'] = 'select';

$config->block->params['qaStatistic'] = clone $config->block->params['default'];
$config->block->params['qaStatistic']->type['name']    = $lang->block->type;
$config->block->params['qaStatistic']->type['options'] = $lang->block->typeList->product;
$config->block->params['qaStatistic']->type['control'] = 'select';

$config->block->params['waterfallIssue'] = clone $config->block->params['default'];
$config->block->params['waterfallIssue']->type['name']       = $lang->block->type;
$config->block->params['waterfallIssue']->type['options']    = $lang->block->typeList->issue;
$config->block->params['waterfallIssue']->type['control']    = 'select';
$config->block->params['waterfallIssue']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['waterfallIssue']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['waterfallIssue']->orderBy['control'] = 'select';

$config->block->params['waterfallRisk'] = clone $config->block->params['default'];
$config->block->params['waterfallRisk']->type['name']       = $lang->block->type;
$config->block->params['waterfallRisk']->type['options']    = $lang->block->typeList->risk;
$config->block->params['waterfallRisk']->type['control']    = 'select';
$config->block->params['waterfallRisk']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['waterfallRisk']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['waterfallRisk']->orderBy['control'] = 'select';

$config->block->params['scrumIssue'] = clone $config->block->params['default'];
$config->block->params['scrumIssue']->type['name']       = $lang->block->type;
$config->block->params['scrumIssue']->type['options']    = $lang->block->typeList->issue;
$config->block->params['scrumIssue']->type['control']    = 'select';
$config->block->params['scrumIssue']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['scrumIssue']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['scrumIssue']->orderBy['control'] = 'select';

$config->block->params['scrumRisk'] = clone $config->block->params['default'];
$config->block->params['scrumRisk']->type['name']       = $lang->block->type;
$config->block->params['scrumRisk']->type['options']    = $lang->block->typeList->risk;
$config->block->params['scrumRisk']->type['control']    = 'select';
$config->block->params['scrumRisk']->orderBy['name']    = $lang->block->orderBy;
$config->block->params['scrumRisk']->orderBy['options'] = $lang->block->orderByList->product;
$config->block->params['scrumRisk']->orderBy['control'] = 'select';

$config->block->params['execution'] = clone $config->block->params['default'];
$config->block->params['execution']->type['name']       = $lang->block->type;
$config->block->params['execution']->type['options']    = $lang->block->typeList->execution;
$config->block->params['execution']->type['control']    = 'select';

$config->block->params['assignToMe'] = new stdclass();
$config->block->params['assignToMe']->todoCount['name']    = $this->lang->block->todoCount;
$config->block->params['assignToMe']->todoCount['default'] = 20;
$config->block->params['assignToMe']->todoCount['control'] = 'input';
$config->block->params['assignToMe']->taskCount['name']    = $this->lang->block->taskCount;
$config->block->params['assignToMe']->taskCount['default'] = 20;
$config->block->params['assignToMe']->taskCount['control'] = 'input';
$config->block->params['assignToMe']->bugCount['name']     = $this->lang->block->bugCount;
$config->block->params['assignToMe']->bugCount['default']  = 20;
$config->block->params['assignToMe']->bugCount['control']  = 'input';
if($config->edition == 'max')
{
    if(helper::hasFeature('risk'))
    {
        $config->block->params['assignToMe']->riskCount['name']    = $this->lang->block->riskCount;
        $config->block->params['assignToMe']->riskCount['default'] = 20;
        $config->block->params['assignToMe']->riskCount['control'] = 'input';
    }

    if(helper::hasFeature('issue'))
    {
        $config->block->params['assignToMe']->issueCount['name']    = $this->lang->block->issueCount;
        $config->block->params['assignToMe']->issueCount['default'] = 20;
        $config->block->params['assignToMe']->issueCount['control'] = 'input';
    }

    if(helper::hasFeature('meeting'))
    {
        $config->block->params['assignToMe']->meetingCount['name']    = $this->lang->block->meetingCount;
        $config->block->params['assignToMe']->meetingCount['default'] = 20;
        $config->block->params['assignToMe']->meetingCount['control'] = 'input';
    }

    $config->block->params['assignToMe']->feedbackCount['name']    = $this->lang->block->feedbackCount;
    $config->block->params['assignToMe']->feedbackCount['default'] = 20;
    $config->block->params['assignToMe']->feedbackCount['control'] = 'input';
}

$config->block->params['assignToMe']->storyCount['name']     = $this->lang->block->storyCount;
$config->block->params['assignToMe']->storyCount['default']  = 20;
$config->block->params['assignToMe']->storyCount['control']  = 'input';
$config->block->params['assignToMe']->reviewCount['name']    = $this->lang->block->reviewCount;
$config->block->params['assignToMe']->reviewCount['default'] = 20;
$config->block->params['assignToMe']->reviewCount['control'] = 'input';

$config->block->params['scrumTest'] = clone $config->block->params['default'];
$config->block->params['scrumTest']->type['name']    = $lang->block->type;
$config->block->params['scrumTest']->type['options'] = $lang->block->typeList->testtask;
$config->block->params['scrumTest']->type['control'] = 'select';

$config->block->params['scrumList'] = clone $config->block->params['default'];
$config->block->params['scrumList']->type['name']    = $lang->block->type;
$config->block->params['scrumList']->type['options'] = $lang->block->typeList->scrum;
$config->block->params['scrumList']->type['control'] = 'select';

$config->block->params['scrumProduct'] = clone $config->block->params['default'];

$config->block->params['projectDynamic'] = clone $config->block->params['default'];

$config->block->params['productDoc'] = clone $config->block->params['default'];

$config->block->params['projectDoc'] = clone $config->block->params['default'];

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

$config->block->form = new stdclass();
$config->block->form->create = array();
$config->block->form->create['module'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['code']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['title']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['grid']   = array('type' => 'int',    'required' => false, 'default' => '4');
$config->block->form->create['hidden'] = array('type' => 'int',    'required' => false, 'default' => '0');
$config->block->form->create['params'] = array('type' => 'array',  'required' => false, 'default' => array());

$config->block->form->edit = array();
$config->block->form->edit['module'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['code']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['title']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['grid']   = array('type' => 'int',    'required' => false, 'default' => '4');
$config->block->form->edit['params'] = array('type' => 'array',  'required' => false, 'default' => array());
