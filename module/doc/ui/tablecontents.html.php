<?php
declare(strict_types=1);
/**
 * The tableContents view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;
include 'lefttree.html.php';

if($libType != 'api' && $libID && common::hasPriv('doc', 'create')) include 'createbutton.html.php';

if($canExport)
{
    $exportLink = createLink('doc', $exportMethod, "libID={$libID}&moduleID={$moduleID}");
    if($libType == 'api') $exportLink = $this->createLink('api', $exportMethod, "libID={$libID}&version=0&release={$release}&moduleID={$moduleID}");
}

/* zin: Define the set::module('doc') feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::method('tableContents'),
    set::linkParams("objectID={$objectID}&libID={$libID}&moduleID={$moduleID}&browseType={key}"),
    li(searchToggle(set::module($type . $libType . 'Doc')))
);

toolbar
(
    $libType == 'api' && common::hasPriv('api', 'struct') ? item(set(array
    (
        'icon'  => 'treemap',
        'class' => 'ghost',
        'text'  => $lang->api->struct,
        'url'   => createLink('api', 'struct', "libID={$libID}"),
    ))) : null,
    $libType == 'api' && common::hasPriv('api', 'releases') ? item(set(array
    (
        'icon'        => 'version',
        'class'       => 'ghost',
        'text'        => $lang->api->releases,
        'url'         => createLink('api', 'releases', "libID={$libID}"),
        'data-toggle' => 'modal'
    ))) : null,
    $libType == 'api' && common::hasPriv('api', 'createRelease') ? item(set(array
    (
        'icon'        => 'publish',
        'class'       => 'ghost',
        'text'        => $lang->api->createRelease,
        'url'         => createLink('api', 'createRelease', "libID={$libID}"),
        'data-toggle' => 'modal'
    ))) : null,
    $canExport ? item(set(array
    (
        'id'          => $exportMethod,
        'icon'        => 'export',
        'class'       => 'ghost export',
        'text'        => $lang->export,
        'url'         => $exportLink,
        'data-toggle' => 'modal'
    ))) : null,
    common::hasPriv('doc', 'createLib') ? item(set(array
    (
        'icon'        => 'plus',
        'class'       => 'btn secondary',
        'text'        => $lang->doc->createLib,
        'url'         => createLink('doc', 'createLib', "type={$type}&objectID={$objectID}"),
        'data-toggle' => 'modal'
    ))) : null,
    $libType == 'api' && common::hasPriv('api', 'create') ? item(set(array
    (
        'icon'        => 'plus',
        'class'       => 'btn primary ml-2',
        'text'        => $lang->api->createApi,
        'url'         => createLink('api', 'create', "libID={$libID}&moduleID={$moduleID}"),
        'data-size'   => 'lg',
        'data-toggle' => 'modal'
    ))) : null,
    $libType != 'api' && $libID && common::hasPriv('doc', 'create') ? $createButton : null
);

if($browseType == 'annex')
{
    include 'showfiles.html.php';
}
elseif($libType == 'api')
{
    h::css(file_get_contents($app->getModuleRoot() . 'api/css/index.ui.css'));
    include '../../api/ui/index.html.php';
}
else
{
    include 'doclist.html.php';
}

$docContent;
