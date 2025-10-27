<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class expectTester extends tester
{
    /**
     * Stakeholder  expectation records.
     * 干系人期望记录。
     *
     * @param  array  $stakeholder
     * @access public
     * @return object
     */
    public function expect($stakeholder)
    {
        $form = $this->initForm('stakeholder', 'browse', array('project' => 1), 'appIframe-project');
        $form->dom->expect->click();
        $expectForm = $this->loadPage('stakeholder', 'expect');
        $form->wait(1);
        if(isset($stakeholder['expectComment'])) $expectForm->dom->expectComment->setValueInZenEditor($stakeholder['expectComment']);
        if(isset($stakeholder['progress']))      $expectForm->dom->progress->setValueInZenEditor($stakeholder['progress']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->checkResult($expectForm, $stakeholder);
    }

    /**
     * Check the expectation records on the stakeholder view page.
     * 检查干系人详情页期望记录。
     *
     * @param  array  $stakeholder
     * @access public
     * @return object
     */
    public function checkResult($expectForm, $stakeholder)
    {
        if($this->response('module') == 'stakeholder')
        {
            if($expectForm->dom->expectTip)
            {
                //检查期望内容名称不能为空
                $expectTipText = $expectForm->dom->expectTip->getText();
                $expectTip     = sprintf($this->lang->error->notempty, $this->lang->stakeholder->expect);
                return ($expectTipText == $expectTip) ? $this->success('期望内容表单页提示信息正确') : $this->failed('期望内容表单页提示信息不正确');
            }
            if($expectForm->dom->progressTip)
            {
                //检查达成进展名称不能为空
                $progressTipText = $expectForm->dom->progressTip->getText();
                $progressTip     = sprintf($this->lang->error->notempty, $this->lang->stakeholder->progress);
                return ($progressTipText == $progressTip) ? $this->success('期望内容表单页提示信息正确') : $this->failed('期望内容表单页提示信息不正确');
            }
        }
        /* 干系人详情页，检查期望内容信息。*/
        $browsePage = $this->loadPage('stakeholder', 'browse');
        $browsePage->wait(1);
        $browsePage->dom->title->click();
        $viewPage = $this->loadPage('stakeholder', 'view');
        $viewPage->wait(1);

        if($viewPage->dom->expectComment->getText() != $stakeholder['expectComment']) return $this->failed('期望记录信息错误');
        if($viewPage->dom->progress->getText() != $stakeholder['progress'])           return $this->failed('期望记录信息错误');
        return $this->success('期望记录信息保存成功');
    }
}
