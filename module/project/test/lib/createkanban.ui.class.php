<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createKanbanTester extends tester
{
    /**
     * 创建看板项目时检查页面输入。
     * Check the page input when creating the kanban project.
     *
     * @param  array $kanban
     * @access public
     */
    public function checkInput($kanban = array())
    {
        $form = $this->initForm('project', 'create', array('model' => 'kanban'));
        if(isset($kanban['parent'])) $form->dom->parent->picker($kanban['parent']);
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if(isset($kanban['type'])) $form->dom->btn($categoryLang[$kanban['type']])->click();
        if(isset($kanban['name'])) $form->dom->name->setValue($kanban['name']);
        if(isset($kanban['end']))  $form->dom->end->datepicker($kanban['end']);
        else $form->dom->longTime->click(); //如果设置了end时间就选择，没有设置就选择长期
