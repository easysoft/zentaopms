<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'section' . DS . 'v1.php';

/**
 * 历史记录部件类。
 * The history widget class.
 *
 * @author Hao Sun
 */
class history extends wg
{
    protected static array $defineProps = array
    (
        'panel?: bool=true',            // 是否渲染为面板。
        'objectType?: string',          // 操作对象类型。
        'objectID?: int',               // 操作对象 ID。
        'actions?: array',              // 操作列表数据。
        'users?: array',                // 用户 Map 数据。
        'commentUrl?: string',          // 备注对话框 URL。
        'editCommentUrl?: string',      // 修改备注对话框 URL。
        'title?: string|array',         // 标题。
        'commentBtn?: string|array'     // 是否允许添加备注。
    );

    protected function onCheckErrors(): array | null
    {
        if(empty($this->prop('objectID'))) return array('The property "objectID" of widget "history" is undefined.');
        return null;
    }

    protected function created()
    {
        global $app, $lang;

        if(!$this->hasProp('title'))      $this->setProp('title',      $lang->history);
        if(!$this->hasProp('commentBtn')) $this->setProp('commentBtn', $lang->action->create);

        $objectType = $this->prop('objectType');
        $objectID   = $this->prop('objectID');
        $users      = $this->prop('users');
        $actions    = $this->prop('actions');
        if(empty($objectType))
        {
            $objectType = data('objectType');
            if(empty($objectType)) $objectType = $app->rawModule;
            $this->setProp('objectType', $objectType);
        }
        if(empty($objectID))
        {
            $objectID = data($objectType . 'ID');
            if(empty($objectID))
            {
                $object = data($objectType);
                if(is_object($object) && isset($object->id))      $objectID = $object->id;
                elseif(is_array($object) && isset($object['id'])) $objectID = $object['id'];
            }
            $this->setProp('objectID', $objectID);
        }
        if(empty($users))
        {
            $users = data('users');
            $this->setProp('users', $users);
        }
        if(empty($actions))
        {
            $actions = data('actions');
            if(empty($actions) && !empty($objectID)) $actions = $app->loadTarget('action')->getList($objectType, $objectID);
            if(!empty($actions))                     $actions = $app->loadTarget('action')->buildActionList($actions, $users, $this->prop('commentBtn'));
            $this->setProp('actions', $actions);
        }
    }

    protected function build(): array
    {
        list($panel, $objectID, $objectType, $title, $actions, $commentUrl, $editCommentUrl, $commentBtn) = $this->prop(array('panel', 'objectID', 'objectType', 'title', 'actions', 'commentUrl', 'editCommentUrl', 'commentBtn'));

        $canComment     = hasPriv('action', 'comment', null);
        $canEditComment = hasPriv('action', 'editComment', null);
        return array
        (
            zui::historyPanel
            (
                $panel ? set::className('canvas py-1 px-2') : null,
                set::objectID((int)$objectID),
                set::objectType($objectType),
                set::actions($actions),
                set::title($title),
                $canEditComment ? set::editCommentUrl($editCommentUrl) : null,
                $canComment ? set::commentUrl($commentUrl) : null,
                set::commentBtn($canComment ? $commentBtn : false),
                set($this->getRestProps())
            ),
            h::css('.history-panel-action blockquote.original {display:none}')
        );
    }
}
