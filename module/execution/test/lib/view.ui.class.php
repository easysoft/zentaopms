<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class viewTester extends tester
{
    /**
     * 检查执行概况页面执行基础信息
     * Check the basic information of the execution.
     *
     * @access public
     * @return object
     */
    public function checkBasic()
    {
        $form = $this->initForm('execution', 'view', array('execution' => '3'), 'appIframe-execution');
        if($form->dom->executionName->getText() != '执行') return $this->failed('执行名称不正确');
        if($form->dom->programName->getText() != '项目集') return $this->failed('项目集名称不正确');
        if($form->dom->projectName->getText() != '项目')   return $this->failed('项目名称不正确');
        if($form->dom->storyNum->getText() != '2')         return $this->failed('需求数不正确');
        if($form->dom->taskNum->getText() != '11')         return $this->failed('任务数不正确');
        if($form->dom->bugNum->getText() != '5')           return $this->failed('Bug数不正确');
        return $this->success('执行基础信息正确');
    }

    /**
     * 检查执行概况页面产品信息
     * Check the product information of the execution.
     *
     * @access public
     * @return object
     */
    public function checkProduct()
    {
        $form = $this->initForm('execution', 'view', array('execution' => '3'), 'appIframe-execution');
        if($form->dom->linckedProducta->getText() != '产品1') return $this->failed('关联产品不正确');
        $form->dom->moreProducts->click();
        $form->wait(1);
        $url  = $this->response();
        if($url['module'] != 'execution')      return $this->failed('页面跳转后module不正确');
        if($url['method'] != 'manageproducts') return $this->failed('页面跳转后method不正确');
        return $this->success('产品信息正确');
    }
}
