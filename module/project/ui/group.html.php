<?php
declare(strict_types=1);
/**
 * The browse view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('confirmDelete', $lang->group->confirmDelete);

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::linkParams("projectID={$projectID}&programID={$programID}"),
    set::current('all')
);

/* zin: Define the toolbar on main menu. */
toolbar
(
    item(set(array
    (
        'icon'        => 'plus',
        'text'        => $lang->group->create,
        'class'       => "primary create-project-btn",
        'url'         => $this->createLink('project', 'createGroup', "projectID={$projectID}"),
        'data-toggle' => 'modal'
    )))
);

$groups = initTableData($groups, $config->projectGroup->dtable->fieldList, $this->project);

/* zin: Define the dtable in main content. */
dtable
(
    set::fixedLeftWidth('30%'),
    set::cols(array_values($config->projectGroup->dtable->fieldList)),
    set::data($groups),
    set::emptyTip($lang->group->noGroup),
    set::createTip($lang->group->create),
    set::createLink(hasPriv('project', 'createGroup') ? createLink('project', 'createGroup', "projectID={$projectID}") : ''),
    set::createAttr("data-toggle='modal'")
);

render();
