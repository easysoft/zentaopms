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
        'formName: string'   // 表单名称。
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
        jsVar('addFile',          $lang->doc->addFile);
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

        $formName = $this->prop('formName') ? $this->prop('formName') : 'deliverable';

        return zui::deliverableList
        (
            set::formName($formName),
            set::items($this->prop('items')),
            set::docPicker(array('placeholder' => $lang->doc->selectDoc, 'items' => helper::createLink('doc', 'ajaxGetMineDocs', 'keyword={search}'))),
            set::getFileActions(jsRaw('window.getDeliverableFileActions')),
            set::getDocActions(jsRaw('window.getDocActions')),
            set::getEmptyActions(jsRaw('window.getDeliverableActions'))
        );
    }
}
