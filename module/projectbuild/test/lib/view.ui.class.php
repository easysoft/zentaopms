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
         $viewPage = $this->loadPage('projectbuild', 'view');
         $viewPage->wait(2);
         $viewPage->dom->basic->click();
         $viewPage->wait(2);
         $basicbuildName = $viewPage->dom->basicBuildName->getText();
         $basicProduct   = $viewPage->dom->basicProduct->getText();
         $basicExecution = $viewPage->dom->basicExecution->getText();

         //断言检查版本详情页显示是否正确
         if($buildNameBrowse != $basicbuildName) return $this->failed('项目版本详情页名称显示不正确');
         if($productBrowse != $basicProduct)     return $this->failed('项目版本详情页所属产品显示不正确');
         if($executionBrowse != $basicExecution) return $this->failed('项目版本详情页所属执行显示不正确');
         return $this->success('项目版本详情查看成功');
     }
}
