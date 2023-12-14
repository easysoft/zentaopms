<?php
declare(strict_types=1);
/**
 * The ajaxgetrelationinfo view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$objectID = zget($object, 'id', '');
if($objectType == 'story')
{
    $headerTitle = a
    (
        span
        (
            zget($object, 'title', ''),
            setClass('text-primary text-lg font-bold entity-title')
        ),
        set::href(createLink('story', 'view', "storyID={$objectID}")),
    );
    $section = sectionList
    (
        section
        (
            set::title($lang->story->legendSpec),
            set::content(zget($object, 'spec', '')),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->story->legendVerify),
            set::content(zget($object, 'verify', '')),
            set::useHtml(true)
        ),
    );
}
elseif($objectType == 'task')
{
    $headerTitle = a
    (
        span
        (
            zget($object, 'name', ''),
            setClass('text-primary text-lg font-bold entity-title')
        ),
        set::href(createLink('task', 'view', "taskID={$objectID}")),
    );
    $section = sectionList
    (
        section
        (
            set::title($lang->task->legendDesc),
            set::content(zget($object, 'desc', '')),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->task->story),
            set::content(zget($object, 'storyTitle', '')),
            set::useHtml(true)
        ),
    );
}
elseif($objectType == 'bug')
{
    $headerTitle = a
    (
        span
        (
            zget($object, 'title', ''),
            setClass('text-primary text-lg font-bold entity-title')
        ),
        set::href(createLink('bug', 'view', "bugID={$objectID}")),
    );
    $section = sectionList
    (
        section
        (
            setClass('bug'),
            set::content(zget($object, 'steps', '')),
            set::useHtml(true)
        ),
    );
}

to::header('');

detailHeader
(
    to::prefix(''),
    to::title($headerTitle)
);

detailBody
(
    $section
);

render();
