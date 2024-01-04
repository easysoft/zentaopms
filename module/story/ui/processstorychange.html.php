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

modalHeader(set::title($lang->story->URChanged));
div(setClass('text-gray mb-2'), icon(setClass('text-warning pr-2'), 'exclamation'), $lang->story->changeTips);
foreach($changedStories as $story)
{
    div
    (
        setClass('changedStoryBox'),
        entityLabel(set(array('entityID' => $story->id, 'level' => 3, 'text' => $story->title))),
        div
        (
            setClass('p-3'),
            p(setClass('text-gray pb-3'), "[{$lang->story->legendSpec}]"),
            html($story->spec),
            p(setClass('text-gray pb-3 pt-3'), "[{$lang->story->legendVerify}]"),
            html($story->verify)
        )
    );
}

div
(
    on::click('.changeBtn', 'closeModal'),
    setClass('actions text-center mt-3'),
    btn(set::url(inlink('processstorychange', "id=$storyID&result=no")), setClass('secondary changeBtn mr-5 btn-wide'), $lang->story->changeList['no']),
    btn(set::url(inlink('change', "id=$storyID")), setClass('primary changeBtn btn-wide'), $lang->story->changeList['yes'])
);

render();
