<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class executionTester extends tester
{
    /**
     * 敏捷项目迭代列表页面标签数量。
     * Project execution tab.
     *
     * @param  string $tab
     * @param  string $expectNum
     * @access public
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('project', 'execution', '', 'appIframe-project');
        $status = [
            'all'       => '全部',
            'undone'    => '未完成',
            'wait'      => '未开始',
            'doing'     => '进行中',
            'suspended' => '已挂起',
            'delayed'   => '已延期',
            'closed'    => '已关闭',
        ];
        $tabDom = $tab.'Tab';
        $form->dom->$tabDom->click();
        $form->wait(2);

        /*添加断言，判断标签下条数是否符合预期*/
        if($form->dom->numDom->getText() == $expectNum) return $this->success($status[$tab] . '标签下条数显示正确');
        return $this->failed($status[$tab] . '标签下条数显示不正确');
    }
}
