#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';

class ajaxGetDropmenuTester extends tester
{
    /**
     * 对比产品范围
     * Compare product range
     *
     * @param  array  $products
     * @param  array  $prodcutvalues
     * @access public
     * @return bool
     */

    public function compareProducts($products, $prodcutvalues)
    {
        if(count($prodcutvalues) !== count($products)) return false;

        $temp1 = $prodcutvalues;
        $temp2 = $products;
        sort($temp1);
        sort($temp2);
        return $temp1 === $temp2;
    }

    /**
     * 测试Bug创建页面Ajax获取所属产品下拉菜单功能
     * Test ajaxgetdropdownmenu functionality in bug create page
     *
     * @param  array  $user
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function ajaxGetDropmenuInBugCreate($user = array(), $products = array(), $bug = array())
    {
        $this->login($user);
        $form = $this->initForm('bug', 'create',array('productID' => '1'), 'appIframe-qa');
        $form->wait(1);

        $prodcutvalues = $form->dom->getPickerItems('product');
        $prodcutvalues = array_column($prodcutvalues, 'text');

        if(!$this->compareProducts($products, $prodcutvalues)) return $this->failed('产品范围显示错误');

        $form->dom->product->picker($products[1]);
        $form->wait(3);
        if(isset($bug['openedBuild'])) $form->dom->{'openedBuild[]'}->multipicker($bug['openedBuild']);
        if(isset($bug['title']))       $form->dom->title->setValue($bug['title']);
        $form->dom->save->click();
        $form->wait(3);

        if($this->response('method') != 'browse') return $this->failed('创建bug失败');
        return $this->success($user . '在Bug创建页面获取所属产品数据正确');
    }

    /**
     * 测试Bug编辑页面Ajax获取产品下拉菜单功能
     * Test ajaxgetdropdownmenu functionality in bug edit page
     *
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function ajaxGetDropmenuInBugEdit($user =array(), $products = array(), $bug = array())
    {
        $this->login($user);
        $form = $this->initForm('bug', 'edit', array('bugID' => '1'), 'appIframe-qa');
        $form->wait(3);

        $prodcutvalues = $form->dom->getPickerItems('product');
        $prodcutvalues = array_column($prodcutvalues, 'text');

        if(!$this->compareProducts($products, $prodcutvalues)) return $this->failed('产品范围显示错误');

        $form->dom->title->setValue($user . '编辑Bug名称');
        $form->dom->save->click();
        $form->wait(3);
        if($this->response('method') != 'view') return $this->failed('编辑bug失败');
        return $this->success($user . '在Bug编辑页面获取所属产品数据正确');
    }
}
