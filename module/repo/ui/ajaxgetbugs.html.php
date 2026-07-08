<?php
declare(strict_types=1);
/**
 * The ajaxgetbugs view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;
set::zui(true);

jsVar('currentLink', inLink('ajaxGetBugs', "repoID={$repoID}&bugList=" . implode(',', $bugIDList) . "&currentBug=%s"));
$tabs = array();
foreach($bugs as $bug)
{
    $commentBlock = array();
    $bugComments  = array_values(zget($comments, $bug->id, array()));
    $commentCount = count($bugComments);
    foreach($bugComments as $index => $comment)
    {
        $commentBlock[] = div
        (
            setClass('comment-block mt-2'),
            set('data-id', $comment->id),
            set('data-bug', $bug->id),
            div
            (
                setClass('flex'),
                userAvatar
                (
                    set::size(16),
                    set::user($comment->user)
                ),
                span
                (
                    setClass('strong ml-2'),
                    zget($comment->user, 'realname', $comment->user->account)
                )
            ),
            div
            (
                setClass('mt-2'),
                div
                (
                    setClass('comment-content'),
                    html($comment->comment)
                ),
                div
                (
                    setClass('comment-form-div hidden'),
                    textarea
                    (
                        set::rows(3),
                        set::name('comment'),
                        set::value($comment->comment)
                    ),
                    div
                    (
                        setClass('text-right'),
                        btn
                        (
                            $lang->repo->submit,
                            setClass('editSubmit mt-2 primary size-sm')
                        )
                    )
                )
            ),
            div
            (
                setClass('mt-2'),
                $comment->date,
                hasPriv('repo', 'editComment') && $index + 1 == $commentCount ? icon('edit pull-right edit-comment cursor-pointer') : null
            )
        );
    }

    $tabs[] = tabPane
    (
        set::title($bug->title),
        set::key("tab-bug-{$bug->id}"),
        set::active($bug->id == $currentBug),
        div
        (
            setClass('pb-4'),
            div
            (
                userAvatar
                (
                    set::size(16),
                    set::user(zget($users, $bug->openedBy)),
                    set::realname(isset($users[$bug->openedBy]) ? $users[$bug->openedBy]->realname : $bug->openedBy)
                ),
                span
                (
                    setClass('strong px-2'),
                    isset($users[$bug->openedBy]) ? $users[$bug->openedBy]->realname : $bug->openedBy
                ),
                span
                (
                    setClass('text-muted'),
                    sprintf($lang->repo->dateTmpl, $bug->openedDate)
                )
            ),
            div
            (
                setClass('mt-2'),
                $lang->repo->issueTitle,
                div(setClass('font-bold'), $bug->title)
            ),
            div
            (
                setClass('mt-2 flex', $bug->steps ? '' : 'hidden'),
                span(setClass('flex-none'), $lang->repo->issueDesc),
                div(setClass('ml-2 font-bold'), html($bug->steps))
            ),
            hasPriv('repo', 'addComment') ? div
            (
                setClass('mt-2 canvas'),
                tabs
                (
                    tabPane
                    (
                        set::title($lang->repo->commentary),
                        set::active(true),
                        div
                        (
                            setClass('canvas comment-block'),
                            set('data-id', $bug->id),
                            textarea
                            (
                                set::rows(3),
                                set::name('comment'),
                                setClass('commentText form-control'),
                                set::placeholder($lang->repo->notice->commentContent)
                            ),
                            formHidden('objectID', $bug->id),
                            div
                            (
                                setClass('text-right'),
                                btn
                                (
                                    $lang->repo->submit,
                                    setClass('commentSubmit mt-2 primary size-sm')
                                )
                            )
                        )
                    ),
                )
            ) : null,
            $commentBlock
        )
    );
}

$commonClass = 'canvas cursor-pointer absolute top-1';
div
(
    on::click('.commentSubmit')->call('saveComment', jsRaw('this')),
    on::click('.editSubmit')->call('editSubmit', jsRaw('this')),
    on::click('.edit-comment')->call('editComment', jsRaw('this')),
    on::click('.btn-left')->call('arrowTabs', 'bugTabs', 1),
    on::click('.btn-right')->call('arrowTabs', 'bugTabs', -2),
    on::click('.btn-close')->call('closeTabs'),
    tabs
    (
        set::id('bugTabs'),
        setClass('canvas px-2'),
        set::titleClass('clip'),
        div(setStyle(array('position' => 'absolute', 'width' => '100%', 'height' => '34px', 'background' => '#efefef', 'top' => '0px'))),
        $tabs,
        div(setClass($commonClass, 'left-0  z-20 arrow-left  btn-left'),  icon('chevron-left')),
        div(setClass($commonClass, 'right-4 z-20 arrow-right btn-right'), icon('chevron-right')),
        div(setClass($commonClass, 'right-0 z-20 arrow-right btn-close'), icon('close'))
    )
);
render('pagebase');
