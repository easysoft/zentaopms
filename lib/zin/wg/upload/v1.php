<?php
declare(strict_types=1);
namespace zin;

class upload extends wg
{
    protected static array $defineProps = array(
        'name: string="files[]"',          // 字段名
        'id?: string',                     // 元素 ID
        'icon?: string',                   // 文件图标
        'showIcon?: bool=true',            // 是否展示文件图标
        'showSize?: bool=true',            // 是否展示文件大小
        'multiple?: bool=true',            // 是否启用多文件上传
        'listPosition?: string="bottom"',  // 文件列表位置
        'uploadText?: string',             // 上传按钮文本
        'uploadIcon?: string',             // 上传按钮图标
        'renameBtn?: bool=true',           // 是否启用重命名按钮
        'renameIcon?: string',             // 重命名图标
        'renameText?: string',             // 重命名文本
        'renameClass?: string',            // 重命名按钮类
        'deleteBtn?: bool=true',           // 是否启用删除按钮
        'deleteIcon?: string',             // 删除图标
        'deleteText?: string',             // 删除文本
        'deleteClass?: string',            // 删除按钮类
        'confirmText?: string',            // 确认按钮文本
        'cancelText?: string',             // 取消按钮文本
        'useIconBtn?: string',             // 是否启用图标按钮
        'tip?: string',                    // 提示文本
        'btnClass?: string',               // 上传按钮类
        'onAdd?: callable',                // 添加文件回调
        'onDelete?: callable',             // 删除文件回调
        'onRename?: callable',             // 重命名文件回调
        'onSizeChange?: callable',         // 文件大小变更回调
        'draggable?: bool=true',           // 是否启用拖拽上传
        'limitCount?: int',                // 上传文件数量限制
        'accept?: string',                 // input accept 属性
        'defaultFileList?: object[]',      // 默认文件列表
        'limitSize?: false|string=false',  // 上传尺寸限制
        'duplicatedHint?: string',         // 文件名重复提示
        'exceededSizeHint?: string',       // 上传超出大小限制提示
        'exceededCountHint?: string'       // 上传超出个数限制提示
    );

    protected function build(): zui
    {
        global $lang, $app;

        if(!$this->prop('class')) $this->setProp('class', 'w-full');
        if(!$this->prop('tip'))   $this->setProp('tip', sprintf($lang->noticeDrag, strtoupper(ini_get('upload_max_filesize'))));
        if($this->prop('limitCount') && !$this->prop('exceededCountHint'))
        {
            $app->loadLang('file');
            $this->setProp('exceededCountHint', sprintf($lang->file->errorFileCount, $this->prop('limitCount')));
        }

        $otherProps = $this->getRestProps();
        return zui::upload(inherit($this), set('_props', $otherProps));
    }
}
