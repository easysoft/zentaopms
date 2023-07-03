<?php
declare(strict_types=1);
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wang Yidong <yidong@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

jsVar('storyType', $story->type);

$cols = array();
$cols['id']  = $config->story->dtable->fieldList['id'];
$cols['pri'] = $config->story->dtable->fieldList['pri'];
if(!empty($product->shadow)) $cols['product'] = $config->story->dtable->fieldList['product'];
$cols['title']      = $config->story->dtable->fieldList['title'];
$cols['status']     = $config->story->dtable->fieldList['status'];
$cols['openedBy']   = $config->story->dtable->fieldList['openedBy'];
$cols['assignedTo'] = $config->story->dtable->fieldList['assignedTo'];

$data = array();
foreach($stories2Link as $story) $data[] = $this->story->formatStoryForList($story);

modalHeader(set::title($story->type == 'story' ? $lang->story->linkStoriesAB : $lang->story->linkRequirementsAB));

dtable
(
    set::id('linkStories'),
    set::userMap($users),
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::footToolbar(array('items' => array('text' => $lang->save, 'className' => 'primary linkStoriesBtn', 'data-on' => 'click', 'data-dismiss' => 'modal', 'data-call' => 'linkStories'))),
    set::footer(array('checkbox', 'toolbar', 'flex', 'pager')),

);

h::js
(
<<<EOD
window.linkStories = function(e)
{
    const dtable      = zui.DTable.query(e.target);
    const checkedList = dtable.$.getChecks();
    var   itemBoxHtml = '';
    checkedList.forEach(function(id)
    {
        var linkStoryField = storyType == 'story' ? 'linkStories' : 'linkRequirements';
        var storyInfo      = dtable.$.getRowInfo(checkedList[0]).data;
        var checkbox       = "<input type='checkbox' checked='checked' name='" + linkStoryField + "[]' " + "value=" + storyInfo.id + " />";
        var idLabel        = "<span class='label circle size-sm'>" + storyInfo.id + "</span>";
        var titleSpan      = "<span class='linkStoryTitle'>" + storyInfo.title + "</span>";

        itemBoxHtml += "<div title='" + storyInfo.title + "'>" + checkbox + idLabel + titleSpan + "</div>";
    });

    $('#linkStoriesBox').html(itemBoxHtml);
}
EOD
);

render();
