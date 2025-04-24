<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProjectLiteTester extends tester
{
    /**
     * 创建项目时检查页面输入。
     * Check the page input when creating the project.
     *
     * @param  array $project
     * @access public
     */
    public function checkInput($project = array())
    {
        $form = $this->loadPage('project', 'browse');
        $this->switchVision('lite');
        $form = $this->initForm('project', 'create', array('model' => 'kanban'));
        if(isset($project['name'])) $form->dom->name->setValue($project['name']);
        if(isset($project['end']))  $form->dom->end->datepicker($project['end']);
        if(isset($project['PM']))   $form->dom->PM->picker($project['PM']);
}
