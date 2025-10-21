#!/usr/bin/env php
<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class ajaxGetDropmenuTester extends tester
{

    public function __construct()
    {
        parent::__construct();
        $this->login();
    }

    /**
     * 测试Bug创建页面Ajax获取产品下拉菜单功能
     * Test ajaxgetdropdownmenu functionality in bug create page
     *
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function ajaxGetDropmenuInBugCreate($products = array(), $bug = array())
    {
        $form = $this->initForm('bug', 'create', $products[0], 'appIframe-qa');
        $form->wait(1);

        $i = 0;
        foreach($products as $product)
        {
            $i++;
            $form->dom->product->picker($product['name']);
            $form->wait(1);
            if($form->dom->product->attr('value') != $i) return $this->failed('Ajaxgetdropmenu测试失败，显示产品与选择不符');
        }
        $form->dom->product->picker($products[0]['name']);
        $form->wait(1);
        if(isset($bug['title']))       $form->dom->title->setValue($bug['title']);
        if(isset($bug['openedBuild'])) $form->dom->{'openedBuild[]'}->multipicker($bug['openedBuild']);
        $form->dom->save->click();
        $form->wait(1);
        if($this->response('method') != 'browse') return $this->failed('Ajaxgetdropmenu测试失败，创建bug失败');
        return $this->success('Ajaxgetdropmenu在Bug创建页面测试成功');
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
    public function ajaxGetDropmenuInBugEdit($products = array(), $bug = array())
    {
        $form = $this->initForm('bug', 'browse', $products[0], 'appIframe-qa');
        $form->wait(1);
        $form->dom->btn($this->lang->bug->search)->click();
        $form->wait(1);
        $search = array('field1' => $this->lang->bug->title);
        foreach($search as $key=>$value)
        {
            $form->dom->{$key}->picker($value);
            $form->wait(1);
        }
        $form->dom->value1->setValue($bug['title']);
        $form->wait(1);
        $form->dom->searchButton->click();
        $form->wait(1);
        $form->dom->editButton->click();
        $form->wait(1);

        $i = 0;
        foreach($products as $product)
        {
            $i++;
            if($product['name'] == $products[0]['name']) continue;
            $form->dom->product->picker($product['name']);
            $form->wait(1);
            try
            {
                $form->dom->btn($this->lang->projectstory->confirm)->click();
            }
            catch (Exception)
            {
            }
            if($form->dom->product->attr('value') != $i) return $this->failed('Ajaxgetdropmenu测试失败，显示产品与选择不符');
        }
        if($i != count($products)) return $this->failed('Ajaxgetdropmenu在Bug编辑页面测试失败，产品下拉菜单数目不符');
        $form->dom->product->picker($products[0]['name']);
        $form->wait(1);
        $form->dom->save->click();
        $form->wait(1);
        return $this->success('Ajaxgetdropmenu在Bug编辑页面测试成功');
    }
}
