<?php
declare(strict_types=1);
namespace zin;

class uploadImgs extends wg
{
    protected static $defineProps = array(
        'name: string',                    // 字段名
        'icon?: string',                   // 文件图标
        'showIcon?: bool=true',            // 是否展示文件图标
        'showSize?: bool=true',            // 是否展示文件大小
        'multiple?: bool=true',            // 是否启用多文件上传
        'listPosition?: string="bottom"',  // 文件列表位置
        'uploadText?: string',             // 上传按钮文本
        'renameBtn?: bool=true',           // 是否启用重命名按钮
        'renameIcon?: string',             // 重命名图标
        'renameText?: string',             // 重命名文本
        'deleteBtn?: bool=true',           // 是否启用删除按钮
        'deleteIcon?: string',             // 删除图标
        'deleteText?: string',             // 删除文本
        'confirmText?: string',            // 确认按钮文本
        'cancelText?: string',             // 取消按钮文本
        'tip?: string',                    // 提示文本
        'btnClass?: string',               // 上传按钮类
        'onChange?: callable',             // 文件变更回调
        'onDelete?: callable',             // 文件删除回调
        'onRename?: callable',             // 文件重命名回调
        'onSizeChange?: callable',         // 文件大小变更回调
        'draggable?: bool',                // 是否启用拖拽上传
        'limitCount?: int',                // 上传文件数量限制
        'accept?: string',                 // input accept 属性
        'defaultFileList?: object[]',      // 默认文件列表
        'limitSize?: false|string=false',  // 上传尺寸限制
        'duplicatedHint?: string',         // 文件名重复提示
        'exceededSizeHint?: string',       // 上传超出大小限制提示
        'exceededCountHint?: string',      // 上传超出个数限制提示
        'commentText?: string',            // 备注文本
        'addImgsText?: string',            // 添加图片文本
        'toUploadText?: string',           // 待上传文本
        'totalSizeText?: string',          // 总大小文本
        'handleUpload?: callable',         // 处理上传函数
    );

    protected function build()
    {
        return zui::uploadImgs(inherit($this));
    }
}
