<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createStageTester extends tester
{
    /**
     * Create a stage.
     *
     * @param  array  $waterfall
     * @access public
     * @return object
     */
    public function createStage(array $waterfall)
    {
        $programplanForm = $this->initForm('programplan', 'create' , array('projectID' => 1), 'appIframe-project');

        //删除除需求外的其他默认阶段行
        for ($i = 6; $i > 1; $i--)
        {
            $btn = "deleteBtn_$i";
            $programplanForm->dom->$btn->click();
            $programplanForm->wait(1);
        }

        if(isset($waterfall['name_0']))  $programplanForm->dom->name_0->setValue($waterfall['name_0']);
        if(isset($waterfall['begin_0'])) $programplanForm->dom->begin->setValue($waterfall['begin_0']);
        if(isset($waterfall['end_0']))   $programplanForm->dom->end->setValue($waterfall['end_0']);
        $programplanForm->wait(1);
        $programplanForm->dom->submitBtn->click();
        $programplanForm->wait(1);

        if($this->response('module') != 'project')
        {
             if($this->checkFormTips('programplan')) return $this->success('创建阶段表单页提示信息正确');
             if($programplanForm->dom->nameTip)
             {
                //检查阶段名称不能为空
                $nameTipText = $programplanForm->dom->nameTip->getText();
                $nameTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->name);
                return ($nameTipText == $nameTip) ? $this->success('创建阶段表单页提示信息正确') : $this->failed('创建阶段表单页提示信息不正确');
             }
             if($programplanForm->dom->beginTip)
             {
                //检查计划开始日期不能为空
                $beginTipText = $programplanForm->dom->beginTip->getText();
                $beginTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->begin);
                return ($beginTipText == $beginTip) ? $this->success('创建阶段表单页提示信息正确') : $this->failed('创建阶段表单页提示信息不正确');
             }
             if($programplanForm->dom->endTip)
             {
                 //检查计划结束日期不能为空
                 if($waterfall['end_0'] == '')
                 {
                    $endTipText = $programplanForm->dom->endTip->getText();
                    $endTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->end);
                    return ($endTipText == $endTip) ? $this->success('创建阶段表单页提示信息正确') : $this->failed('创建阶段表单页提示信息不正确');
                 }
                 //检查计划开始不能大于计划完成
                 if($waterfall['begin_0'] > $waterfall['end_0'])
                 {
                    $endTipText = $programplanForm->dom->endTip->getText();
                    $endTip     = sprintf($this->lang->programplan->error->planFinishSmall, '');
                    return ($endTipText == $endTip) ? $this->success('创建阶段表单页提示信息正确') : $this->failed('创建阶段表单页提示信息不正确');
                 }
             }
             return $this->failed('创建阶段表单页提示信息不正确');
        }

        $executionPage = $this->loadPage('project', 'execution');
        if($executionPage->dom->name->getText()  != $waterfall['name_0']) return $this->failed('阶段名称错误');
        if($executionPage->dom->begin->getText() != $waterfall['begin_0']) return $this->failed('阶段计划开始错误');
        if($executionPage->dom->end->getText()   != $waterfall['end_0']) return $this->failed('阶段计划完成错误');

        return $this->success();
    }
}
