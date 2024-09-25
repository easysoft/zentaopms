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
