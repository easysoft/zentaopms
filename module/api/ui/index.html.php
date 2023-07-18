<?php
declare(strict_types=1);
/**
 * The index view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('api') feature bar on main menu. */
featureBar
(
    li(searchToggle(set::module('api')))
);

toolbar
(
    $libID && common::hasPriv('api', 'struct') ? item(set(array
    (
        'icon'  => 'treemap',
        'class' => 'ghost',
        'text'  => $lang->api->struct,
        'url'   => createLink('api', 'struct', "libID={$libID}"),
    ))) : null,
    $libID && common::hasPriv('api', 'releases') ? item(set(array
    (
        'icon'        => 'version',
        'class'       => 'ghost',
        'text'        => $lang->api->releases,
        'url'         => createLink('api', 'releases', "libID={$libID}"),
        'data-toggle' => 'modal'
    ))) : null,
    $libID && common::hasPriv('api', 'createRelease') ? item(set(array
    (
        'icon'        => 'publish',
        'class'       => 'ghost',
        'text'        => $lang->api->createRelease,
        'url'         => createLink('api', 'createRelease', "libID={$libID}"),
        'data-toggle' => 'modal'
    ))) : null,
    $libID && common::hasPriv('api', 'export') && $config->edition != 'open' ? item(set(array
    (
        'icon'        => 'export',
        'class'       => 'ghost export',
        'text'        => $lang->export,
        'url'         => createLink('api', 'export', "libID={$libID}&version={$version}&release={$release}&moduleID={$moduleID}"),
        'data-toggle' => 'modal'
    ))) : null,
    common::hasPriv('api', 'createLib') ? item(set(array
    (
        'icon'        => 'plus',
        'class'       => 'btn secondary',
        'text'        => $lang->api->createLib,
        'url'         => createLink('api', 'createLib', "type=" . ($objectType ? $objectType : 'nolink') . "&objectID=$objectID"),
        'data-toggle' => 'modal'
    ))) : null,
    $libID && common::hasPriv('api', 'create') ? item(set(array
    (
        'icon'        => 'plus',
        'class'       => 'btn primary',
        'text'        => $lang->api->createApi,
        'url'         => createLink('api', 'create', "libID={$libID}&moduleID={$moduleID}"),
        'data-toggle' => 'modal'
    ))) : null,
);

/* ====== Render page ====== */
render();
