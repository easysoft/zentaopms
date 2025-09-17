#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';

class repo extends tester
{
    public function createMaintain($maintain = array())
    {
        $this->login();
        $form = $this->initForm('repo', 'create', '', 'appIframe-devops');
        $form->dom->SCM->picker($maintain['SCM']);
        $form->dom->serviceHost->picker($maintain['serviceHost']);
        $form->dom->serviceProject->picker($maintain['serviceProject']);
        $form->dom->{'product[]'}->multiPicker($maintain['product']);
        $form->dom->desc->setValue($maintain['desc']);
    }

    public function maintain()
    {
        $this->login();
        $form = $this->initForm('repo', 'maintain', '', 'appIframe-devops');
        $fieldXpath = $form->dom->getElementList($form->dom->xpath['fieldList']);
        $fieldLists = array_map(function($element){return $element->getText();}, $fieldXpath->element);
        $name           = $this->lang->repo->name;
        $product        = '关联产品';
        $type           = $this->lang->typeAB;
        $lastSubmitTime = $this->lang->repo->lastSubmitTime;
        $actions        = $this->lang->actions;
        $field = array($name, $product, $type, $lastSubmitTime, $actions);
        if(empty(array_diff($fieldLists, $field))) return $this->success('代码库列表无误');
        return $this->failed('代码库列表有误');
    }
}
