<?php
declare(strict_types=1);
namespace zin;

class uploadImgs extends wg
{
    protected static array $defineProps = array(
        'name: string',                    // 字段名
        'showSize?: bool=true',            // 是否展示文件大小
        'multiple?: bool=true',            // 是否启用多文件上传
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
        'tip?: string',                    // 提示文本
        'btnClass?: string',               // 上传按钮类
        'onAdd?: callable',                // 文件变更回调
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
        'totalCountText?: string',         // 文件数量文本
    );

    protected function build(): zui
    {
        return zui::uploadImgs(inherit($this));
    }
}
