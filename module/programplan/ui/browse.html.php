<?php
declare(strict_types=1);
namespace zin;

jsVar('browseType', $type);

if(empty($plans))
{
    div
    (
        setClass('table-empty-tip'),
        span(setClass('muted'), $lang->programplan->noData),
        common::hasPriv('programplan', 'create') ? btn(setClass('info'), set::url($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID")), icon('plus'), $lang->programplan->create) : null
    );
}
else
{
    if($type == 'gantt' or $type == 'assignedTo') include './gantt.html.php';
}
