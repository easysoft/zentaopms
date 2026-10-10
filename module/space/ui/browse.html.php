<?php
declare(strict_types=1);
/**
 * The browse view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('spaceRepoMap', $this->session->spaceRepoMap ? json_decode($this->session->spaceRepoMap, true) : array());

$canCreate  = hasPriv('space', 'create');
$createLink = $this->createLink('space', 'create');
$createItem = array('text' => $lang->space->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');
$isGitfox   = $config->vision == 'gitfox' || $config->edition == 'gitfox';
$isJumpRepo = !$isGitfox && !empty($config->spaceLink) && $config->spaceLink == 'repo-browse';

featureBar
(
    div(searchToggle
    (
        set::module('spaceSearch'),
        set::open($type == 'bysearch')
    ))
);
toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

$tableData = initTableData($spaces, $config->space->dtable->fieldList, $this->space);
dtable
(
    set::id('spaces'),
    $isJumpRepo ? set::onRenderCell(jsRaw('window.renderCell')) : null,
    set::cols($config->space->dtable->fieldList),
    set::data($tableData),
    set::userMap($users),
    set::emptyTip($lang->space->notice->noSpaces),
    hasPriv('space', 'create') ? set::createLink($createLink) : null,
    hasPriv('space', 'create') ? set::createTip($lang->space->create) : null,
    set::footPager(usePager())
);
