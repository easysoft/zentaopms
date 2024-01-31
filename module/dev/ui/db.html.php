<?php
declare(strict_types=1);
/**
 * The db view file of dev module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong<yidong@easycorp.ltd>
 * @package     dev
 * @link        https://www.zentao.net
 */
namespace zin;

$fnBuildContent = function() use ($fields, $selectedTable)
{
    global $lang;

    $trItems = array();
    $i       = 1;
    foreach($fields as $key => $field)
    {
        $trItems[] = h::tr
        (
            h::td($i),
            h::td($key),
            h::td($field['name']),
            h::td($field['type']),
            h::td(isset($field['options']['max']) ? $field['options']['max'] : ''),
            h::td($field['null'])
        );
        $i++;
    }

    return panel
    (
        set::title($selectedTable),
        set::bodyClass('p-0'),
        h::table
        (
            setClass('table bordered'),
            h::thead
            (
                h::tr
                (
                    h::th(setClass('w-20'), $lang->dev->fields['id']),
                    h::th($lang->dev->fields['name']),
                    h::th($lang->dev->fields['desc']),
                    h::th($lang->dev->fields['type']),
                    h::th($lang->dev->fields['length']),
                    h::th($lang->dev->fields['null'])
                )
            ),
            h::tbody($trItems)
        )
    );
};

$activeGroup = '';
foreach($tableTree as $module)
{
    if($module->active) $activeGroup = $module->id;
}

h::css("
.sidebar .tree [data-level=\"0\"][id=\"{$activeGroup}\"] {color: var(--color-primary-600); font-weight:bolder}
.sidebar .tree [data-level=\"1\"][id=\"{$selectedTable}\"] {color: var(--color-primary-600); font-weight:bolder}
");

sidebar
(
    setClass('bg-white'),
    h::header
    (
        setClass('h-10 flex items-center pl-4 flex-none gap-3'),
        span(setClass('text-lg font-semibold'), icon(setClass('pr-2'), 'list'), $lang->dev->dbList),
    ),
    treeEditor(set(array('items' => $tableTree, 'canEdit' => false, 'canDelete' => false, 'canSplit' => false)))
);

div
(
    setClass('bg-white'),
    $selectedTable ? $fnBuildContent() : null
);
