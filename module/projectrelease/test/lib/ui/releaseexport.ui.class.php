<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class releaseExportTester extends tester
{
    /**
     * 项目发布导出。
     * Projectrelease export HTML.
     *
     * @param  array $release
     * @access public
     */
    public function releaseExport($release)
    {
        $form = $this->initForm('projectrelease', 'browse', array('project' => 1), 'appIframe-project');
        $form->dom->releaseNameBrowse->click();
        $form->dom->btn($this->lang->projectrelease->export)->click();
        if(isset($release['filename']))   $form->dom->fileName->setValue($release['filename']);
        if(isset($release['exportdata'])) $form->dom->type->picker($release['exportdata']);

        $form->dom->btn($this->lang->project->export)->click();
        $form->wait(2);

        if(is_object($this->lang->project->export)) return $this->failed('项目发布导出失败');
        return $this->success('项目发布导出成功');
    }

    /**
     * 项目发布导出时文件名为空时的必填校验检查。
     * Check required tips of projectrelease export.
     *
     * @access public
     * @return object
     */
    public function exportWithNoFilename()
    {
        $form = $this->initForm('projectrelease', 'browse', array('project' => 1), 'appIframe-project');
        $form->dom->releaseNameBrowse->click();
        $form->dom->btn($this->lang->projectrelease->export)->click();
        $form->dom->btn($this->lang->project->export)->click();
        $form->wait(2);

        /*点击导出按钮后，检查必填校验*/
        if($form->dom->alertModal('text') === '『文件名』不能为空。') return $this->success('项目发布导出必填提示信息正确');
        return $this->failed('项目发布导出必填提示信息不正确');
    }
}
