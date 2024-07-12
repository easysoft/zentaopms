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
        $form->dom->moreBtn->click();
        $form->dom->closeBtn->click();
        $form->wait(1);

        $form->dom->closeProject->click();
        $form->wait(1);

        return $this->success();
    }

}
