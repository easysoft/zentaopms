<?php
declare(strict_types=1);
/**
 * The batchUnlinkStory view file of projectstory module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     projectstory
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::titleClass('text-danger font-bold'), set::inModal(true), set::title($lang->projectstory->batchUnlinkTip));

$tableTR = array();
foreach($executionStories as $story)
{
    $storyLink     = helper::createLink('story', 'view', "storyID={$story->id}");
    $executionLink = helper::createLink('execution', 'story', "executionID={$story->executionID}");
    $tableTR[] = h::tr
    (
        h::td($story->id),
        h::td(a(set::href($storyLink), set::title($story->title), set::style(array('color' => '#5988e2')), $story->title)),
        h::td(a(set::href($executionLink), set::title($story->execution), set::style(array('color' => '#32579c')), setData('app', 'execution'), $story->execution))
    );
}

h::table
(
    set::className('table'),
    h::tr
    (
        h::th(set::width('60px'), $lang->idAB),
        h::th($lang->story->title),
        h::th($lang->story->link . $lang->execution->common)
    ),
    $tableTR,
    h::tr
    (
        setClass('text-center border-b-0'),
        h::td(set::colspan(3), setStyle(array('text-align' => 'center')), btn(setClass('primary confirmBtn mt-4'), set('data-dismiss', 'modal'), $lang->projectstory->confirm))
    )
);

render('modalDialog');
