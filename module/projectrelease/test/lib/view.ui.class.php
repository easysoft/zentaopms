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
         //在详情页面，检查字段信息显示是否正确
         $viewPage = $this->initForm('projectrelease', 'view', array('projectID' => 1), 'appIframe-project');
         $viewPage->dom->basic->click();
         $basicReleaseName = $viewPage->dom->basicreleasename->getText();
         $basicStatus      = $viewPage->dom->basicstatus->getText();
         $basicPlanDate    = $viewPage->dom->basicplandate->getText();

         //断言检查发布是否发布成功
         if($releaseNameBrowse != $basicReleaseName) return $this->failed('项目发布详情名称显示不正确');
         if($releaseStatusBrowse != $basicStatus)    return $this->failed('项目发布详情状态显示不正确');
         if($planDateBrowse != $basicPlanDate)       return $this->failed('项目发布详情计划时间显示不正确');
         return $this->success('项目发布详情查看成功');
     }
}
