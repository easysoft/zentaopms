<?php
declare(strict_types=1);
/**
 * The create file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

$fields = useFields('bug.create');
if(!empty($executionType) && $executionType == 'kanban') $fields->merge('bug.kanban');

$fields->autoLoad('product',   array('items' => 'product,module,assignedTo,story,execution,task,openedBuild,project,injection,identify,plan,case,' . (!empty($lang->bug->flowExtraFields) ? implode(',', $lang->bug->flowExtraFields) : ''), 'updateOrders' => true))
       ->autoLoad('branch',    'module,execution,project,story,task,assignedTo,plan')
       ->autoLoad('module',    'assignedTo,story')
       ->autoLoad('project',   'project,execution,story,task,assignedTo,injection,identify,openedBuild')
       ->autoLoad('execution', 'execution,story,task,assignedTo,openedBuild')
       ->autoLoad('allBuilds', 'openedBuild')
       ->autoLoad('allUsers',  'assignedTo')
       ->autoLoad('region',    'lane');

if(!$product->shadow) $fields->fullModeOrders('module,project,execution,plan', 'pri,title');
$fields->sort('execution,plan');

jsVar('bug',                   $bug);
jsVar('moduleID',              $bug->moduleID);
jsVar('methodName',            $app->methodName);
jsVar('projectID',             isset($projectID)   ? $projectID   : 0);
jsVar('executionID',           isset($executionID) ? $executionID : 0);
jsVar('copyBugID',             $copyBugID);
jsVar('tab',                   $this->app->tab);
jsVar('createRelease',         $lang->release->create);
jsVar('refresh',               $lang->refreshIcon);
jsVar('projectExecutionPairs', $projectExecutionPairs);

formGridPanel
(
    set::title($lang->bug->create),
    set::fields($fields),
    set::loadUrl($loadUrl)
);
