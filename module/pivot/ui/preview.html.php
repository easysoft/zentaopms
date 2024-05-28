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

if($this->config->edition != 'open')
{
    $pivotPath = $this->app->getModuleExtPath('pivot', 'ui');
    include $pivotPath['common'] . 'exportdata.html.php';
}

jsVar('dimensionID', $dimensionID);
jsVar('groupID', $groupID);

$items = array();
foreach($groups as $id => $name)
{
    $items[] = array
    (
        'text' => $name,
        'value' => $id,
        'url' => inlink('preview', "dimension={$dimensionID}&group={$id}"),
        'badge' => $id == $groupID ? array('text' => $recTotal, 'class' => 'size-sm canvas ring-0 rounded-md') : null,
        'active' => $id == $groupID
    );
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
            'data-target' => '#export',
            'data-toggle' => 'modal',
            'data-size' => 'sm'
        ))) : null,
        hasPriv('pivot', 'browse') ? item(set(array
        (
            'text'  => $lang->pivot->toDesign,
            'class' => 'primary',
            'url'   => inlink('browse'),
        ))) : null,
    );
}

sidebar
(
    set::width(240),
    moduleMenu
    (
        to::header
        (
            div
            (
                setClass('bg-canvas my-3 mx-5 text-xl font-semibold text-ellipsis'),
                $groups[$groupID]
            )
        ),
        set::title($groups[$groupID]),
        set::activeKey($currentMenu),
        set::modules($menus),
        set::closeLink(''),
        set::showDisplay(false),
        set::titleShow(false),
        to::footer
        (
            $this->config->edition == 'open' ? div
            (
                set::width(240),
                setClass('bg-canvas px-4 py-2 module-menu'),
                html(empty($config->isINT) ? $lang->bizVersion : $lang->bizVersionINT)
            ) : null
        )
    )
);
div
(
    setID('pivotContent'),
    setClass('flex col gap-4 w-full'),
    $generateData()
);
