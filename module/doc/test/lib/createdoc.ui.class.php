<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 创建草稿文档。
     * Create a draft.
     *
     * @param  string $draftName
     * @access public
     * @return void
     */
    public function createDraft($draftName)
    {
        $this->openUrl('doc', 'myspace', array('objectType' => 'mine'));
        $form = $this->loadPage('doc', 'myspace', array('objectType' => 'mine'));
        $form->dom->createDocBtn->click();
        $form->dom->showTitle->setValue($draftName->dftName);
        $form->dom->saveDraftBtn->click();
        $this->openUrl('doc', 'myspace', array('objectType' => 'mine'));
        $form = $this->loadPage('doc', 'myspace', array('objectType' => 'mine'));
        $form->dom->search(array("文档标题,=,{$draftName->dftName}"));
        $form->wait(1);

        if($form->dom->fstDocLabel->getText() != '草稿') return $this->failed('创建草稿失败');
        return $this->success('创建草稿成功');
    }

    /**
     * 创建文档。
     * Create a doc.
     *
     * @param  string $docName
     * @access public
     * @return void
     */
    public function createDoc($docName)
    {
        $this->openUrl('doc', 'mySpace', array('objectType' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('objectType' => 'mine'));
        $form->dom->createDocBtn->click();
        $form->wait(1);
        $form->dom->showTitle->setValue($docName->dcName);
        $form->dom->saveBtn->click();
        $form->wait(1);
        $form->dom->releaseBtn->click();

        $this->openUrl('doc', 'mySpace', array('objectType' => 'createdby'));
        $form = $this->loadPage('doc', 'mySpace', array('objectType' => 'createdby'));
        $form->dom->search(array("文档标题,=,{$docName->dcName}"));
        $form->wait(1);

        if($form->dom->fstDocName->getText() != $docName->dcName) return $this->failed('创建文档失败');
        return $this->success('创建文档成功');
    }

    /*
     * 创建产品文档。
     * Create a product doc.
     *
     * @param  string $productName
     * @param  string $docName
     * @access public
     * @return void
     */
    public function createProductDoc($productName, $docName)
    {
        /*创建两个产品*/
        $form = $this->initForm('product', 'create');
        $form->dom->name->setValue($productName->fstProduct);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        $form = $this->initForm('product', 'create');
        $form->dom->name->setValue($productName->secProduct);
        $form->dom->btn($this->lang->save)->click();

        /*创建产品下的文档*/
        $this->openUrl('doc', 'productSpace');
        $form = $this->loadPage('doc', 'productSpace');
        $form->dom->createDocBtn->click();
        $form->wait(1);
        $form->dom->showTitle->setValue($docName->dcName);
        $form->dom->saveBtn->click();
        $form->wait(1);
        $form->dom->product->picker($productName->secProduct);
        $form->dom->releaseBtn->click();

        if($form->dom->leftListHeader->getText() != $productName->secProduct) return $this->failed('创建产品文档失败');
        return $this->success('创建产品文档成功');
    }

    /*
     * 创建项目文档。
     * Create a project doc.
     *
     * @param string $projectName
     * @param string $executionName
     * @param string $plan
     * @param string $docName
     * @access public
     * @return void
     */
    public function createProjectDoc($projectName, $executionName, $plan, $docName)
    {
        /*创建项目*/
        $form = $this->initForm('project', 'create', array('modal' => 'scrum'));
        $form->dom->hasProduct0->click();
        $form->dom->name->setValue($projectName->fstProject);
        $form->dom->begin->datePicker($plan->begin);
        $form->dom->longTime->click();
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /*创建项目下的执行*/
        $form = $this->initForm('execution', 'create');
        $form->dom->project->picker($projectName->fstProject);
        $form->dom->name->setValue($executionName->fstExecution);
        $form->dom->begin->datePicker($plan->begin);
        $form->dom->end->datePicker($plan->end);
        $form->dom->btn($this->lang->save)->click();

        /*创建项目空间下的文档*/
        $this->openUrl('doc', 'projectSpace');
        $form = $this->loadPage('doc', 'projectSpace');
        $form->dom->createDocBtn->click();
        $form->wait(1);
        $form->dom->showTitle->setValue($docName->dcName);
        $form->dom->saveBtn->click();
        $form->wait(1);
        $form->dom->project->picker($projectName->fstProject);
        $form->dom->execution->picker($executionName->fstExecution);
        $form->wait(1);
        $form->dom->releaseBtn->click();

        /*搜索文档*/
        $this->openUrl('doc', 'projectSpace');
        $form = $this->loadPage('doc', 'projectSpace');
        $form->dom->search(array("文档标题,=,{$docName->dcName}"));
        $form->wait(1);

        if($form->dom->fstDocName->getText() != $docName->dcName) return $this->failed('创建项目文档失败');
        return $this->success('创建项目文档成功');
    }

    /*
     * 创建团队文档。
     * Create a team doc.
     *
     * @param  string $teamSpace
     * @param  string $teamLib
     * @param  string $docName
     * @access public
     * @return void
     */
    public function createTeamDoc($teamSpace, $teamLib, $docName)
    {
        /*创建团队文档库*/
        $this->openUrl('doc', 'teamSpace');
        $form = $this->loadPage('doc', 'teamSpace');
        $form->dom->createLibBtn->click();
        $form->wait(1);
        $form->dom->spaceName->setValue($teamSpace->spaceName);
        $form->dom->name->setValue($teamLib->libName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /*创建团队文档*/
        $form->dom->createDocBtn->click();
        $form->wait(1);
        $form->dom->showTitle->setValue($docName->dcName);
        $form->dom->saveBtn->click();
        $form->wait(1);
        $form->dom->releaseBtn->click();

        $this->openUrl('doc', 'teamSpace');
        $form->dom->search(array("文档标题,=,{$docName->dcName}"));
        $form->wait(1);
        if($form->dom->fstDocName->getText() != $docName->dcName) return $this->failed('创建团队文档失败');
        return $this->success('创建团队文档成功');
    }
}
