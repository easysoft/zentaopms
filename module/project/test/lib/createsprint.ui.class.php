<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createSprintTester extends tester
{
    /**
     * Check the page input when creating the sprint.
     * 创建迭代时检查页面输入
     *
     * @param  array $sprint
     * @access public
     */
    public function checkInput(array $sprint)
    {
        $sprintForm = $this->initForm('project', 'execution', array('status' => 'undone', 'projectID' => '1'), 'appIframe-project');
        $sprintForm->dom->addSprint->click();
        $form = $this->initForm('execution', 'create', array('projectID' => '1'));
        if(isset($sprint['name']))    $form->dom->name->setValue($sprint['name']);
        if(isset($sprint['project'])) $form->dom->project->picker($sprint['project']);
        if(isset($sprint['end']))     $form->dom->end->datepicker($sprint['end']);
