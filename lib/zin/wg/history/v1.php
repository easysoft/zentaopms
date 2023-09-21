<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'section' . DS . 'v1.php';

class history extends wg
{
    protected static array $defineProps = array(
        'id?: string',
        'actions?: array',
        'users?: array',
        'moduleName?: string',
        'methodName?: string',
        'commentUrl?: string',
        'commentBtn?: bool',
        'bodyClass?: string',
        'hasComment?: bool',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function created()
    {
        global $app;

        if(!$this->hasProp('moduleName')) $this->setProp('moduleName', $app->rawModule);
        if(!$this->hasProp('methodName')) $this->setProp('methodName', $app->rawMethod);
        if(!$this->hasProp('id'))         $this->setProp('id', 'history_' . $this->prop('moduleName') . '-' . $this->prop('methodName'));
    }

    private function marker(int|string $content): wg
    {
        return span
        (
            setClass('marker', 'relative', 'z-10', 'text-sm', 'rounded-full', 'aspect-square', 'inline-flex', 'justify-center', 'items-center', 'mr-1', 'border', 'h-5', 'w-5', 'z-10'),
            is_int($content) ? $content : icon('check', setClass('text-success font-semibold'))
        );
    }

    private function checkEditCommentPriv(object $action): bool
    {
        global $app;
        $methodName = $this->prop('methodName');
        $actions    = $this->prop('actions') !== null ? $this->prop('actions') : data('actions');

        return (!isset($canBeChanged) || !empty($canBeChanged))
            && !empty($actions) && end($actions) == $action
            && trim($action->comment) !== ''
            && str_contains(',view,objectlibs,viewcard,', ",$methodName,")
            && $action->account == $app->user->account
            && common::hasPriv('action', 'editComment');
    }

    private function expandBtn(): wg
    {
        global $lang;
        return btn
        (
            setClass('btn-expand btn-mini px-0'),
            set::title($lang->switchDisplay),
            h::i(setClass('change-show icon icon-plus icon-sm')),
            on::click('expand'),
        );
    }

    private function editCommentBtn(): wg
    {
        global $lang;
        return button
        (
            setClass('btn btn-link btn-edit-comment right-0'),
            set::title($lang->action->editComment),
            h::i(setClass('icon icon-pencil')),
            on::click('editComment')
        );
    }

    private function historyChanges(object $action): wg
    {
        global $app;
        return div
        (
            setClass('history-changes ml-1 mt-2'),
            html($app->loadTarget('action')->renderChanges($action->objectType, $action->history)),
        );
    }

    private function actionItem(object $action, int $i): wg
    {
        global $app;
        $actionItemView = div
        (
            setClass('w-full'),
            html($app->loadTarget('action')->renderAction($action))
        );
        if(!empty($action->history))
        {
            $actionItemView->add($this->expandBtn());
            $actionItemView->add($this->historyChanges($action));
        }
        if(strlen(trim(($action->comment))) !== 0)
        {
            $actionItemView->add($this->comment($action));

            $canEditComment = $this->checkEditCommentPriv($action);
            if($canEditComment) $actionItemView->add($this->commentEditForm($action));
        }
        return li
        (
            setClass('mb-2 flex'),
            $this->marker($action->action === 'finished' ? 'finished' : $i),
            $actionItemView
        );
    }

    private function getComment(object $action): string
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

    private function comment(object $action): wg
    {
        $comment = $this->getComment($action);
        $canEdit = $this->checkEditCommentPriv($action);
        return div
        (
            setClass('article-content comment relative'),
            $canEdit ? $this->editCommentBtn() : null,
            div
            (
                setClass('comment-content mt-2 p-2.5'),
                isHTML($comment) ? html($comment) : $comment,
            ),
        );
    }

    private function commentEditForm(object $action): wg
    {
        global $lang;

        return form
        (
            setClass('comment-edit-form hidden mt-2 ml-6'),
            setData('load', 'modal'),
            setData('close-modal', false),
            set::method('post'),
            set::submitBtnText($lang->save),
            set::action(createLink('action', 'editComment', "actionID=$action->id")),
            editor
            (
                set::name('lastComment'),
                isHTML($action->comment) ? html($action->comment) : $action->comment
            ),
            set::actions(array(
                'submit',
                array('text' => $lang->close, 'id' => 'btn-close-form')
            ))
        );
    }

    private function historyList(): wg
    {
        $actions = $this->prop('actions') !== null ? $this->prop('actions') : data('actions');
        $users   = $this->prop('users') !== null ? $this->prop('users') : data('users');
        $historiesListView = h::ol(setClass('history-list col relative'));

        $i = 0;
        foreach($actions as $action)
        {
            $action->account = $action->actor;
            if($action->action === 'assigned' || $action->action === 'toaudit') $action->extra = zget($users, $action->extra);
            $action->actor = zget($users, $action->actor);
            if(str_contains($action->actor, ':')) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);

            $i++;
            $historiesListView->add($this->actionItem($action, $i));
        }

        return $historiesListView;
    }

    private function reverseBtn(): wg
    {
        global $lang;
        return btn
        (
            setClass('btn-reverse btn-mini px-0 ml-3'),
            set::title($lang->reverse),
            set::icon('arrow-up'),
            on::click('reverseList')
        );
    }

    private function expandAllBtn(): wg
    {
        global $lang;
        return btn
        (
            setClass('btn-mini px-0 btn-expand-all ml-2'),
            set::title($lang->switchDisplay),
            set::icon('plus'),
            on::click('expandAll')
        );
    }

    private function commentBtn(): ?wg
    {
        global $app, $lang;
        $methodName = $this->prop('methodName') !== null ? $this->prop('methodName') : $app->rawMethod;
        $showCommentBtn = $this->prop('commentBtn', false);
        if(!$showCommentBtn && !str_contains(',view,objectlibs,viewcard,', ",$methodName,")) return null;
        return commentBtn
        (
            set::dataTarget('#comment-dialog_'. $this->prop('moduleName') . '-' . $this->prop('methodName')),
            setClass('btn-comment ml-4 size-sm ghost'),
            set::icon('chat-line'),
            set::iconClass('text-primary'),
            set::text($lang->action->create)
        );
    }

    protected function build(): wg
    {
        global $lang;

        $isInModal = isAjaxRequest('modal');
        $padding   = $isInModal ? 'px-3 pd-3' : 'px-6 pb-6';

        list($commentUrl, $bodyClass, $hasComment) = $this->prop(array('commentUrl', 'bodyClass', 'hasComment'));
        return panel
        (
            setClass('history', 'pt-4', 'h-full', $padding),
            setID($this->prop('id')),
            set::headingClass('p-0'),
            set::bodyClass("p-0 {$bodyClass}"),
            set::shadow(false),
            to::heading
            (
                div
                (
                    setClass('flex'),
                    div
                    (
                        set('class', 'panel-title'),
                        $lang->history,
                    ),
                    div
                    (
                        setClass('flex items-center'),
                        $this->reverseBtn(),
                        $this->expandAllBtn(),
                        $this->commentBtn(),
                    )
                )
            ),
            div(setClass('mt-3'), $this->historyList()),
            $hasComment !== false ? commentDialog
            (
                set::id('comment-dialog_'. $this->prop('moduleName') . '-' . $this->prop('methodName')),
                set::name('comment'),
                set::url($commentUrl),
                $isInModal ? set::load('modal') : null,
            ) : null
        );
    }
}
