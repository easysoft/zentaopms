<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createWaterfallplusTester extends tester
{
    /**
     * 创建融合瀑布项目时检查页面输入。
     * Check the page input when create the waterfallplus project.
     *
     * @param  array $waterfallPlus
     * @access public
     * @return object
     */
   public function checkInput(array $waterfallPlus)
    {
        $form = $this->initForm('project', 'create', array('model' => 'waterfallplus'), 'appIframe-project');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if(isset($waterfallPlus['parent']))   $form->dom->parent->picker($waterfallPlus['parent']);
        $form->wait(2);
        if(isset($waterfallPlus['name']))     $form->dom->name->setValue($waterfallPlus['name']);
        if(isset($waterfallPlus['type']))     $form->dom->btn($categoryLang[$waterfallPlus['type']])->click();
        if(isset($waterfallPlus['longTime'])) $form->dom->longTime->click();
        if(isset($waterfallPlus['end']))      $form->dom->end->datePicker($waterfallPlus['end']);
        if(isset($waterfallPlus['PM']))       $form->dom->PM->picker($waterfallPlus['PM']);
        if(isset($waterfallPlus['product']))  $form->dom->{'products[0]'}->picker($waterfallPlus['product']);
        $form->wait(5);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($waterfallPlus);
    }

   /**
     * 创建融合瀑布项目后结果检查。
     * Check the result after create the waterfallplus project
     *
     * @param  arrary $waterfallPlus
     * @access public
     * @return object
     */
    public function checkResult(array $waterfallPlus)
    {
        $form = $this->loadPage('programplan', 'create');
        if($this->response('module') != 'programplan')
        {
            return $this->checkFormTips('project') ? $this->success('创建融合瀑布项目表单页提示信息正确') : $this->failed('创建融合瀑布项目表单页提示信息不正确');
            if($form->dom->endTip)
            {
                //检查结束日期不能为空
                $endTipText = $form->dom->endTip->getText();
                $endTip     = sprintf($this->lang->project->copyProject->endTips,'');
                return ($endTipText == $endTip) ? $this->success('创建融合瀑布项目表单页提示信息正确') : $this->failed('创建融合瀑布项目表单页提示信息不正确');
            }
        }

       /* 跳转到项目设置页面，点击设置菜单。 */
        $programplanPage = $this->loadPage('programplan', 'create');
        $programplanPage->dom->settings->click();
        $viewPage     = $this->loadPage('project', 'view');
        $viewPage->wait(4);
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->projectName->getText() != $waterfallPlus['name'])               return $this->failed('名称错误');
        if($viewPage->dom->category->getText() != $categoryLang[$waterfallPlus['type']])   return $this->failed('类型错误');
        if($viewPage->dom->acl->getText() != $this->lang->project->shortAclList->open) return $this->failed('权限错误');
        //检查项目计划完成日期是否正确
        if($waterfallPlus['type'] == 1)
        {
            if(isset($waterfallPlus['longTime']) && trim($viewPage->dom->waterfallEnd->getText()) != $this->lang->project->longTime) return $this->failed('计划完成日期错误');
            if(isset($waterfallPlus['end']) && $viewPage->dom->waterfallEnd->getText() != $waterfallPlus['end']) return $this->failed('计划完成日期错误');
        }
        else
        {
            if(isset($waterfallPlus['longTime']) && trim($viewPage->dom->waterfallNoProductEnd->getText()) != $this->lang->project->longTime) return $this->failed('计划完成日期错误');
            if(isset($waterfallPlus['end']) && $viewPage->dom->waterfallNoProductEnd->getText() != $waterfallPlus['end']) return $this->failed('计划完成日期错误');
        }

        return $this->success('创建融合瀑布项目成功');
    }
}
