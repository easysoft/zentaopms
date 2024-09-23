<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 切换tab
     * switch tab
     *
     * @param $planurl 产品ID
     * @param $tabName tab名称
     * @param $tabNum  tab下计划数量
     *
     * @return mixed
     */
    public function switchTab($planurl, $tabName, $tabNum)
    {
        $tabDom     = $tabName.'Tab';
        $tabNumDom  = $tabName.'Num';
        $browsePage = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        $browsePage->dom->$tabDom->click();//点击对应的tab
        $num = $browsePage->dom->$tabNumDom->getText();//获取对应tab下计划数量
        return ($num == $tabNum) ? $this->success("切换至{$tabName}Tab成功") : $this->failed("切换至{$tabName}Tab失败");
    }

    /**
     * 切换计划列表/计划看板
     * switch list/kanban
     *
     * @param $planurl    产品ID
     * @param $browseType 切换类型
     *
     * @return mixed
     */
    public function switchBrowseType($planurl, $browseType)
    {
        $browsePage = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        if ($browseType === 'kanban')
        {
            $browsePage->dom->kanbanBtn->click();
            return ($browsePage->dom->orderByBtn) ? $this->success('成功切换到看板模式') : $this->failed('切换到看板模式失败');
        }
        else
        {
            $browsePage->dom->listBtn->click();
            return ($browsePage->dom->allTab) ? $this->success('成功切换到列表模式') : $this->failed('切换到列表模式失败');
        }
    }

    /**
     * 看板中计划排序
     * sort in kanban
     * @param $planurl 产品ID
     * @param $orderBy 排序方式
     *
     * @return mixed
     */
    public function sortInKanban($planurl, $orderBy)
    {
        $browsePage = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        $browsePage->dom->kanbanBtn->click();//点击进入看板模式
        $browsePage->dom->orderByBtn->click();
        $orderButtons = [
            'begin_desc' => 'beginDesc',
            'begin_asc'  => 'beginAsc',
        ];
        $browsePage->dom->{$orderButtons[$orderBy]}->click();//点击对应的排序方式
        $browsePage->wait(2);
        $firBeginToEnd = $browsePage->dom->firBeginToEnd->getText(); // 第一个计划卡片中计划的开始结束时间
        $secBeginToEnd = $browsePage->dom->secBeginToEnd->getText(); // 第二个计划卡片中计划的开始结束时间
        $firBegin      = substr($firBeginToEnd, 0, strpos($firBeginToEnd, " {$this->lang->productplan->to} ")); // 第一个计划的开始时间
        $secBegin      = substr($secBeginToEnd, 0, strpos($secBeginToEnd, " {$this->lang->productplan->to} ")); // 第二个计划的开始时间
        if ($orderBy === 'begin_desc')
        {
            return ($firBegin > $secBegin) ? $this->success("按计划开始时间倒序排序成功") : $this->failed("按计划开始时间倒序排序失败");
        }
        if ($orderBy === 'begin_asc')
        {
            return ($firBegin < $secBegin) ? $this->success("按计划开始时间正序排序成功") : $this->failed("按计划开始时间正序排序失败");
        }
    }
}
