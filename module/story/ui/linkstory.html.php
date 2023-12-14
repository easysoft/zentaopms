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

$pageParams = http_build_query($app->getParams());

$config->story->dtable->fieldList['title']['title'] = $lang->story->title;

$cols = array();
$cols['id']     = $config->story->dtable->fieldList['id'];
$cols['title']  = $config->story->dtable->fieldList['title'];
$cols['pri']    = $config->story->dtable->fieldList['pri'];
$cols['status'] = $config->story->dtable->fieldList['status'];
if($story->type == 'story') $cols['stage'] = $config->story->dtable->fieldList['stage'];
$cols['openedBy'] = $config->story->dtable->fieldList['openedBy'];
$cols['estimate'] = $config->story->dtable->fieldList['estimate'];
$cols['title']['nestedToggle'] = false;
$cols['title']['flex']         = 1;
$cols = array_map(function($col){unset($col['sortType']); return $col;}, $cols);

$data = array();
foreach($stories2Link as $story) $data[] = $this->story->formatStoryForList($story);

modalHeader(set::title($lang->story->linkStory));

searchForm
(
    set::module('story'),
    set::simple(true),
    set::show(true)
);

dtable
(
    setID('linkStories'),
    set::userMap($users),
    set::cols($cols),
    set::data($data),
    set::footToolbar(array('items' => array(array('text' => $lang->save, 'btnType' => 'primary', 'className' => 'size-sm', 'data-on' => 'click', 'data-call' => 'fnLinkStories', 'data-params' => 'event')))),
    set::footer(array('checkbox', 'toolbar'))
);

h::js
(
<<<EOD
window.fnLinkStories = function(e)
{
    const dtable      = zui.DTable.query(e.target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    postData.append('story', {$story->id});
    checkedList.forEach((id) => postData.append('stories[]', id));

    $.ajaxSubmit({"url": $.createLink('story', 'linkStory', '{$pageParams}'), "data": postData});
}
EOD
);

render();
