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
    }
}
