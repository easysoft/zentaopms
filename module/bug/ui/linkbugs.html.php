<?php
declare(strict_types=1);
/**
 * The linkbugs view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $config->bug->linkBugs->dtable->fieldList;
$cols['product']['map'] = $products;

$footToolbar = array('items' => array());
$footToolbar['items'][] = array('text' => $lang->save, 'btnType' => 'primary', 'className' => 'size-sm', 'data-on' => 'click', 'data-call' => 'fnLinkBugs', 'data-params' => 'event');

if(isInModal())
{
    modalHeader
    (
        set::title($lang->bug->linkBugs),
        set::entityText($bug->title),
        set::entityID($bug->id)
    );
}

searchForm
(
    set::module('bug'),
    set::simple(true),
    set::show(true)
);

dtable
(
    set::cols($cols),
    set::userMap($users),
    set::data($bugs2Link),
    set::footPager(usePager()),
    set::footToolbar($footToolbar),
    set::footer(array('checkbox', 'toolbar'))
);

h::js
(
<<<EOD
window.fnLinkBugs = function(e)
{
    const dtable      = zui.DTable.query(e.target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    var checkedBugs = [];
    checkedList.forEach(function(id)
    {
        var bugInfo = dtable.$.getRowInfo(id).data;
        checkedBugs.push({text: bugInfo.title, value: bugInfo.id});
    });

    const \$relatedBugs = $('#linkBugsBox').find('.picker-box').zui('picker');
    \$relatedBugs.render({items: checkedBugs});
    \$relatedBugs.$.setValue(checkedList);
    zui.Modal.hide('#' + $(e.target).closest('.modal').attr('id'));
}
EOD
);

render();
