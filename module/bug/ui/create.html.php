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

$fields = useFields('bug.create');

if(!empty($executionType) && $executionType == 'kanban') $fields->merge('bug.kanban');

jsVar('bug',                   $bug);
jsVar('moduleID',              $bug->moduleID);
jsVar('tab',                   $this->app->tab);
jsVar('createRelease',         $lang->release->create);
jsVar('refresh',               $lang->refreshIcon);
jsVar('projectExecutionPairs', $projectExecutionPairs);

$autoLoad = array();
$autoLoad['product']   = 'product,module,openedBuild,execution,project,story,task,assignedTo';
$autoLoad['branch']    = 'module,openedBuild,execution,project,story,task,assignedTo';
$autoLoad['module']    = 'assignedTo,story';
$autoLoad['project']   = 'openedBuild,execution,story,task,assignedTo';
$autoLoad['execution'] = 'openedBuild,story,task,assignedTo';
$autoLoad['region']    = 'lane';

formGridPanel
(
    set::title($lang->bug->create),
    set::fields($fields),
    set::loadUrl($loadUrl),
    set::autoLoad($autoLoad),
    on::click('#allBuilds',             'loadAllBuilds'),
    on::click('#allUsers',              'loadAllUsers'),
    on::click('#refreshExecutionBuild', 'refreshExecutionBuild'),
    on::click('#refreshProductBuild',   'refreshProductBuild'),
);
