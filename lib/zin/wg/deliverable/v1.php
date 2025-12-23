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
        'items: array',       // 交付物条目。
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
        $app->loadLang('deliverable');
        jsVar('createByTemplate', $lang->deliverable->createByTemplate);
        jsVar('createDoc',        $lang->doc->create);
        jsVar('uploadFile',       $lang->doc->uploadFile);
        jsVar('deleteItem',       $lang->delete);
        jsVar('otherLang',        $lang->other);
        jsVar('canDownload',      hasPriv('file', 'download'));
        jsVar('canCreateDoc',     hasPriv('doc', 'create'));

        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    /**
     * 获取CSS。
     * Get page CSS.
     *
     * @static
     * @access public
     * @return string
     */
    public static function getPageCss(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
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
        $extraCategory = $this->prop('extraCategory') ? $this->prop('extraCategory') : array_column($this->prop('items'), 'category');
        $categories    = $this->prop('categories');
        $projectID     = $this->prop('projectID');
        $createDocUrl  = $this->prop('createDocUrl');
        $uploadDocUrl  = $this->prop('uploadDocUrl');

        jsVar('addFile', $isTemplate ? $lang->deliverable->files : $lang->doc->addFile);
        jsVar('createDocUrl', $createDocUrl);
        jsVar('uploadDocUrl', $uploadDocUrl);
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

        $selectDocTips = $isTemplate ? $lang->deliverable->selectDoc : $lang->deliverable->selectDocInProject;
        $docLink       = $isTemplate ? helper::createLink('doc', 'ajaxGetTemplateDocs', "keyword={search}") : helper::createLink('doc', 'ajaxGetDeliverableDocs', "keyword={search}&projectID={$projectID}");

        return zui::deliverableList
        (
            set::formName($formName),
            set::items($this->prop('items')),
            set::docPicker(array('placeholder' => $selectDocTips, 'items' => $docLink, 'cache' => false)),
            set::getFileActions(jsRaw('window.getDeliverableFileActions')),
            set::getDocActions(jsRaw('window.getDocActions')),
            set::getEmptyActions(jsRaw('window.getDeliverableActions')),
            set::maxFileSize($this->prop('maxFileSize')),
            set::isTemplate($isTemplate),
            set::extraCategory($extraCategory),
            set::categories($categories)
        );
    }
}
