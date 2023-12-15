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
if($app->rawModule == 'api')
{
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
            'url'   => createLink('api', 'struct', "libID={$libID}")
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
            'url'         => createLink('api', 'create', "libID={$libID}&moduleID={$moduleID}")
        ))) : null,
    );

    include '../../doc/ui/lefttree.html.php';
}

$list = array();
foreach($apiList as $api)
{
    $list[] = h::li
    (
        setClass('list-group-item'),
        div
        (
            setClass("heading {$api->method}"),
            a
            (
                set::href(createLink('api', 'index', "libID={$api->lib}&moduleID=0&apiID={$api->id}&version={$api->version}")),
                span
                (
                    setClass('method'),
                    $api->method
                ),
                span
                (
                    setClass('path'),
                    $api->path
                ),
                span
                (
                    setClass('desc'),
                    $api->title
                )
            )
        )
    );
}

$delimiter  = strpos($app->clientLang, 'zh') === 0 ? '：' : ': ';
$docContent = panel
(
    $lib ? div
    (
        setClass('detail base-url'),
        $lang->api->baseUrl . $delimiter . $lib->baseUrl
    ) : null,
    $lib ? h::hr(setClass('mb-4')) : null,
    div
    (
        setClass('detail'),
        h::ul
        (
            setClass('list-group'),
            $list
        )
    )
);
