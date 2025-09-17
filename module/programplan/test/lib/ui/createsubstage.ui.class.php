<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createSubStageTester extends tester
{
    /**
     * Create a sub stage.
     *
     * @param  arrary    $waterfall
     * @access public
     * @return object
     */
    public function createSubStage(array $waterfall)
    {
        $programplanForm = $this->initForm('programplan', 'create' , array('projectID' => 60, 'productID' => 0, 'executionID' => 106), 'appIframe-project');

        if(isset($waterfall['name_0']))  $programplanForm->dom->name_0->setValue($waterfall['name_0']);
        if(isset($waterfall['begin_0'])) $programplanForm->dom->begin->setValue($waterfall['begin_0']);
        if(isset($waterfall['end_0']))   $programplanForm->dom->end->setValue($waterfall['end_0']);
        $programplanForm->wait(1);
        $programplanForm->dom->submitBtn->click();
        $programplanForm->wait(1);

        if($this->response('module') != 'project')
        {
             if($this->checkFormTips('programplan')) return $this->success('创建子阶段表单页提示信息正确');
             if($programplanForm->dom->nameTip)
             {
                 //检查阶段名称不能为空
                 $nameTipText = $programplanForm->dom->nameTip->getText();
                 $nameTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->name);
                 return ($nameTipText == $nameTip) ? $this->success('创建子阶段表单页提示信息正确') : $this->failed('创建子阶段表单页提示信息不正确');
             }
             if($programplanForm->dom->beginTip)
             {
                 //检查计划开始日期不能为空
                 $beginTipText = $programplanForm->dom->beginTip->getText();
                 $beginTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->begin);
                 return ($beginTipText == $beginTip) ? $this->success('创建子阶段表单页提示信息正确') : $this->failed('创建子阶段表单页提示信息不正确');
             }
             if($programplanForm->dom->endTip)
             {
                 //检查计划结束日期不能为空
                 if($waterfall['end_0'] == '')
                 {
                     $endTipText = $programplanForm->dom->endTip->getText();
                     $endTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->end);
                     return ($endTipText == $endTip) ? $this->success('创建子阶段表单页提示信息正确') : $this->failed('创建子阶段表单页提示信息不正确');
                 }
                 //检查计划开始不能大于计划完成
                 if($waterfall['begin_0'] > $waterfall['end_0'])
                 {
                     $endTipText = $programplanForm->dom->endTip->getText();
                     $endTip     = sprintf($this->lang->programplan->error->planFinishSmall, '');
                     return ($endTipText == $endTip) ? $this->success('创建子阶段表单页提示信息正确') : $this->failed('创建子阶段表单页提示信息不正确');
                 }
             }
             return $this->failed('创建子阶段表单页提示信息不正确');
        }

        $executionPage = $this->loadPage('project', 'execution');
        if($executionPage->dom->subName->getText()  != $waterfall['name_0']) return $this->failed('阶段名称错误');
        if($executionPage->dom->subBegin->getText() != $waterfall['begin_0']) return $this->failed('阶段计划开始错误');
        if($executionPage->dom->subEnd->getText()   != $waterfall['end_0']) return $this->failed('阶段计划完成错误');

        return $this->success('创建子阶段成功');
    }

}
