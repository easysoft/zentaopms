<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class bugTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of the Tab tag.
     *
     * @param  string $tab       allTab|unclosedTab|draftTab|reviewingTab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('execution', 'bug', array('execution' => '2'), 'appIframe-execution');
        $form->wait(3);
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }

    /**
     * 单个指派bug。
     * Assign bug.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function assignTo($user)
    {
        $form = $this->initForm('execution', 'bug', array('execution' => '2'), 'appIframe-execution');
        $form->wait(3);
        $form->dom->firstAssignedTo->click();
        $form->wait(1);
        $form->dom->assignedTo->picker($user);
        $form->dom->submitBtn->click();
        $form->wait(3);
        /* 因为指派给字段被遮挡，所以需要滚动到可见区域 */
        $form->dom->firstAssignedTo->scrollToElement();
        if($form->dom->firstAssignedTo->getText() == $user) return $this->success('指派bug成功');
        return $this->failed('指派bug失败');
    }

    /**
     * 批量指派bug。
     * Batch assign bug.
     *
     * @access public
     * @return object
     */
    public function batchAssignTo()
    {
        $form = $this->initForm('execution', 'bug', array('execution' => '2'), 'appIframe-execution');
        $form->wait(3);
        $form->dom->firstCheckbox->click();
        $form->wait(1);
        $form->dom->batchAssignBtn->click();
        $form->wait(1);
        $form->dom->assignToAdmin->click();
        $form->wait(3);
        /* 因为指派给字段被遮挡，所以需要滚动到可见区域 */
        $form->dom->firstAssignedTo->scrollToElement();
        if($form->dom->firstAssignedTo->getText() == 'admin') return $this->success('批量指派bug成功');
        return $this->failed('批量指派bug失败');
    }

    /**
     * 切换产品查看buig。
     * Switch product to view bug.
     *
     * @param  string $product   firstProduct|secondProduct|thirdProduct
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('execution', 'bug', array('execution' => '2'), 'appIframe-execution');
        $form->wait(3);
        $form->dom->productNav->click();
        $form->wait(1);
        $form->dom->$product->click();
        $form->wait(1);

        if($form->dom->num->getText() == $expectNum) return $this->success('切换产品查看bug成功');
        return $this->failed('切换产品查看bug失败');
    }
}
