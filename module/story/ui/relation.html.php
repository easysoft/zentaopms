<?php
declare(strict_types=1);
/**
 * The change view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

$tbody = array();
foreach($relation as $key => $story)
{
    $tbody[] = h::tr
    (
        h::td
        (
            $story->id
        ),
        h::td
        (
            $story->title
        ),
        h::td
        (
            $story->pri
        ),
        h::td
        (
            zget($users, $story->openedBy)
        ),
        h::td
        (
            zget($users, $story->assignedTo)
        ),
        h::td
        (
            $story->estimate
        ),
        h::td
        (
            zget($lang->story->statusList, $story->status)
        )
    );
}

h::table
(
    setClass('table table-fixed'),
    h::tr
    (
        h::th
        (
            set::width('60px'),
            $lang->story->id
        ),
        h::th
        (
            $lang->story->title
        ),
        h::th
        (
            set::width('60px'),
            $lang->story->pri
        ),
        h::th
        (
            set::width('100px'),
            $lang->story->openedBy
        ),
        h::th
        (
            set::width('100px'),
            $lang->story->assignedTo
        ),
        h::th
        (
            set::width('80px'),
            $lang->story->estimate
        ),
        h::th
        (
            set::width('80px'),
            $lang->story->status
        )
    ),
    $tbody
);
