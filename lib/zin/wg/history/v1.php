<?php
namespace zin;

class history extends wg
{
    protected static $defineProps = array(
        'actions?:array',
        'users?:array',
        'methodName?:string'
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function marker(int $num)
    {
        return span
        (
            setClass('marker', 'text-sm', 'rounded-full', 'aspect-square', 'inline-flex', 'justify-center', 'items-center', 'mr-2'),
            $num
        );
    }

    private function timeline()
    {
        return div(setClass('timeline w-px absolute'));
    }

    private function checkEditCommentPriv($action)
    {
        global $app;
        $methodName = $this->prop('methodName') === null ? $this->prop('methodName') : data('methodName');

        return (!isset($canBeChanged) || !empty($canBeChanged))
            && end($actions) == $action
            && trim($action->comment) !== ''
            && str_contains(',view,objectlibs,viewcard,', ",$methodName,")
            && $action->actor == $app->user->account
            && common::hasPriv('action', 'editComment');
    }

    private function expandBtn($i)
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

    private function editCommentBtn()
    {
        global $lang;
        return button
        (
            setClass('btn btn-link btn-icon btn-sm btn-edit-comment'),
            set::title($lang->action->editComment),
            h::i(setClass('icon icon-pencil')),
        );
    }

    private function historyChanges($action, $i)
    {
        global $app;
        return div
        (
            setClass('history-changes'),
            set::id("changeBox$i"),
            html($app->loadTarget('action')->renderChanges($action->objectType, $action->history)),
        );
    }

    private function actionItem($action, $i)
    {
        global $app;
        return li
        (
            setClass('my-3'),
            set::value($i),
            $this->marker($i),
            html($app->loadTarget('action')->renderAction($action))
        );
    }

    private function getComment($action)
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

    private function comment($action)
    {
        $comment = $this->getComment($action);
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

    private function commentEditForm($action)
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

    private function historyList()
    {
        $actions = $this->prop('actions') === null ? $this->prop('actions') : data('actions');
        $users   = $this->prop('users') === null ? $this->prop('users') : data('users');
        $historiesListView = h::ol(setClass('histories-list relative'));
        $i = 0;

        $historiesListView->add($this->timeline());
        foreach($actions as $action)
        {
            if($action->action === 'assigned' || $action->action === 'toaudit') $action->extra = zget($users, $action->extra);

            $action->actor = zget($users, $action->actor);
            if(str_contains($action->actor, ':')) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);

            $i++;
            $actionItemView = $this->actionItem($action, $i);

            if(!empty($action->history))
            {
                $actionItemView->add($this->expandBtn($i));
                $actionItemView->add($this->historyChanges($action, $i));
            }
            if(strlen(trim(($action->comment))) !== 0)
            {
                $canEditComment = $this->checkEditCommentPriv($action);

                if($canEditComment) $actionItemView->add($this->editCommentBtn());

                $actionItemView->add($this->comment($action));

                if($canEditComment) $actionItemView->add($this->commentEditForm($action));
            }
            $historiesListView->add($actionItemView);
        }

        return $historiesListView;
    }

    private function reverseBtn()
    {
        global $lang;
        return btn
        (
            setClass('btn-mini btn-icon btn-reverse mr-2'),
            set::title($lang->reverse),
            set::icon('arrow-up'),
            on::click(<<<REVERSE
            document.querySelector('.histories-list').classList.toggle('sort-reverse');
            var icon = e.target.querySelector('.icon');
            icon.classList.toggle('icon-arrow-up');
            icon.classList.toggle('icon-arrow-down');
            REVERSE)
        );
    }

    private function expandAllBtn()
    {
        global $lang;
        return btn
        (
            setClass('btn-mini btn-icon btn-expand-all'),
            set::title($lang->switchDisplay),
            set::icon('plus'),
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
            EXPANDALL)
        );
    }

    private function commentBtn()
    {
        global $lang;
        return btn
        (
            setClass('btn-comment btn-link ml-4'),
            set::icon('chat-line'),
            set::iconClass('text-primary'),
            $lang->action->create
        );
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
                $this->reverseBtn(),
                $this->expandAllBtn(),
                $this->commentBtn(),
            ),
            div(setClass('detail-content'), $this->historyList())
        );
    }
}
