<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class closeprojectTester extends tester
{
 /**
     * Close a project.
     *
     * @param  array    $project
     * @access public
     * @return object
     */
    public function closeProject(array $project)
    {
        $form = $this->initForm('project', 'browse','appIframe-project');
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['wait'])->click();
        $title = $form->dom->projectName->getText();
        $form->dom->moreBtn->click();
        $form->dom->closeBtn->click();
        $form->wait(1);

        $form->dom->closeProject->click();
        $form->wait(1);

        /* 点击已关闭标签进入已关闭列表，搜索关闭的项目*/
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['more'])->click();
        $form->dom->closed->click();
        $form->dom->search(array("项目名称,=,{$title}"));
        $form->wait(1);

        if($title != $form->dom->projectName->getText()) return $this->failed('关闭项目失败');

        return $this->success('关闭项目成功');
    }

}
