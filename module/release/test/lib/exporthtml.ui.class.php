<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class exportHtmlTester extends tester
{
    /**
     * 发布导出。
     * Release export HTML.
     *
     * @param  array $release
     * @access public
     */
    public function exportHtml($release = array())
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->dom->exportBtn->click();
        if(isset($release['filename']))   $form->dom->fileName->setValue($release['filename']);
        if(isset($release['exportdata'])) $form->dom->exportData->picker($release['exportdata']);

        $form->dom->exportBtnAlert->click();
