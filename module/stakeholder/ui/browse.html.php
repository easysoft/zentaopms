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
            setClass('active'),
            set::href($this->createLink('stakeholder', 'browse', "project=$projectID&browseType={$browseType}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
            set('data-app', $app->tab),
            $lang->stakeholder->browse
        )
    )
);

/* Toolbar. */
$canCreate      = hasPriv('stakeholder', 'create');
$canBatchCreate = hasPriv('stakeholder', 'batchcreate');

if($canCreate || $canBatchCreate)
{
    $createLink      = $this->createLink('stakeholder', 'create', "projectID=$projectID");
    $batchCreateLink = $this->createLink('stakeholder', 'batchCreate', "projectID=$projectID");

    $items = array();
    if($canCreate)      $items[] = array('text' => $lang->stakeholder->create,      'url' => $createLink, 'data-app' => 'project');
    if($canBatchCreate) $items[] = array('text' => $lang->stakeholder->batchCreate, 'url' => $batchCreateLink);

    toolbar
    (
        count($items) > 1 ? btngroup
        (
            btn(setClass('btn primary'), setData(array('app' => 'project')), set::icon('plus'), set::url($createLink), $lang->stakeholder->create),
            dropdown
            (
                btn(setClass('btn primary dropdown-toggle'),
                setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                set::items($items),
                set::placement('bottom-end')
            )
        ) : btn
        (
            setClass('btn primary'),
            set(reset($items))
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
    set::footPager(usePager()),
    set::createTip($lang->stakeholder->create),
    set::createLink(hasPriv('stakeholder', 'create') ? $createLink : ''),
    set::createAttr("data-app='project'")
);

render();
