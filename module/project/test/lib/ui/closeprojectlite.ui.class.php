<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class closeprojectliteTester extends tester
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
        $form = $this->loadPage('project', 'browse');
        $this->switchVision('lite');
        $this->page->wait(3);
        $form = $this->initForm('project', 'browse', '', 'appIframe-project');
        $form->wait(5);
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['doing'])->click();
        $form->wait(2);
        $form->dom->closeBtn->click();
        $title = $form->dom->title->getText();
        $form->wait(2);

        $form->dom->closeProject->click();
        $form->wait(2);

        /* 点击已关闭标签进入已关闭列表，搜索关闭的项目*/
        $form->dom->search(array("{$this->lang->project->name},=,{$title}"));
        $form->wait(4);

        $featureBar['index'] = (array)$featureBar['index'];
        if($featureBar['index']['closed'] != $form->dom->browseStatus->getText()) return $this->failed('关闭项目失败');

        return $this->success('关闭项目成功');
    }
}
