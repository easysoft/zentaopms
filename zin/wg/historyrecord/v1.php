<?php
namespace zin;

class historyRecord extends wg
{
    protected static $defineProps = [
        'actions?:array',
        'users?:array',
        'methodName?:string'
    ];

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function checkEditCommentPriv($action)
    {
        global $app;
        $methodName = $this->prop('methodName') ?? data('methodName');

        return (!isset($canBeChanged) || !empty($canBeChanged))
            && end($actions) == $action
            && trim($action->comment) !== ''
            && str_contains(',view,objectlibs,viewcard,', ",$methodName,")
            && $action->actor == $app->user->account
            && common::hasPriv('action', 'editComment');
    }

    private function createExpandBtn($i)
    {
        global $lang;

        return button
        (
            setClass('btn btn-mini switch-btn btn-icon btn-expand'),
            set::type('button'),
            set::title($lang->switchDisplay),
            h::i(setClass('change-show icon icon-plus icon-sm')),
            on::click(<<<EXPAND
            var changeBox = document.querySelector("#changeBox$i");
            var icon = e.target.querySelector('.icon');
            console.log(icon);
            icon.classList.toggle('icon-plus');
            icon.classList.toggle('icon-minus');
            if (icon.classList.contains('icon-plus')) {
                changeBox.classList.remove('show');
            } else {
                changeBox.classList.add('show');
            }
            EXPAND),
        );
    }

    private function createEditCommentBtn()
    {
        global $lang;

        return button
        (
            setClass('btn btn-link btn-icon btn-sm btn-edit-comment'),
            set::title($lang->action->editComment),
            h::i(setClass('icon icon-pencil')),
        );
    }

    private function createHistoryChangesView($action, $i)
    {
        global $app;

        return div
        (
            setClass('history-changes'),
            set::id("changeBox$i"),
            html($app->loadTarget('action')->renderChanges($action->objectType, $action->history)),
        );
    }

    private function createActionItemView($action, $i)
    {
        global $app;

        return li
        (
            set::value($i),
            html($app->loadTarget('action')->renderAction($action))
        );
    }

    private function generateComment($action)
    {
        if(str_contains($action->comment, '<pre class="prettyprint lang-html">'))
        {
            $before   = explode('<pre class="prettyprint lang-html">', $action->comment);
            $after    = explode('</pre>', $before[1]);
            $htmlCode = $after[0];
            return $before[0] . htmlspecialchars($htmlCode) . $after[1];
        }

        return strip_tags($action->comment) === $action->comment
            ? nl2br($action->comment)
            : $action->comment;
    }

    private function createCommentView($action)
    {
        $comment = $this->generateComment($action);

        return div
        (
            setClass('article-content comment'),
            div
            (
                setClass('comment-content'),
                $comment,
            ),
        );
    }

    private function createCommentEditForm($action)
    {
        global $lang;

        return form
        (
            setClass('comment-edit-form'),
            set::method('post'),
            set::action(createLink('action', 'editComment', "actionID=$action->id")),
            div
            (
                setClass('form-group'),
                textarea
                (
                    htmlSpecialString($action->comment),
                    set::name('lastComment'),
                    set::rows('8'),
                    set::autofocus('autofocus'),
                ),
            ),
            div
            (
                setClass('form-group form-actions'),
                button
                (
                    setClass('btn btn-wide btn-primary'),
                    set::type('submit'),
                    set::id('submit'),
                    $lang->save,
                ),
                button
                (
                    setClass('btn btn-wide btn-hide-form'),
                    $lang->close,
                ),
            ),
        );
    }

    private function buildHistoriesList()
    {
        $actions    = $this->prop('actions') ?? data('actions');
        $users      = $this->prop('users') ?? data('users');
        $historiesListView = h::ol(setClass('histories-list'));
        $i = 0;

        foreach($actions as $action)
        {
            if($action->action === 'assigned' || $action->action === 'toaudit')
                $action->extra = zget($users, $action->extra);

            $action->actor = zget($users, $action->actor);
            if(str_contains($action->actor, ':'))
                $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);

            $i++;
            $actionItemView = $this->createActionItemView($action, $i);

            if(!empty($action->history))
            {
                $allExpandBtn = $this->createExpandBtn($i);
                $actionItemView->add($allExpandBtn);

                $historyChangesView = $this->createHistoryChangesView($action, $i);
                $actionItemView->add($historyChangesView);
            }
            if(strlen(trim(($action->comment))) !== 0)
            {
                $canEditComment = $this->checkEditCommentPriv($action);

                if($canEditComment)
                {
                    $editCommentBtn = $this->createEditCommentBtn();
                    $actionItemView->add($editCommentBtn);
                }

                $commentView = $this->createCommentView($action);
                $actionItemView->add($commentView);

                if($canEditComment)
                {
                    $commentEditForm = $this->createCommentEditForm($action);
                    $actionItemView->add($commentEditForm);
                }
            }
            $historiesListView->add($actionItemView);
        }

        return $historiesListView;
    }

    protected function build()
    {
        global $lang;
        return div
        (
            setClass('detail histories'),
            set::id('actionbox'),
            set('data-textdiff', $lang->action->textDiff),
            set('data-original', $lang->action->original),
            div
            (
                setClass('detail-title'),
                span($lang->history),
                button
                (
                    setClass('btn btn-mini btn-icon btn-reverse'),
                    setStyle('margin-right', '4px'),
                    set::type('button'),
                    set::title($lang->reverse),
                    h::i(setClass('icon icon-arrow-up icon-sm')),
                    on::click(<<<REVERSE
                    document.querySelector('.histories-list').classList.toggle('sort-reverse');
                    var icon = e.target.querySelector('.icon');
                    icon.classList.toggle('icon-arrow-up');
                    icon.classList.toggle('icon-arrow-down');
                    REVERSE),
                ),
                button
                (
                    setClass('btn btn-mini btn-icon btn-expand-all'),
                    set::type('button'),
                    set::title($lang->switchDisplay),
                    h::i(setClass('icon icon-plus icon-sm')),
                    on::click(<<<EXPANDALL
                    var icon = e.target.querySelector('.icon');
                    var isExpand = icon.classList.contains('icon-plus');
                    var changeBoxs = document.querySelectorAll('[id^="changeBox"]');
                    if(isExpand) {
                        changeBoxs.forEach(function(box) {
                            box.classList.add('show');
                        });
                    } else {
                        changeBoxs.forEach(function(box) {
                            box.classList.remove('show');
                        });
                    }
                    icon.classList.toggle('icon-plus');
                    icon.classList.toggle('icon-minus');
                    EXPANDALL),
                ),
                button
                (
                    setClass('btn btn-link pull-right btn-comment'),
                    set::type('button'),
                    h::i(setClass('icon icon-chat-line'), ' ' . $lang->action->create)
                ),
            ),
            div(setClass('detail-content'), $this->buildHistoriesList())
        );
    }
}
