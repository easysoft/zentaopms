<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class groupTester extends tester
{
    /**
     * Create project group.
     * 创建项目权限分组
     *
     * @param  array $project
     * @access public
     */
    public function createGroup($project = array())
    {
        $form = $this->initForm('project', 'group', array('projectID' => '1'), 'appIframe-project');
        $form->dom->createGroupBtn->click();
        if(isset($project['groupname'])) $form->dom->groupName->setValue($project['groupname']);
        if(isset($project['groupdesc'])) $form->dom->groupDesc->setValue($project['groupdesc']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
