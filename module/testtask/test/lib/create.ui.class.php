<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
     * 创建构建。
     * Create build.
     *
     * @param  array $build
     * @access public
     * @return void
     */
    public function create($build)
    {
        $form = $this->initForm('build', 'create', $build, 'appIframe-qa');
        if(isset($build['product']))   $form->dom->product->picker($build['product']);
        if(isset($build['execution'])) $form->dom->execution->picker($build['execution']);
        if(isset($build['build']))     $form->dom->build->picker($build['build']);
        if(isset($build['begin']))     $form->dom->begin->setValue($build['begin']);
        if(isset($build['end']))       $form->dom->end->setValue($build['end']);
        if(isset($build['name']))      $form->dom->name->setValue($build['name']);
        $form->dom->submitBtn->click();
        $form->wait(1);
        if(isset($testtask['build']) && $testtask['build'] == '')
        {
            if($form->dom->buildTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->build)) return $this->failed('提测构建为空时提示信息错误');
            return $this->success('提测构建为空时提示信息正确');
        }
        if(isset($testtask['begin']) && $testtask['begin'] == '')
        {
            if($form->dom->beginTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->begin)) return $this->failed('开始日期为空时提示信息错误');
            return $this->success('开始日期为空时提示信息正确');
        }
        if(isset($testtask['end']) && $testtask['end'] == '')
        {
            if($form->dom->endTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->end)) return $this->failed('结束日期为空时提示信息错误');
            return $this->success('结束日期为空时提示信息正确');
        }
        if(isset($testtask['name']) && $testtask['name'] == '')
        {
            if($form->dom->nameTip->getText() != sprintf($this->lang->error->notempty, $this->lang->testtask->name)) return $this->failed('测试单名称为空时提示信息错误');
            return $this->success('测试单名称为空时提示信息正确');
        }
        if(isset($testtask['begin']) && isset($testtask['end']) && $testtask['begin'] > $testtask['end'])
        {
            if($form->dom->endTip->getText() != sprintf($this->lang->error->ge, $this->lang->testtask->end, $testtask['begin'])) return $this->failed('开始日期大于结束日期时提示信息错误');
            return $this->success('开始日期大于结束日期时提示信息正确');
        }
        return $this->success('创建测试单成功');
    }
}
