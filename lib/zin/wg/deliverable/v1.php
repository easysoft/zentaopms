<?php
declare(strict_types=1);
namespace zin;

class deliverable extends wg
{
    /**
     * 默认的组件属性。
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'items: array',      // 交付物条目。
        'formName: string',   // 表单名称。
        'maxFileSize: string' // 最大文件大小。
    );

    /**
     * 获取JS。
     * Get page JS.
     *
     * @static
     * @access public
     * @return string
     */
    public static function getPageJS(): ?string
    {
        global $lang, $app;
        $app->loadLang('doc');
        $app->loadLang('file');
        jsVar('downloadTemplate', $lang->doc->downloadTemplate);
        jsVar('deleteItem',       $lang->delete);
        jsVar('canDownload',      hasPriv('file', 'download'));

        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    /**
     * 构建组件。
     * Build.
     *
     * @access protected
     * @return zui
     */
    protected function build(): zui
    {
        global $lang, $app;
        $app->loadLang('doc');
        $app->loadLang('file');
        $app->loadLang('deliverable');

        $formName      = $this->prop('formName') ? $this->prop('formName') : 'deliverable';
        $isTemplate    = $this->prop('isTemplate') ? $this->prop('isTemplate') : false;
        $onlyShow      = $this->prop('onlyShow') ? $this->prop('onlyShow') : false;
        $extraCategory = $this->prop('extraCategory');
        $extraCategory = $extraCategory ? $extraCategory : array_column($this->prop('items'), 'category');

        jsVar('addFile', $isTemplate ? $lang->deliverable->files : $lang->doc->addFile);
        jsVar('isTemplate', $isTemplate);
        jsVar('onlyShow', $onlyShow);

        if(!$this->hasProp('maxFileSize'))
        {
            $maxFileSize  = ini_get('upload_max_filesize');
            $lastChar     = substr($maxFileSize, -1);
            $fileSizeUnit = array('K', 'M', 'G', 'T');
            if(in_array($lastChar, $fileSizeUnit)) $maxFileSize .= 'B';
            $this->setProp('maxFileSize', $maxFileSize);
        }

        $selectDocTips = $isTemplate ? $lang->deliverable->selectDoc : $lang->doc->selectDoc;
        $docLink       = $isTemplate ? helper::createLink('doc', 'ajaxGetTemplateDocs', 'keyword={search}') : helper::createLink('doc', 'ajaxGetMineDocs', 'keyword={search}');

        return zui::deliverableList
        (
            set::formName($formName),
            set::items($this->prop('items')),
            set::docPicker(array('placeholder' => $selectDocTips, 'items' => $docLink)),
            set::getFileActions(jsRaw('window.getDeliverableFileActions')),
            set::getDocActions(jsRaw('window.getDocActions')),
            set::getEmptyActions(jsRaw('window.getDeliverableActions')),
            set::maxFileSize($this->prop('maxFileSize')),
            set::isTemplate($isTemplate),
            set::extraCategory($extraCategory)
        );
    }
}
