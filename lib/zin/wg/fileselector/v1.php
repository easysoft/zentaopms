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
        'deleteName: string="deleteFiles"',            // 默认显示文件中被删除的文件。
        'renameName: string="renameFiles"',            // 默认显示文件中被重命名的文件。
        'extra?:string=""',                            // 根据extra筛选默认显示的文件列表
        'multiple?: bool=true',                        // 是否允许在文件选择对话框中一次性选择多个文件（需要操作系统支持）。
        'itemProps?: array|callback',                  // 文件项的属性。
        'draggable?: bool=true',                       // 是否允许拖拽。
        'fileIcons?: string|array= "paper-clip"',      // 文件图标。
        'uploadBtn?: string|array',                    // 上传按钮。
        'renameBtn?: bool|string|array|callback=true', // 重命名按钮。
        'removeBtn?: bool|string|array|callback',      // 删除按钮。
        'removeConfirm?: string|array',                // 删除确认提示。
        'maxFileSize?: int|string',                    // 限制文件大小。
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
        if(!$this->hasProp('removeBtn')) $this->setProp('removeBtn', common::hasPriv('file', 'delete'));
        if(!$this->hasProp('totalFileSize'))
        {
            $maxFileSize  = ini_get('post_max_size');

            $lastChar     = substr($maxFileSize, -1);
            $fileSizeUnit = array('K', 'M', 'G', 'T');
            if(in_array($lastChar, $fileSizeUnit)) $maxFileSize .= 'B';
            $this->setProp('totalFileSize', $maxFileSize);
        }
        if(!$this->hasProp('maxFileSize'))
        {
            $maxFileSize  = ini_get('upload_max_filesize');
            $lastChar     = substr($maxFileSize, -1);
            $fileSizeUnit = array('K', 'M', 'G', 'T');
            if(in_array($lastChar, $fileSizeUnit)) $maxFileSize .= 'B';
            $this->setProp('maxFileSize', $maxFileSize);
        }
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
        if(!$this->hasProp('exceededTotalSizeTip'))
        {
            global $app, $lang;
            $app->loadLang('file');
            $maxUploadSize = strtoupper(ini_get('post_max_size'));
            $this->setProp('exceededTotalSizeTip', sprintf($lang->file->errorFileSize, $maxUploadSize));
        }

        /* Auto prepend suffix "[]" to multiple mode. */
        $name = $this->prop('name');
        if(!str_ends_with($name, ']') && ($this->prop('multiple') !== false || $this->prop('maxFileCount') !== 1))
        {
            $this->setProp('name', $name . '[]');
        }

        if($this->prop('defaultFiles') && $this->prop('extra'))
        {
            $defaultFiles = array();
            foreach($this->prop('defaultFiles') as $file)
            {
                if($file->extra !== $this->prop('extra')) continue;
                $defaultFiles[] = $file;
            }
            $this->setProp('defaultFiles', $defaultFiles);
        }
        if($this->hasProp('accept'))
        {
            $accept         = explode(',', $this->prop('accept'));
            $dangers        = explode(',', $app->config->file->dangers);
            $filteredAccept = array_filter($accept, function($item) use ($dangers)
            {
                $item = strtolower($item);
                $ext  = ltrim($item, '.');
                return !in_array($ext, $dangers);
            });

            $newAccept = implode(',', $filteredAccept);
            $this->setProp('accept', $newAccept);
        }

        /* Check file type. */
        $acceptFileTypes = $this->prop('accept') ? ',' . str_replace('.', '', $this->prop('accept')) . ',' : '';
        $checkFiles = jsCallback('file')
            ->const('dangerFileTypes', ",{$app->config->file->dangers},")
            ->const('dangerFile', $lang->file->dangerFile)
            ->const('acceptFileTypes', $acceptFileTypes)
            ->do(<<<'JS'
        const typeIndex = file.name.lastIndexOf(".");
        const fileType  = "," + file.name.slice(typeIndex + 1) + ",";
        if(acceptFileTypes)
        {
            if(acceptFileTypes.indexOf(fileType) == -1)
            {
                zui.Modal.alert(dangerFile);
                return false;
            }
        }
        else if(dangerFileTypes.indexOf(fileType) > -1)
        {
            zui.Modal.alert(dangerFile);
            return false;
        }
        JS);

        /* Get onAdd function.*/
        $onAdd = $this->prop('onAdd');
        if($onAdd)
        {
            if(is_object($onAdd))
            {
                /*
                 * 获取在 ui 界面上通过 jsCallback 和 js 定义的 onAdd 函数。
                 * eg: 1. $onAdd = jsCallbakc()..;
                 *         fileSelector(set::onAdd($onAdd));
                 *     2. $onAdd = js()..;
                 *         fileSelector(set::onAdd($onAdd));
                 */
                $objectClass = get_class($onAdd);
                if($objectClass == 'zin\js')         $onAdd = $onAdd->toJS();
                if($objectClass == 'zin\jsCallback') $onAdd = $onAdd->buildBody();
                if(!is_object($onAdd)) $checkFiles = $checkFiles->do($onAdd);
            }
            else
            {
                /* 获取在 ui 界面上通过 jsRaw 定义的 onAdd 函数。 eg: fileSelector(set::onAdd(jsRaw('window.onAdd'))); */
                $onAdd      = js::value($onAdd);
                $checkFiles = $checkFiles->call($onAdd, jsRaw('file'));
            }
        }
        $checkFiles = $checkFiles->do('return file');
        $this->setProp('onAdd', $checkFiles);
    }

    /**
     * Build the widget.
     *
     * @access protected
     * @return mixed
     */
    protected function build()
    {
        return zui::fileSelector(inherit($this));
    }
}
