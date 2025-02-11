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

    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .history-panel-action blockquote.original {display:none}
        .history-panel-action blockquote {padding: 5px 5px 5px 10px; margin: 5px 0 0; background: var(--color-surface)}
        .history-panel-action .history-panel-action-comment button + div {max-width: 98%;}
        CSS;
    }

    protected function onCheckErrors(): array | null
    {
        if(empty($this->prop('objectID'))) return array('The property "objectID" of widget "history" is undefined.');
        return null;
    }

    protected function created()
    {
        global $app, $lang;

        if(!$this->hasProp('title'))      $this->setProp('title',      $lang->history);

        if(!$this->hasProp('commentBtn'))
        {
            if(!isset($lang->action->create)) $app->loadLang('action');
            $this->setProp('commentBtn', $lang->action->create);
        }

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
            foreach($actions as $action)
            {
                if(!empty($action->comment)) $action->comment = htmlentities($action->comment);
            }
            $this->setProp('actions', $actions);
        }
    }

    protected function build()
    {
        list($panel, $objectID, $objectType, $title, $actions, $commentUrl, $editCommentUrl, $commentBtn) = $this->prop(array('panel', 'objectID', 'objectType', 'title', 'actions', 'commentUrl', 'editCommentUrl', 'commentBtn'));

        $canComment     = hasPriv('action', 'comment', null);
        $canEditComment = hasPriv('action', 'editComment', null);

        $className = $this->props->class->toStr();
        if($panel && empty($className)) $className = 'canvas py-1 px-2 overflow-visible';
        $className .= ' break-all';
        $fileListProps = array();
        $fileListProps['fileUrl']          = '#file?id={id}';
        $fileListProps['hoverItemActions'] = true;

        global $lang, $app;
        $app->control->loadModel('file');

        $previewLink = $downloadLink = '';
        $canDownload = common::hasPriv('file', 'download');
        if($canDownload)
        {
            $previewLink = helper::createLink('file', 'download', "fileID={id}&mouse=left");
            $downloadLink  = helper::createLink('file', 'download', "fileID={id}");
            $downloadLink .= strpos($downloadLink, '?') === false ? '?' : '&';
            $downloadLink .= session_name() . '=' . session_id();
        }
        $fileListProps['fileActions'] = jsCallback('file')
            ->const('previewLang', $lang->file->preview)
            ->const('downloadLang', $lang->file->download)
            ->const('previewLink', $previewLink)
            ->const('downloadLink', $downloadLink)
            ->const('libreOfficeTurnon', isset($this->config->file->libreOfficeTurnon) && $this->config->file->libreOfficeTurnon == 1)
            ->do("
            let fileActions = [];

            let canPreview       = false;
            let officeTypes      = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
            let isOfficeFile     = officeTypes.includes(file.extension);
            let previewExtension = 'txt|jpg|jpeg|gif|png|bmp|mp4';
            if(previewExtension.includes(file.extension)) canPreview = true;
            if(libreOfficeTurnon && isOfficeFile)         canPreview = true;
            if(canPreview)
            {
                let previewAction = {icon: 'eye', title: previewLang, url: previewLink.replace('{id}', file.id).replace('\\', ''), className: 'text-primary', target: '_blank'};
                if(isOfficeFile)
                {
                    previewAction['data-toggle'] = 'modal';
                    previewAction['data-size'] = 'lg';
                }
                fileActions.push(previewAction);
            }

            fileActions.push({icon: 'download', title: downloadLang, url: downloadLink.replace('{id}', file.id).replace('\\', ''), className: 'text-primary', target: '_blank'});
            return fileActions;
        ");

        return zui::historyPanel
        (
            set::className($className),
            set::objectID((int)$objectID),
            set::objectType($objectType),
            set::actions($actions),
            set::title($title),
            set::fileListProps($fileListProps),
            $canEditComment ? set::editCommentUrl($editCommentUrl) : null,
            $canComment ? set::commentUrl($commentUrl) : null,
            set::commentBtn($canComment && !common::isTutorialMode() ? $commentBtn : false),
            set($this->getRestProps())
        );
    }
}
