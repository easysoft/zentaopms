<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editTester extends tester
{
    /**
     * 编辑测试单。
     * Edit a testtask.
     *
     * @param  array  $testtask
     * @access public
     * @return void
     */
    public function edit($testtask)
    {
        $form = $this->initForm('testtask', 'edit', array('taskID' => '1'), 'appIframe-qa');
        if(isset($testtask['execution'])) $form->dom->execution->picker($testtask['execution']);
        if(isset($testtask['build']))     $form->dom->build->picker($testtask['build']);
        if(isset($testtask['begin']))     $form->dom->begin->setValue($testtask['begin']);
        if(isset($testtask['end']))       $form->dom->end->setValue($testtask['end']);
        if(isset($testtask['name']))      $form->dom->name->setValue($testtask['name']);
        $form->dom->submitBtn->click();
        $form->wait(1);
        if(!isset($testtask['build']) || $testtask['build'] == '')
        {
            if($form->dom->buildTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->build)) return $this->failed('提测构建为空时提示信息错误');
            return $this->success('提测构建为空时提示信息正确');
        }
        if(!isset($testtask['begin']) || $testtask['begin'] == '')
        {
            if($form->dom->beginTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->begin)) return $this->failed('开始日期为空时提示信息错误');
            return $this->success('开始日期为空时提示信息正确');
        }
        if(!isset($testtask['end']) || $testtask['end'] == '')
        {
            if($form->dom->endTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->end)) return $this->failed('结束日期为空时提示信息错误');
            return $this->success('结束日期为空时提示信息正确');
        }
        if(!isset($testtask['name']) || $testtask['name'] == '')
        {
            if($form->dom->nameTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->name)) return $this->failed('测试单名称为空时提示信息错误');
            return $this->success('测试单名称为空时提示信息正确');
        }
        if(!isset($testtask['begin']) || isset($testtask['end']) && $testtask['begin'] > $testtask['end'])
        {
            if($form->dom->endTip->getText() != sprintf($this->lang->error->ge, $this->lang->testtask->end, $this->lang->testtask->begin)) return $this->failed('开始日期大于结束日期时提示信息错误');
            return $this->success('开始日期大于结束日期时提示信息正确');
        }
        $browseForm = $this->initForm('testtask', 'browse', array('product' => '1'), 'appIframe-qa');
        if($browseForm->dom->firstName->getText() != $testtask['name']) return $this->failed('编辑测试单后，测试单名称错误');
        return $this->success('编辑测试单成功');
    }
}
