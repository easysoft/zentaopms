<?php
declare(strict_types=1);
namespace zin;

class upload extends wg
{
    protected static $defineProps = array(
        'name: string',                    // 字段名
        'icon?: string',                   // 文件图标
        'renameBtn?: bool=true',           // 是否启用重命名按钮
        'deleteBtn?: bool=true',           // 是否启用删除按钮
        'showIcon?: bool=true',            // 是否展示文件图标
        'multiple?: bool=true',            // 是否启用多文件上传
        'listPosition?: string="bottom"',  // 文件列表位置
        'uploadText?: string',             // 上传按钮文本
        'renameText?: string',             // 重命名文本
        'deleteText?: string',             // 删除文本
        'confirmText?: string',            // 确认按钮文本
        'cancelText?: string',             // 取消按钮文本
        'tip?: string',                    // 提示文本
        'btnClass?: string',               // 上传按钮类
        'onChange?: callable',             // 文件变更回调
        'onDelete?: callable',             // 文件删除回调
        'onRename?: callable',             // 文件重命名回调
        'limitCount?: int',                // 上传文件数量限制
        'accept?: string',                 // input accept 属性
        'defaultFileList?: object[]',      // 默认文件列表
        'limitSize?: false|string=false',  // 上传尺寸限制
        'draggable?: bool',                // 是否启用拖拽上传
        'duplicatedHint?: string',         // 文件名重复提示
        'exceededSizeHint?: string',       // 上传超出大小限制提示
        'exceededCountHint?: string',      // 上传超出个数限制提示
    );

    private function setDefaultText()
    {
        global $lang;
        $this->setDefaultProps(array());
    }

    protected function build()
    {
        // $this->setDefaultText();
        return zui::upload(inherit($this));
    }
}
