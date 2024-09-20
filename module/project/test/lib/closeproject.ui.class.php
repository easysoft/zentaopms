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
        $form       = $this->initForm('project', 'browse', 'appIframe-project');
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['wait'])->click();
        $form->dom->moreBtn->click();
        $form->dom->closeBtn->click();
        $title = $form->dom->title->getText();
        $form->wait(1);

        $form->dom->closeProject->click();
        $form->wait(1);

        /* 点击已关闭标签进入已关闭列表，搜索关闭的项目*/
        $form->dom->search(array("{$this->lang->project->name},=,{$title}"));
        $form->wait(1);

        $featureBar['index'] = (array)$featureBar['index'];
        if($featureBar['index']['closed'] != $form->dom->browseStatus->getText()) return $this->failed('关闭项目失败');

        return $this->success('关闭项目成功');
    }

}
