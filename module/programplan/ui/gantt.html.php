<?php
declare(strict_types=1);
/**
 * The gantt view file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     programplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

data('fileName', 'gantt-export-' . $projectID);
include './ganttfields.html.php';

if($app->rawModule == 'programplan')
{
    if($project->stageBy == 'product')
    {
        $viewName = $productID != 0 ? zget($productList, $productID) : $lang->product->allProduct;
        $items    = array();
        foreach($productList as $key => $productName) $items[] = array('text' => $productName, 'url' => $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$key&type=gantt"));
        featureBar
        (
            dropdown
            (
                btn(set::type('link'), setClass('no-underline'), $viewName),
                set::items($items)
            )
        );
    }
    else
    {
        featureBar(span(setClass('text font-bold'), $lang->programplan->gantt));
    }
    toolbar
    (
        btnGroup
        (
            btn(setClass('square switchBtn text-primary'), set::title($lang->programplan->gantt), icon('gantt-alt')),
            btn(setClass('square switchBtn'), set::title($lang->project->bylist), set::url($this->createLink('project', 'execution', "status=all&projectID=$projectID")), icon('list'))
        ),
        btn(setClass('no-underline'), set::type('link'), setID('fullScreenBtn'), set::icon('fullscreen'), $lang->programplan->full),
        dropdown
        (
            btn(set::type('link'), setClass('no-underline'), set::icon('export'), $lang->export),
            set::items(array
            (
                array('text' => $lang->execution->gantt->exportImg, 'url' => 'javascript:exportGantt()'),
                array('text' => $lang->execution->gantt->exportPDF, 'url' => 'javascript:exportGantt("pdf")')
            ))
        ),
        btn(set::url($this->createLink('programplan', 'ajaxcustom')), set::icon('cog-outline'), $lang->settings, setClass('no-underline'), set::type('link'), set('data-toggle', 'modal'), set('data-size', 'sm')),
        (common::hasPriv('programplan', 'create') && empty($product->deleted)) ? btn(set::url($this->createLink('programplan', 'create', "projectID=$projectID")), set::icon('plus'), $lang->programplan->create, setClass('primary programplan-create-btn')) : null
    );
}

gantt
(
    set('ganttLang', $ganttLang),
    set('ganttFields', $ganttFields),
    set('canEdit', common::hasPriv('programplan', 'ganttEdit')),
    set('canEditDeadline', common::hasPriv('review', 'edit')),
    set('zooming', isset($zooming) ? $zooming : 'day'),
    set('options', $plans)
);
