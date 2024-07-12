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
        $form = $this->initForm('project', 'browse','appIframe-project');
        $featureBar = (array)$this->lang->project->featureBar;
        $featureBar['browse'] = (array)$featureBar['browse'];
        $form->dom->btn($featureBar['browse']['more'])->click();
        $form->dom->closed->click();
        $form->dom->activeBtn->click();
        $form->wait(1);

        $form->dom->activeProject->click();
        $form->wait(1);

        return $this->success();
    }

}
