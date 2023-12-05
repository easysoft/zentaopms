<?php
declare(strict_types=1);
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

$active = array();
$fnProcessTreeData = function($moduleTree, $level = 0, $parent = null) use (&$fnProcessTreeData, &$active)
{
    foreach($moduleTree as $menu)
    {
        $menu->text = $menu->name;
        $menu->link = empty($menu->url) ? '' : helper::createLink('editor', 'extend', "module={$menu->id}");
        if(!empty($menu->children)) $menu->items = $fnProcessTreeData($menu->children, $level + 1, $menu);
        if($menu->active)
        {
            $active[$level] = $menu->id;
            if($parent) $parent->active = 1;
        }
        unset($menu->children, $menu->url);
    }
    return $moduleTree;
};
$moduleTree = $fnProcessTreeData($moduleTree);

jsVar('moduleTree', $moduleTree);

if(common::hasPriv('editor', 'turnon')) div(setID('mainMenu'), set::style(array('display' => 'block', 'padding-top' => '0')), $lang->editor->turnOff, btn(set::url($this->createLink('editor', 'turnon', 'status=0')), set::size('sm'), $lang->dev->switchList[0]));
div
(
    setClass('flex'),
    cell
    (
        set::style(array('width' => '180px')),
        setClass('sidebar bg-white mr-2'),
        h::header
        (
            setClass('h-10 flex items-center pl-4 flex-none gap-3'),
            span(setClass('text-lg font-semibold'), icon('list'), $lang->editor->moduleList)
        ),
        ul(setID('moduleTree'))
    ),
    cell
    (
        set::width('350px'),
        setClass('module-col bg-white mr-2'),
        h::iframe(set::name('extendWin'), setID('extendWin'), set::height('100%'), set::width('100%'), set::frameborder(0))
    ),
    cell
    (
        setClass('main-col main-content flex-1 bg-white'),
        h::iframe(set::name('editWin'), setID('editWin'), set::height('100%'), set::width('100%'), set::frameborder(0))
    )
);
