<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class executionForLiteTester extends tester
{
    /**
     * 运营界面项目看板页面标签数量。
     * Project execution tab.
     *
     * @param  string $tab
     * @param  string $expectNum
     * @access public
     */
    public function checkTab($sort, $tab, $expectNum)
    {
        $this->switchVision('lite');
        $this->page->wait(5)->refresh();
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
        $form->wait(5);

        /*添加断言，判断标签下条数是否符合预期*/
        $num = $form->dom->getElement("//*[@id='featureBar']/menu/li[$sort]/a/span[2]")->element->getText();
        $form->wait(2);
        if($num == $expectNum) return $this->success($status[$tab] . '标签下条数显示正确');
        return $this->failed($status[$tab] . '标签下条数显示不正确');
    }
}
