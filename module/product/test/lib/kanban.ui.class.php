<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class kanbanTester extends tester
{
    /**
     * 检查产品看板数据
     * check data of product kanban
     *
     * @param $type     数据类型 plan|project|execution|release
     * @param $expected 预期数量
     * @return mixed
     */
    public function checkKanbanData($type,$expected)
    {
        $form = $this->initForm('product', 'kanban', array(), 'appIframe-product');
        $form->wait(2);
        $numDom  = $type.'Num';
        $num     = $form->dom->$numDom->getText();
        $message = [
            'plan'      => '未过期计划数',
