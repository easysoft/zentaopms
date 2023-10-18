<?php
declare(strict_types=1);
/**
 * The browse view file of stakeholder module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     stakeholder
 * @link        https://www.zentao.net
 */

namespace zin;

/* Level 3 navigator. */
$navLinkParams = array('project' => $projectID);

$navItems = array();
$navItems[] = array('text' => $lang->overview,            'url' => createLink('project', 'view',           $navLinkParams));
$navItems[] = array('text' => $lang->productCommon,       'url' => createLink('project', 'manageProducts', $navLinkParams));
$navItems[] = array('text' => $lang->team->common,        'url' => createLink('project', 'team',           $navLinkParams));
$navItems[] = array('text' => $lang->stakeholder->common, 'url' => createLink('stakeholder', 'browse',     $navLinkParams));
$navItems[] = array('text' => $lang->priv,                'url' => createLink('project', 'group',          $navLinkParams));

mainNavbar(set::items($navItems));

/* Feature bar. */
featureBar
(
    li
    (
        set::className('nav-item'),
        a
        (
            set::href($this->createLink('stakeholder', 'browse', $navLinkParams)),
            set('data-app', $app->tab),
            $lang->stakeholder->browse
        )
    ),
    set::current('all')
);

/* Toolbar. */
$createLink      = $this->createLink('stakeholder', 'create', "projectID=$projectID");
$batchCreateLink = $this->createLink('stakeholder', 'batchCreate', "projectID=$projectID");
$createItem      = array('text' => $lang->stakeholder->create,      'url' => $createLink);
$batchCreateItem = array('text' => $lang->stakeholder->batchCreate, 'url' => $batchCreateLink);

if(common::hasPriv('stakeholder', 'batchcreate') and common::hasPriv('stakeholder', 'create'))
{
    toolbar
    (
        btngroup
        (
            btn(setClass('btn primary'), set::icon('plus'), set::url($createLink), $lang->stakeholder->create),
            dropdown
            (
                btn(setClass('btn primary dropdown-toggle'),
                setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                set::items(array_filter(array($createItem, $batchCreateItem))),
                set::placement('bottom-end'),
            )
        )
    );
}

/* DataTable. */
$cols = $this->loadModel('datatable')->getSetting('stakeholder');
$data = initTableData($stakeholders, $cols, $this->stakeholder);

dtable
(
    set::customCols(false),
    set::cols($cols),
    set::data($data),
    set::orderBy($orderBy),
    set::sortLink(createLink('stakeholder', 'browse', "projectID={$projectID}&browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager())
);

render();
