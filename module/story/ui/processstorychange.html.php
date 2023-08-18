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

$fnGetContent = function() use ($changedStories, $lang, $users)
{
    $trItems = array();
    foreach($changedStories as $story)
    {
        $trItems[] = h::tr
        (
            h::td($story->id),
            h::td($story->title),
            h::td(setClass('text-danger'), $lang->story->changed),
            h::td($story->version),
            h::td(\zget($users, $story->openedBy)),
        );
    }

    return $trItems;
};

modalHeader(set::title($lang->story->URChanged));
h::table
(
    setClass('table'),
    h::thead
    (
        h::tr
        (
            h::th($lang->story->id),
            h::th($lang->story->title),
            h::th($lang->story->status),
            h::th($lang->story->version),
            h::th($lang->story->openedBy),
        ),
    ),
    h::tbody($fnGetContent()),
);

div(setClass('alert secondary m-3'), $lang->story->changeTips);
div
(
    setClass('actions text-center'),
    btn(set::url(inlink('processstorychange', "id=$storyID&result=no")), setClass('secondary mr-3'), $lang->story->changeList['no']),
    btn(set::url(inlink('change', "id=$storyID")), setClass('primary'), $lang->story->changeList['yes']),
);

render();
