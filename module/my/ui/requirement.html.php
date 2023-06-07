<?php
declare(strict_types=1);
/**
 * The requirement view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($type),
    set::linkParams("mode=requirement&type={key}&param={$param}"),
    li(searchToggle())
);

$stories = initTableData($stories, $config->my->requirement->dtable->fieldList, $this->story);
$cols    = array_values($config->my->requirement->dtable->fieldList);
$data    = array_values($stories);

$footToolbar = array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('story', 'batchEdit', "productID=0&executionID=0&branch=0&storyType=requirement&from={$app->rawMethod}")),
    array('caret' => 'up', 'text' => $lang->story->review, 'url' => '#navReview', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('caret' => 'up', 'text' => $lang->story->assignedTo, 'url' => '#navAssignedTo', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('text' => $lang->story->close, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('story', 'batchClose', "productID=0&executionID=0&storyType=requirement&from={$app->rawMethod}")),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'primary'));

$rejectItems = array();
foreach($lang->story->reasonList as $key => $reason)
{
    if(!$key || $key == 'subdivided' || $key == 'duplicate') continue;
    $rejectItems[] = array('text' => $reason, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('story', 'batchReview', "result=reject&reason={$key}&storyType=requirement"));
}

$reviewItems = array();
foreach($lang->story->reviewResultList as $key => $result)
{
    if(!$key || $key == 'revert') continue;
    if($key == 'reject')
    {
        $reviewItems[] = array('text' => $result, 'class' => 'not-hide-menu', 'items' => $rejectItems);
    }
    else
    {
        $reviewItems[] = array('text' => $result, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('story', 'batchReview', "result={$key}&reason=&storyType=requirement"));
    }
}

menu
(
    set::id('navReview'),
    set::class('menu dropdown-menu'),
    set::items($reviewItems)
);

$assignedToItems = array();
foreach($users as $key => $value)
{
    if(empty($key) || $key == 'closed') continue;
    $assignedToItems[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('story', 'batchAssignTo', "storyType=requirement&assignedTo={$key}"));
}

menu
(
    set::id('navAssignedTo'),
    set::class('dropdown-menu'),
    set::items($assignedToItems)
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();
