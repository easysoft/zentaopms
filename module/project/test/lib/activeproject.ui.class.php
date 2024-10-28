<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class activeprojectTester extends tester
{
 /**
     * Active a project.
     *
     * @param  array    $project
     * @access public
     * @return object
     */
    public function activeProject(array $project)
    {
        $form       = $this->initForm('project', 'browse', '', 'appIframe-project');
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['more'])->click();
        $form->dom->closed->click();
        $form->dom->activeBtn->click();
        $form->wait(1);

        $title = $form->dom->title->getText();
        $form->dom->activeProject->click();
        $form->wait(1);

        /* 点击进行中标签进入进行中列表，搜索激活的项目*/
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['doing'])->click();
        $form->dom->search(array("{$this->lang->project->name},=,{$title}"));
        $form->wait(1);

        $featureBar['index'] = (array)$featureBar['index'];
        if($featureBar['index']['doing'] != $form->dom->browseStatus->getText()) return $this->failed('激活项目失败');

        return $this->success('激活项目成功');
    }

}
