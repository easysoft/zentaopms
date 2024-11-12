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
        if(isset($kanban['PM']))   $form->dom->PM->picker($kanban['PM']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($kanban);
    }

    /**
     * 创建看板项目后结果检查。
     * Check the result after creating the kanban project.
     *
     * @param  string $kanban
     * @access public
     * @return object
     */
    public function checkResult($kanban = array())
    {
        /* 检查创建页面时的提示信息 */
        if($this->response('method') != 'browse')
        {
            if($this->checkFormTips('project')) return $this->success('创建看板项目表单页提示信息正确');
            if($form->dom->endTip)
            {
                /* 检查结束日期不能为空 */
                $endTiptext = $form->dom->endTip->getText();
                $endTip     = sprintf($this->lang->copyProject->endTip,'');
                return ($endTiptext == $endTip) ? $this->success('创建看板项目表单页提示信息正确') : $this->failed('创建看板项目表单页提示信息不正确');
                form->wait(1);
            }
            return $this->failed('创建看板项目表单页提示信息不正确');
        }
        /* 检查创建成功后的提示信息 */
        else
        {
        /* 跳转到项目列表页面，按照项目名称进行搜索 */
        $browsePage = $this->loadPage('project', 'browse');
        $browsePage->dom->search($searchList = array("项目名称,包含, {$kanban['name']}"));
        $browsePage->wait(2);
        $browsePage->dom->kanbanName->click();
        // 进入项目概况页面
