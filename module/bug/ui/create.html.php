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

$fields->autoLoad('product',   array('items' => 'product,module,execution,project,story,task,assignedTo', 'updateOrders' => true))
       ->autoLoad('branch',    'module,execution,project,story,task,assignedTo')
       ->autoLoad('module',    'assignedTo,story')
       ->autoLoad('project',   'project,execution,story,task,assignedTo,injection,identify')
       ->autoLoad('execution', 'execution,story,task,assignedTo')
       ->autoLoad('allBuilds', 'openedBuild')
       ->autoLoad('allUsers',  'assignedTo')
       ->autoLoad('region',    'lane');

if(!$product->shadow) $fields->fullModeOrders('module,project,execution');

jsVar('bug',                   $bug);
jsVar('moduleID',              $bug->moduleID);
jsVar('methodName',            $app->methodName);
jsVar('tab',                   $this->app->tab);
jsVar('createRelease',         $lang->release->create);
jsVar('refresh',               $lang->refreshIcon);
jsVar('projectExecutionPairs', $projectExecutionPairs);

formGridPanel
(
    on::change('[name="product"], [name="branch"], [name="project"], [name="execution"]', 'loadBuilds'),
    set::title($lang->bug->create),
    set::fields($fields),
    set::loadUrl($loadUrl)
);
