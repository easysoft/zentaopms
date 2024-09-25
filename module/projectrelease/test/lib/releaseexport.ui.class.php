<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class releaseExportTester extends tester
{
    /**
     * Projectrelease export HTML.
     * 项目发布导出
     *
     * @param  array $release
     * @access public
     */
    public function releaseExport($release = array())
    {
        $form = $this->initForm('projectrelease', 'view', array('releaseID' => 1), 'appIframe-project');
        $form->dom->exportBtn->click();
        if(isset($release['filename']))   $form->dom->fileName->setValue($release['filename']);
        if(isset($release['exportdata'])) $form->dom->exportData->picker($release['exportdata']);

        $form->dom->exportBtnAlert->click();
        $form->wait(2);

        if($form->dom->exportBtnAlert) return $this->failed('项目发布导出失败');
        return $this->success('项目发布导出成功');
    }

    /**
     * Check required tips of projectrelease export.
     * 项目发布导出时文件名为空时的必填校验检查
     *
     * @access public
     * @return object
     */
    public function exportWithNoFilename()
    {
        $form = $this->initForm('projectrelease', 'view', array('releaseID' => 1), 'appIframe-project');
        $form->dom->exportBtn->click();
        $form->dom->exportBtnAlert->click();
        $form->wait(2);

        /*点击导出按钮后，检查必填校验*/
        if($form->dom->alertModal('text') === '『文件名』不能为空。') return $this->success('项目发布导出必填提示信息正确');
        return $this->failed('项目发布导出必填提示信息不正确');
    }
}
