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
$isJumpRepo = !empty($config->spaceLink) && $config->spaceLink == 'repo-browse';

featureBar();
toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

if(empty($spaces))
{
    div
    (
        setClass('w-full dtable-empty-tip text-center bg-white'),
        div
        (
            setClass('text-gray'),
            $lang->space->notice->noSpaces,
            hasPriv('space', 'create') ?
            btn
            (
                set(array('text' => $lang->space->create, 'url' => inLink('create'), 'class' => 'ml-2 primary-pale border-primary', 'icon'  => 'plus'))
            ) : null
        ),
    );
    return;
}

$tableData = initTableData($spaces, $config->space->dtable->fieldList, $this->space);
dtable
(
    $isJumpRepo ? set::onRenderCell(jsRaw('window.renderCell')) : null,
    set::cols($config->space->dtable->fieldList),
    set::data($tableData),
    set::userMap($users),
    set::footPager(usePager())
);
