<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createWaterfallTester extends tester
{
    /**
     * Check the page jump after creating the project.
     *
     * @param  array    $waterfall
     * @access public
     * @return object
     */
   public function checkLocating(array $waterfall)
    {
        $form = $this->initForm('project', 'create', array('model' => 'waterfall'));
        if(isset($waterfall['name'])) $form->dom->name->setValue($waterfall['name']);
        if(isset($waterfall['end']))  $form->dom->end->datePicker($waterfall['end']);
        if(isset($waterfall['PM']))   $form->dom->PM->picker($waterfall['PM']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->response();
    }

   /**
     * Create a default project.
     *
     * @param  arrary    $waterfall
     * @access public
     * @return object
     */
    public function createDefault(array $waterfall)
    {
        $form         = $this->initForm('project', 'create', array('model' => 'waterfall'));
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if(isset($waterfall['parent']))   $form->dom->parent->picker($waterfall['parent']);
        if(isset($waterfall['name']))     $form->dom->name->setValue($waterfall['name']);
        if(isset($waterfall['type']))     $form->dom->btn($categoryLang[$waterfall['type']])->click();
        if(isset($waterfall['longTime'])) $form->dom->longTime->click();
        if(isset($waterfall['end']))      $form->dom->end->datePicker($waterfall['end']);
        if(isset($waterfall['PM']))       $form->dom->PM->picker($waterfall['PM']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('module') != 'programplan')
        {
            if($this->checkFormTips('project')) return $this->success('创建瀑布项目表单页提示信息正确');
            if($form->dom->endTip)
            {
                //检查结束日期不能为空
                $endTipText = $form->dom->endTipgetText();
                $endTip     = sprintf($this->lang->project->copyProject->endTips,'');
                return ($endTipText == $endTip) ? $this->success('创建瀑布项目表单页提示信息正确') : $this->failed('创建瀑布项目表单页提示信息不正确');
            }
            return $this->failed('创建瀑布项目表单页提示信息不正确');
        }

       /* 跳转到项目设置页面，点击设置菜单。 */
        $programplanPage = $this->loadPage('programplan', 'create');
        $programplanPage->dom->settings->click();

        $viewPage     = $this->loadPage('project', 'view');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->projectName->getText() != $waterfall['name'])               return $this->failed('名称错误');
        if($viewPage->dom->category->getText() != $categoryLang[$waterfall['type']])   return $this->failed('类型错误');
        if($viewPage->dom->acl->getText() != $this->lang->project->shortAclList->open) return $this->failed('权限错误');
        //检查项目计划完成日期是否正确
        if($waterfall['type'] == 1)
        {
            if(isset($waterfall['longTime']) && trim($viewPage->dom->waterfallend->getText()) != $this->lang->project->longTime) return $this->failed('计划完成日期错误');
            if(isset($waterfall['end']) && $viewPage->dom->waterfallend->getText() != $waterfall['end']) return $this->failed('计划完成日期错误');
        }
        else
        {
            if(isset($waterfall['longTime']) && trim($viewPage->dom->waterfallnoproductend->getText()) != $this->lang->project->longTime) return $this->failed('计划完成日期错误');
            if(isset($waterfall['end']) && $viewPage->dom->waterfallnoproductend->getText() != $waterfall['end']) return $this->failed('计划完成日期错误');
        }

        return $this->success();
    }
}
