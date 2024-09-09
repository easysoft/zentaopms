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
        $releaseNameReleased = $browsePage->dom->releaseName->getText();

        //断言检查发布是否发布成功
        if($releaseNameWait != $releaseNameReleased) return $this->failed('项目发布列表页发布发布失败');
        return $this->success('发布成功');
    }

    /**
     * Check the browse page of terminate a project release .
     * 项目发布列表停止一个发布
     *
     * @param  array $release
     * @access public
     */
    public function terminateRelease()
    {
        $browsePage = $this->initForm('projectrelease', 'browse', array('projectID' => 1), 'appIframe-project');
        $browsePage->dom->releasedTab->click();
        $releaseNameReleased = $browsePage->dom->releaseName->getText();
        $browsePage->dom->terminateBtn->click();
        $browsePage->dom->terminateConfirm->click();
        $browsePage->dom->terminatedTab->click();
        $releaseNameTerminated = $browsePage->dom->releaseName->getText();

        //断言是否检查停止维护发布成功
        if($releaseNameReleased != $releaseNameTerminated) return $this->failed('项目发布列表页停止发布失败');
        return $this->success('停止维护发布成功');
    }

    /**
     * Check the browse page of active a project release .
     * 项目发布列表激活一个发布
     *
     * @param  array $release
     * @access public
     */
    public function activeRelease()
    {
        $browsePage = $this->initForm('projectrelease', 'browse', array('projectID' => 1), 'appIframe-project');
        $browsePage->dom->terminatedTab->click();
        $releaseNameTerminated = $browsePage->dom->releaseName->getText();
        $browsePage->dom->activeBtn->click();
        $browsePage->dom->activeConfirm->click();
        $browsePage->dom->releasedTab->click();
        $releaseNameReleased = $browsePage->dom->releaseName->getText();

        //断言是否检查激活发布成功
        if($releaseNameReleased != $releaseNameTerminated) return $this->failed('项目发布列表页激活发布失败');
        return $this->success('激活发布成功');
    }
}
