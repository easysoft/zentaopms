<?php
declare(strict_types=1);
/**
 * The zerocase view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

$canBatchEdit        = hasPriv('story', 'batchEdit');
$canBatchChangeStage = hasPriv('story', 'batchChangeStage');
$canBatchReview      = hasPriv('story', 'batchReview');
$canBatchAction      = $canBatchEdit || $canBatchChangeStage || $canBatchReview;

$footToolbar = $canBatchAction ? array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn' . ($canBatchEdit ? '': 'hidden'), 'data-url' => createLink('story', 'batchEdit', "productID={$productID}&projectID={$projectID}&branch={$branch}")),
    array('text' => $lang->story->review,  'className' => ($canBatchReview ? '' : 'hidden') ,     'caret' => 'up', 'url' => '#navReview', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('text' => $lang->story->stageAB, 'className' => ($canBatchChangeStage ? '' : 'hidden'), 'caret' => 'up', 'url' => '#navStage',  'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary')) : null;

$rejectItems = array();
foreach($lang->story->reasonList as $key => $reason) $rejectItems[] = array('text' => $reason, 'class' => 'batch-btn ajax-btn', 'data-url' => createLink('story', 'batchReview', "result=reject&reason=$key")) ;

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
        $reviewItems[] = array('text' => $result, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('story', 'batchReview', "result=$key"));
    }
}

menu
(
    set::id('navReview'),
    set::className('dropdown-menu'),
    set::items($reviewItems)
);

$stageItems = array();
$lang->story->stageList[''] = $lang->null;
foreach($lang->story->stageList as $key => $stage) $stageItems[] = array('text' => $stage, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('story', 'batchChangeStage', "stage=$key"));

menu
(
    set::id('navStage'),
    set::className('dropdown-menu'),
    set::items($stageItems),
);

$stories = initTableData($stories, $config->testcase->zerocase->dtable->fieldList, $this->story);

dtable
(
    set::cols($config->testcase->zerocase->dtable->fieldList),
    set::data(array_values($stories)),
    set::userMap($users),
    set::footPager(usePager()),
    set::footToolbar($footToolbar),
    set::checkable($canBatchAction),
);

render();
