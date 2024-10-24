<?php
declare(strict_types=1);
/**
 * The browse view file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     programplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

jsVar('browseType', $type);

if(empty($plans))
{
    div
    (
        setClass('table-empty-tip'),
        span(setClass('muted'), $lang->programplan->noData),
        common::canModify('project', $project) && common::hasPriv('programplan', 'create') ? btn(setClass('info'), set::url($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID")), icon('plus'), $lang->programplan->create) : null
    );
}
else
{
    if($type == 'gantt' or $type == 'assignedTo') include './gantt.html.php';
}
