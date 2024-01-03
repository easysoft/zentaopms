<?php
declare(strict_types = 1);
/**
 * The preview view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$currentMenu = $currentMenu ?? '';
$generateData = function() use ($lang) {return div(setClass('bg-canvas center text-gray w-full h-40'), $lang->pivot->noPivot);};

$viewFile = strtolower($method) . '.html.php';
if(file_exists($viewFile)) include_once $viewFile;

jsVar('dimensionID', $dimensionID);
jsVar('groupID', $groupID);

$items = array();
foreach($groups as $id => $name)
{
    $items[] = array('text' => $name, 'url' => inlink('preview', "dimension={$dimensionID}&group={$id}"), 'active' => $id == $groupID);
}

featureBar(set::items($items));

if($config->edition != 'open')
{
    toolbar
    (
        hasPriv('pivot', 'export') ? item(set(array
        (
            'text'  => $lang->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => '#export',
            'data-toggle' => 'modal'
        ))) : null,
        hasPriv('pivot', 'browse') ? item(set(array
        (
            'text'  => $lang->pivot->toDesign,
            'class' => 'primary',
            'url'   => inlink('browse'),
            'data-toggle' => 'modal'
        ))) : null,
    );
}

div
(
    setClass('flex gap-4'),
    sidebar
    (
        set::width(240),
        moduleMenu
        (
            set::title($groups[$groupID]),
            set::activeKey($currentMenu),
            set::modules($menus),
            set::closeLink(''),
            set::showDisplay(false)
        ),
        $config->edition == 'open' ? div
        (
            setClass('bg-canvas px-4 pb-4'),
            html(empty($config->isINT) ? $lang->bizVersion : $lang->bizVersionINT)
        ) : null
    ),
    div
    (
        setClass('flex col gap-4 w-full'),
        $generateData()
    )
);
