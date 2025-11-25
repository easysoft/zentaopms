<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createReleaseTeaster extends Tester
{
    /**
     * Check create release page planDate fields display.
     *
     * @param  string $releaseName
     * @param  string $releaseStatus
     * @access public
     * @return object
     */
    public function createRelease($releaseName, $releaseStatus)
    {
        /* 提交表单*/
        $form = $this->initForm('release', 'create', array('productID' => 1), 'appIframe-product');
        $form->dom->name->setValue($releaseName);
        $form->dom->status->picker($releaseStatus);

        $form->dom->btn($this->lang->save)->click();

        /* 查看跳转页面*/
        $viewPage = $this->loadPage('release', 'view');
        $viewPage->wait(3);
        $viewPage->dom->releaseInfo->click();

        /*检查发布信息*/
        if($viewPage->dom->basicreleasename->getText() != $releaseName) return $this->failed('发布名称错误');
        if($viewPage->dom->releasedStatus->getText() != $releaseStatus) return $this->failed('发布状态错误');

        return $this->success('创建'.$releaseStatus.'发布成功');
    }
}
