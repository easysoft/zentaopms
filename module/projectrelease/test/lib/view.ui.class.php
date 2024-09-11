<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class viewTester extends tester
{
    /**
     * Check the view page of project release .
     * 项目发布详情
     *
     * @param
     * @access public
     */
     public function checkReleaseView()
     {
         $browsePage          = $this->initForm('projectrelease', 'browse', array('projectID' => 1), 'appIframe-project');
         $releaseNameBrowse   = $browsePage->dom->releaseNameBrowse->getText();
         $releaseStatusBrowse = $browsePage->dom->releaseStatusBrowse->getText();
         $planDateBrowse      = $browsePage->dom->planDateBrowse->getText();
         $browsePage->dom->releaseNameBrowse->click();
