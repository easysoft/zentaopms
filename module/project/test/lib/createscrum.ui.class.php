<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createScrumTester extends tester
{
    /**
     * Check the page input when creating the scrum project.
     * 创建敏捷项目时检查页面输入
     *
     * @param  array $scrum
     * @access public
     */
    public function checkInput($scrum = array())
    {
        $form = $this->initForm('project', 'create', array('model' => 'scrum'));
        if(isset($scrum['parent'])) $form->dom->parent->picker($scrum['parent']);
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if(isset($scrum['type'])) $form->dom->btn($categoryLang[$scrum['type']])->click();
        if(isset($scrum['name'])) $form->dom->name->setValue($scrum['name']);
        if(isset($scrum['end']))  $form->dom->end->datepicker($scrum['end']);
        else $form->dom->longTime->click(); //如果设置了end时间就选择，没有设置就选择长期
        if(isset($scrum['PM']))   $form->dom->PM->picker($scrum['PM']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($scrum);
    }

    /**
     * Check the result after creating the scrum project.
     *
     * @param  string $scrum
     * @access public
     * @return object
     */
    public function checkResult($scrum = array())
    {
        //检查创建页面时的提示信息
        if($this->response('method') != 'browse')
        {
            if($this->checkFormTips('project')) return $this->success('创建敏捷项目表单页提示信息正确');
            if($form->dom->endTip)
            {
                //检查结束日期不能为空
                $endTiptext = $form->dom->endTip->getText();
                $endTip     = sprintf($this->lang->copyProject->endTip,'');
                return ($endTiptext == $endTip) ? $this->success('创建敏捷项目表单页提示信息正确') : $this->failed('创建敏捷项目表单页提示信息不正确');
                form->wait(1);
            }
            return $this->failed('创建敏捷项目表单页提示信息不正确');
        }
        //检查创建成功后的提示信息
        else
        {
        /* 跳转到项目列表页面，按照项目名称进行搜索 */
        $browsePage = $this->loadPage('project', 'browse');
        $browsePage->dom->search($searchList = array("项目名称,包含, {$scrum['name']}"));
        $browsePage->wait(2);
        $browsePage->dom->scrumName->click();
        //进入项目概况页面
        $browsePage->dom->settings->click();
        $viewPage = $this->loadPage('project', 'view');
        $viewPage->wait(2);

        //断言检查名称、项目类型是否正确
        if($viewPage->dom->projectName->getText() != $scrum['name']) return $this->failed('名称错误');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->category->getText() != $categoryLang[$scrum['type']]) return $this->failed('类型错误');
        //检查日期是否正确，如果是产品型项目，就使用hasprojectend元素，因为产品型项目比项目型项目多了一个关联产品区块，所以“计划完成”的元素不能通用
        if($categoryLang[$scrum['type']] == '产品型')
        {
            if(isset($scrum['end']))
            {
                $endtext = $viewPage->dom->hasproductend->getText();
                if($endtext != $scrum['end']) return $this->failed('日期错误');
            }
            else
            {
                $endtext = trim($viewPage->dom->hasproductend->getText());
                if($endtext != '长期') return $this->failed('日期错误');
            }
        }
        //如果是项目型项目，就使用noproductend元素
        else
        {
            if(isset($scrum['end']))
            {
                $endtext = $viewPage->dom->noproductend->getText();
                if($endtext != $scrum['end']) return $this->failed('日期错误');
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
}
