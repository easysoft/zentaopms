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
        $form->wait(2);

        if($form->dom->exportBtnAlert) return $this->failed('发布导出失败');
        return $this->success('发布导出成功');
    }

    /**
     * 发布导出时文件名为空时的必填校验检查。
     * Check required tips of release export.
     *
     * @access public
     * @return object
     */
    public function exportWithNoFilename()
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->dom->exportBtn->click();
