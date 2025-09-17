<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class startProjectTester extends tester
{
    /**
     * 开始项目
     * Start a project.
     *
     * @access public
     * @return object
     */
    public function startProject()
    {
        $form       = $this->initForm('project', 'browse', '', 'appIframe-project');
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['wait'])->click();
        $title = $form->dom->projectName->getText();
        $form->dom->startBtn->click();
        $form->wait(1);
        /*点击启动*/
        $form->dom->startProject->click();
        $form->wait(1);

        /*重新进入项目列表，按照项目名称搜索项目*/
        $form       = $this->initForm('project', 'browse', '', 'appIframe-project');
        $browsePage = $this->loadPage('project', 'browse');
        $form->dom->search(array("项目名称,=,{$title}"));
        $form->wait(1);
        //搜索的项目状态为进行中，说明启动成功
        $featureBar['index'] = (array)$featureBar['index'];
        if($form->dom->browseStatus->getText() != $featureBar['index']['doing']) return $this->fail('启动项目失败');
        return $this->success('启动项目成功');
    }

    /*
     * 挂起项目
     * Suspend a project.
     *
     * @access public
     * @return object
     */
    public function suspendProject()
    {
        $form       = $this->initForm('project', 'browse', '', 'appIframe-project');
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['doing'])->click();
        $title = $form->dom->projectName->getText();
        $form->dom->moreBtn->click();
        $form->dom->suspendBtn->click();
        $form->wait(1);

        $form->dom->suspendProject->click();
        $form->wait(1);

        /*重新进入项目列表，按照项目名称搜索项目*/
        $form       = $this->initForm('project', 'browse', '', 'appIframe-project');
        $browsePage = $this->loadPage('project', 'browse');
        $form->dom->search(array("项目名称,=,{$title}"));
        $form->wait(1);
        //搜索的项目状态为挂起，说明挂起成功
        $featureBar['index'] = (array)$featureBar['index'];
        if($form->dom->browseStatus->getText() != $featureBar['index']['suspended']) return $this->fail('挂起项目失败');
        return $this->success('挂起项目成功');
    }
}
