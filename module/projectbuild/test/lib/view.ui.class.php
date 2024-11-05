<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class buildViewTester extends tester
{
    /**
     * Check the view page of project build.
     * 项目版本详情
     *
     * @access public
     */
     public function checkBuildView()
     {
         $browsePage      = $this->initForm('projectbuild', 'browse', array('projectID' => 1), 'appIframe-project');
         $buildNameBrowse = $browsePage->dom->buildNameBrowse->getText();
         $productBrowse   = $browsePage->dom->productBrowse->getText();
         $executionBrowse = $browsePage->dom->executionBrowse->getText();
         $browsePage->dom->buildNameBrowse->click();

         //在详情页面，获取项目版本字段信息
