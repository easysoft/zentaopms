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

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($project);
    }

    /**
     * 创建项目后结果检查。
     * Check the result after creating the project.
     *
     * @param  string $project
     * @access public
     * @return object
     */
    public function checkresult($project = array())
    {
        /* 检查创建页面时的提示信息 */
        $form = $this->loadpage('project', 'create');
        if(!is_object($form->dom->tip))
        {
            if($this->checkformtips('project')) return $this->success('创建项目表单页提示信息正确');
            if($form->dom->endtip)
            {
                /* 检查结束日期不能为空 */
                $endtiptext = $form->dom->endtip->gettext();
                $endtip     = sprintf($this->lang->copyproject->endtip,'');
                return ($endtiptext == $endtip) ? $this->success('创建项目表单页提示信息正确') : $this->failed('创建项目表单页提示信息不正确');
                form->wait(1);
            }
            return $this->failed('创建项目表单页提示信息不正确');
        }
        /* 检查创建成功后的提示信息 */
        if(is_object($form->dom->tip))
        {
           /* 跳转到项目列表页面，按照项目名称进行搜索 */
            $browsepage = $this->initform('project', 'browse');
            $browsepage->dom->search($searchlist = array("项目名称,包含, {$project['name']}"));
            $browsepage->wait(2);

            /* 断言检查名称是否正确 */
            if($browsepage->dom->projectnamelite->gettext() != $project['name']) return $this->failed('名称错误');
            return $this->success('创建项目成功');
        }
    }
}
