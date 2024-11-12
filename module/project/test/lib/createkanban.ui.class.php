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
        $browsePage->dom->settings->click();
        $viewPage = $this->loadPage('project', 'view');
        $viewPage->wait(2);

        // 断言检查名称、项目类型是否正确
        if($viewPage->dom->projectName->getText() != $kanban['name']) return $this->failed('名称错误');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->category->getText() != $categoryLang[$kanban['type']]) return $this->failed('类型错误');
        // 检查日期是否正确，如果是产品型项目，就使用hasprojectend元素，因为产品型项目比项目型项目多了一个关联产品区块，所以“计划完成”的元素不能通用
        if($categoryLang[$kanban['type']] == '产品型')
        {
            if(isset($kanban['end']))
            {
                $endtext = $viewPage->dom->hasproductend->getText();
                if($endtext != $kanban['end']) return $this->failed('日期错误');
            }
            else
            {
                $endtext = trim($viewPage->dom->hasproductend->getText());
                if($endtext != '长期') return $this->failed('日期错误');
            }
        }
        // 如果是项目型项目，就使用noproductend元素
        else
        {
            if(isset($kanban['end']))
            {
                $endtext = $viewPage->dom->noproductend->getText();
                if($endtext != $kanban['end']) return $this->failed('日期错误');
            }
            else
            {
                $endtext = trim($viewPage->dom->noproductend->getText());
                if($endtext != '长期') return $this->failed('日期错误');
            }
        }

        return $this->success();
        }
    }
