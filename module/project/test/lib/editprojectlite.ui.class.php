<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editProjectLiteTester extends tester
{
    /**
     * 编辑项目时检查页面输入。
     * Check the page input when editing the project.
     *
     * @param  array $project
     * @access public
     * @return object
     */
    public function checkInput($project = array())
    {
        $form = $this->loadPage('project', 'browse');
        $this->switchVision('lite');
        $form = $this->initForm('project', 'edit', array('projecID' => 1), 'appIframe-project');
        if(isset($project['name']))  $form->dom->name->setValue($project['name']);
        if(isset($project['begin'])) $form->dom->begin->datePicker($project['begin']);
        if(isset($project['end']))   $form->dom->end->datePicker($project['end']);
