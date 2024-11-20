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
    public function checkKanbanData($type, $expected)
    {
        $form = $this->initForm('product', 'kanban', array(), 'appIframe-product');
        $form->wait(2);
        $numDom  = $type.'Num';
        $num     = $form->dom->$numDom->getText();
        $message = [
            'plan'      => '未过期计划数',
            'project'   => '进行中的项目数',
            'execution' => '进行中的执行数',
            'release'   => '正常发布数'
        ];
        if (isset($message[$type]))
        {
            return ($num == $expected)
                ? $this->success($message[$type] . '正确')
                : $this->failed($message[$type] . '不正确');
        }
        return $this->failed('页面中无该类型的数据');
    }
}
