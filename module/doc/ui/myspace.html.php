<?php
declare(strict_types=1);
/**
 * The mySpace view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;
include 'lefttree.html.php';
if($libID && common::hasPriv('doc', 'create')) include 'createbutton.html.php';
include 'mydoclist.html.php';

jsVar('browseType', $browseType);
jsVar('docLang', $lang->doc);
jsVar('confirmDelete', $lang->doc->confirmDelete);
jsVar('appTab', $app->tab);
jsVar('treeData', $libTree);

div
(
    setClass('flex flex-wrap content-start'),
    /* zin: Define the set::module('doc') feature bar on main menu. */
    featureBar
    (
        set::current($browseType),
        set::linkParams("type={$type}&libID={$libID}&moduleID={$moduleID}&browseType={key}"),
        li(searchToggle(set::module($type . $libType . 'Doc')))
    ),
    toolbar
    (
        $canExport ? item(set(array
        (
            'icon'        => 'export',
            'class'       => 'ghost export',
            'text'        => $lang->export,
            'url'         => createLink('doc', 'mine2export', "libID={$libID}&moduleID={$moduleID}"),
            'data-toggle' => 'modal'
        ))) : null,
        common::hasPriv('doc', 'createLib') ? item(set(array
        (
            'icon'        => 'plus',
            'class'       => 'btn secondary',
            'text'        => $lang->doc->createLib,
            'url'         => createLink('doc', 'createLib', 'type=mine'),
            'data-toggle' => 'modal'
        ))) : null,
        $libID && common::hasPriv('doc', 'create') ? $createButton : null
    ),
    div
    (
        setClass('doc-content mt-2 flex-initial w-full'),
        $docContent
    )
);
