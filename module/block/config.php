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
$config->block->longBlock['']['guide']                  = 'guide';
$config->block->longBlock['']['welcome']                = 'welcome';
$config->block->longBlock['']['assigntome']             = 'assigntome';
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

$config->block->modules = array();
$config->block->modules['project']                               = new stdclass();
$config->block->modules['project']->moreLinkList                 = new stdclass();
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

$config->block->modules['product']                      = new stdclass();
$config->block->modules['product']->moreLinkList        = new stdclass();
$config->block->modules['product']->moreLinkList->list  = 'product|all|';
$config->block->modules['product']->moreLinkList->story = 'my|story|type=%s';

$config->block->modules['execution']                     = new stdclass();
$config->block->modules['execution']->moreLinkList       = new stdclass();
$config->block->modules['execution']->moreLinkList->list = 'execution|all|status=%s&executionID=';
$config->block->modules['execution']->moreLinkList->task = 'my|task|type=%s';

$config->block->modules['qa']                         = new stdclass();
$config->block->modules['qa']->moreLinkList           = new stdclass();
$config->block->modules['qa']->moreLinkList->bug      = 'my|bug|type=%s';
$config->block->modules['qa']->moreLinkList->case     = 'my|testcase|type=%s';
$config->block->modules['qa']->moreLinkList->testtask = 'testtask|browse|type=%s';

$config->block->modules['todo']                     = new stdclass();
$config->block->modules['todo']->moreLinkList       = new stdclass();
$config->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';

$config->block->modules['common']                        = new stdclass();
$config->block->modules['common']->moreLinkList          = new stdclass();
$config->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$config->block->modules['doc']                                = new stdclass();
$config->block->modules['doc']->moreLinkList                  = new stdclass();
$config->block->modules['doc']->moreLinkList->docmycollection = 'doc|myspace|type=collect&libID=0&moduleID=0&browseType=all&param=0&orderBy=editedDate_desc';
$config->block->modules['doc']->moreLinkList->productdoc      = 'doc|productspace|';
$config->block->modules['doc']->moreLinkList->projectdoc      = 'doc|projectspace|';
