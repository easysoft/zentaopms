<?php
declare(strict_types=1);
namespace zin;

class fileSelector extends wg
{
     /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'name?: string="files[]"',                     // 作为表单字段的名称。
        'accept?: string',                             // 限制文件类型。
        'disabled?: bool',                             // 是否禁用。
        'mode?: string="box"',                         // 界面模式，包括 'button'、'box'、'grid'
        'tip?: string',                                // 提示信息。
        'thumbnail?: bool=true',                       // 是否显示缩略图。
        'gridWidth?: string|int',                      // 网格模式的宽度。
        'gridHeight?: string|int',                     // 网格模式的高度。
        'gridGap?: string|int',                        // 网格模式的间距。
        'defaultFiles?: array[]',                      // 默认显示的文件列表。
        'multiple?: bool=true',                        // 是否允许在文件选择对话框中一次性选择多个文件（需要操作系统支持）。
        'itemProps?: array|callback',                  // 文件项的属性。
        'draggable?: bool=true',                       // 是否允许拖拽。
        'fileIcons?: string|array= "paper-clip"',      // 文件图标。
        'uploadBtn?: string|array',                    // 上传按钮。
        'renameBtn?: bool|string|array|callback=true', // 重命名按钮。
        'removeBtn?: bool|string|array|callback=true', // 删除按钮。
        'removeConfirm?: string|array',                // 删除确认提示。
        'maxFileSize?: int|string="100MB";',           // 限制文件大小。
        'maxFileCount?: int=0',                        // 限制文件数目，如果设置为非大于 `0` 的数则不限制。
        'totalFileSize?: int|string',                  // 限制总文件大小，如果设置为非大于 `0` 的数则不限制。
        'allowSameName?: bool;',                       // 是否允许同名文件。
        'duplicatedTip?: string|array',                // 重名提示。
        'exceededSizeTip?: string|array',              // 超出大小提示。
        'exceededTotalSizeTip?: string|array',         // 超出总大小提示。
        'exceededCountTip?: string|array',             // 超出数量提示。
        'onSelect?: callback',                         // 选择文件时的回调。
        'onAdd?: callback',                            // 添加文件时的回调。
        'onRemove?: callback',                         // 删除文件时的回调。
        'onRename?: callback',                         // 重命名文件时的回调，返回 `false` 取消重命名。
        'onDuplicated?: callback',                     // 重名时的回调，返回 `true` 保留重复文件。
        'onExceededSize?: callback',                   // 超出大小时的回调，返回 `true` 保留超出大小文件。
        'onExceededTotalSize?: callback',              // 超出总大小时的回调，返回 `true` 保留超出总大小文件。
        'onExceededCount?: callback'                   // 超出数量时的回调，返回 `true` 保留超出数量文件。
    );

    protected function created()
    {
        if(!$this->hasProp('tip'))
        {
            global $lang;
            $this->setProp('tip', sprintf($lang->noticeDrag, '{maxFileSize}'));
        }
        if(!$this->hasProp('exceededCountTip'))
        {
            global $app, $lang;
            $app->loadLang('file');
            $this->setProp('exceededCountHint', sprintf($lang->file->errorFileCount, '{maxCount}'));
        }
    }

    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        return zui::fileSelector(inherit($this));
    }
}
