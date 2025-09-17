<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createTester extends tester
{
    /**
     * 产品下没有构建，创建测试单。
     * Create a testtask without build.
     *
     * @param  string $product
     * @param  string $execution
     * @access public
     * @return void
     */
    public function createWithoutBuild($product, $execution)
    {
        $form = $this->initForm('testtask', 'create', array('product' => '1'), 'appIframe-qa');
        $form->dom->product->picker($product);
        $form->wait(1);
        $form->dom->execution->picker($execution);
        $form->wait(1);
        if(!is_object($form->dom->createBuildBtn)) return $this->failed('没有显示创建构建按钮');
        if(!is_object($form->dom->refreshBtn))     return $this->failed('没有显示刷新按钮');
        return $this->success('正确显示创建构建和刷新按钮');
    }

    /**
     * 创建测试单。
     * Create a testtask.
     *
     * @param  array  $testtask
     * @access public
     * @return void
     */
    public function create($testtask)
    {
        $browseForm = $this->initForm('testtask', 'browse', array('product' => '1'), 'appIframe-qa');
        $numA       = $browseForm->dom->totalNum->getText();
        $form       = $this->initForm('testtask', 'create', array('product' => '1'), 'appIframe-qa');
        if(isset($testtask['product']))   $form->dom->product->picker($testtask['product']);
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
            if($form->dom->endTip->getText() != sprintf($this->lang->error->ge, $this->lang->testtask->end, $testtask['begin'])) return $this->failed('开始日期大于结束日期时提示信息错误');
            return $this->success('开始日期大于结束日期时提示信息正确');
        }
        $form->wait(1);
        $browseForm = $this->loadPage('testtask', 'browse');
        $numB       = $browseForm->dom->totalNum->getText();
        if($numA != $numB - 1) return $this->failed('创建测试单成功后，测试单数目没有变化');
        return $this->success('创建测试单成功');
    }
}
