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

include './create.item.php';

jsVar('bug',                   $bug);
jsVar('moduleID',              $bug->moduleID);
jsVar('tab',                   $this->app->tab);
jsVar('createRelease',         $lang->release->create);
jsVar('refresh',               $lang->refreshIcon);
jsVar('projectExecutionPairs', $projectExecutionPairs);

formGridPanel
(
    set::loadUrl($loadUrl),
    on::change('[name="product"]',   'loadForm({target: target, items: "product,module,openedBuild,execution,project,story,task,assignedTo"})'),
    on::change('[name="branch"]',    'loadForm({target: target, items: "module,openedBuild,execution,project,story,task,assignedTo"})'),
    on::change('[name="module"]',    'loadForm({target: target, items: "assignedTo,story"})'),
    on::change('[name="project"]',   'loadForm({target: target, items: "openedBuild,execution,story,task,assignedTo"})'),
    on::change('[name="execution"]', 'loadForm({target: target, items: "openedBuild,story,task,assignedTo"})'),
    on::change('[name="region"]',    'loadForm({target: target, items: "lane"})'),
    on::click('#allBuilds',             'loadAllBuilds'),
    on::click('#allUsers',              'loadAllUsers'),
    on::click('#refreshExecutionBuild', 'refreshExecutionBuild'),
    on::click('#refreshProductBuild',   'refreshProductBuild'),
    set::title($lang->bug->create),
    set::items($items)
);
