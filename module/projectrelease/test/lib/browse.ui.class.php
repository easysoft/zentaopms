<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * Check the browse page of release a project release .
     * 项目发布列表发布一个发布
     *
     * @param
     * @access public
     */
    public function releaseRelease()
    {
        $browsePage = $this->initForm('projectrelease', 'browse', array('projectID' => 1), 'appIframe-project');
        $browsePage->dom->waitTab->click();
        $releaseNameWait = $browsePage->dom->releaseName->getText();
        $browsePage->dom->releaseBtn->click();
        $browsePage->dom->releaseSubmit->click();
        $browsePage->wait(5);
        $browsePage->dom->releasedTab->click();
