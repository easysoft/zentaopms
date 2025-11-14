<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class viewTester extends tester
{
    /**
     * 检查执行概况页面执行基础信息。
     * Check the basic information of the execution.
     *
     * @paran  array  $basic
     * @access public
     * @return object
     */
    public function checkBasic($basic)
    {
        $form = $this->initForm('execution', 'view', array('execution' => '3'), 'appIframe-execution');
        $form->wait(1);
        if($form->dom->executionName->getText() != $basic['executionName']) return $this->failed('执行名称不正确');
        if($form->dom->programName->getText() != $basic['programName'])     return $this->failed('项目集名称不正确');
        if($form->dom->projectName->getText() != $basic['projectName'])     return $this->failed('项目名称不正确');
        if($form->dom->storyNum->getText() != $basic['storyNum'])           return $this->failed('需求数不正确');
        if($form->dom->taskNum->getText() != $basic['taskNum'])             return $this->failed('任务数不正确');
        if($form->dom->bugNum->getText() != $basic['bugNum'])               return $this->failed('Bug数不正确');
        return $this->success('执行基础信息正确');
    }

    /**
     * 检查执行概况页面产品信息。
     * Check the product information of the execution.
     *
     * @param  string $product
     * @access public
     * @return object
     */
    public function checkProduct($product)
    {
        $form = $this->initForm('execution', 'view', array('execution' => '3'), 'appIframe-execution');
        $form->wait(1);
        if($form->dom->linckedProducta->getText() != $product) return $this->failed('关联产品不正确');
        $form->dom->moreProducts->click();
        $form->wait(1);
        $url = $this->response();
        if($url['module'] != 'execution')      return $this->failed('页面跳转后module不正确');
        if($url['method'] != 'manageproducts') return $this->failed('页面跳转后method不正确');
        return $this->success('产品信息正确');
    }

    /**
     * 检查执行概况页面团队成员信息。
     * Check the team members of the execution.
     *
     * @param  string $membera
     * @param  string $memberb
     * @access public
     * @return object
     */
    public function checkMember($membera, $memberb)
    {
        $form = $this->initForm('execution', 'view', array('execution' => '3'), 'appIframe-execution');
        $form->wait(1);
        if($form->dom->teamMembera->getText() != $membera) return $this->failed('团队成员不正确');
        if($form->dom->teamMemberb->getText() != $memberb) return $this->failed('团队成员不正确');
        $form->dom->moreTeamMembers->click();
        $form->wait(1);
        $url = $this->response();
        if($url['module'] != 'execution') return $this->failed('点击更多按钮，页面跳转后module不正确');
        if($url['method'] != 'team')      return $this->failed('点击更多按钮，页面跳转后method不正确');
        $form->dom->btn($this->lang->overview)->click();
        $form->wait(2);
        $form->dom->manageMembers->click();
        $form->wait(2);
        $url = $this->response();
        if($url['module'] != 'execution')     return $this->failed('点击管理按钮，页面跳转后module不正确');
        if($url['method'] != 'manageMembers') return $this->failed('点击管理按钮，页面跳转后method不正确');
        return $this->success('团队成员信息正确');
    }

    /**
     * 检查执行概况页面文档库信息。
     * Check the doclib information of the execution.
     *
     * @param  string $doclaba
     * @param  string $doclibb
     * @access public
     * @return object
     */
    public function checkDoclib($doclaba, $doclibb)
    {
        $form = $this->initForm('execution', 'view', array('execution' => '3'), 'appIframe-execution');
        $form->wait(1);
        if($form->dom->docliba->getText() != $doclaba) return $this->failed('文档库名称不正确');
        if($form->dom->doclibb->getText() != $doclibb) return $this->failed('文档库名称不正确');
        $form->dom->moreDoclibs->click();
        $form->wait(1);
        $url = $this->response();
        if($url['module'] != 'execution') return $this->failed('点击更多按钮，页面跳转后module不正确');
        if($url['method'] != 'doc')       return $this->failed('点击更多按钮，页面跳转后method不正确');
        $form->dom->btn($this->lang->settings)->click();
        $form->wait(1);
        $form->dom->createDoclib->click();
        $form->wait(1);
        if(!is_object($form->dom->doclibModal)) return $this->failed('创建文档库模态框不存在');
        return $this->success('文档库信息正确');
    }
}
