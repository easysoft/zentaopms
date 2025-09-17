<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class suspendProjectForLiteTester extends tester
{
    /*
     * 挂起运营项目
     * Suspend a project for lite.
     *
     * @access public
     * @return object
     */
    public function suspendProject()
    {
        $this->switchVision('lite');
        $this->page->wait(5)->refresh();
        $form       = $this->initForm('project', 'browse', '', 'appIframe-project');
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['wait'])->click();
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
        $form->wait(5);
        //搜索的项目状态为挂起，说明挂起成功
        $featureBar['index'] = (array)$featureBar['index'];
        if($form->dom->browseStatus->getText() != $featureBar['index']['suspended']) return $this->fail('挂起运营项目失败');
        return $this->success('挂起运营项目成功');
    }
}
