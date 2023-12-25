<?php
declare(strict_types=1);
/**
 * The createimage view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('task', $task);
jsVar('taskID', $task->task);
jsVar('nodeID', $node->id);
jsVar('zanodeLang', $lang->zanode);

$link = helper::createLink('zanode', 'create', "hostID={$node->parent}");
$link = str_replace('onlybody=yes', '', $link);
$link = trim($link, '?');

if($task) modalHeader(set::title($lang->zanode->createImage));

!$task ? formPanel
(
    in_array($node->status, array('shutdown', 'shutoff')) ? null : set::ajax(array('beforeSubmit' => jsRaw("() => zui.Modal.confirm('{$lang->zanode->createImageNotice}')"))),
    setID('createImageForm'),
    set::title($lang->zanode->createImage),
    formGroup
    (
        set::name('name'),
        set::label($lang->zanode->imageName)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->zanode->desc),
        set::control('editor')
    )
) : h::div
(
    h::h5
    (
        setClass('text-center status-title'),
        $lang->zanode->pending
    ),
    div
    (
        setClass('progress'),
        div
        (
            setClass('progress-bar primary'),
            setStyle('width', $task->rate)
        )
    ),
    h6
    (
        setClass('text-center success-text hidden'),
        $lang->zanode->createImageSuccess,
        h::a
        (
            setStyle('color', '#2e7fff'),
            set::target('_parent'),
            set::href($link),
            $lang->zanode->createImageButton
        )
    ),
    h6
    (
        setClass('text-center fail hidden'),
        $lang->zanode->createImageFail
    )
);

render();
