<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class bugTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of Tab tag.
     *
     * @param  string $tab allTab|unresolvedTab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->bugNum->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }

    /**
     * 切换产品查看数据。
     * Switch product to view data.
     *
     * @param  string $product firstProduct|secondProduct
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->dropMenu->click();
        $form->dom->$product->click();
        $form->wait(1);
        if($form->dom->bugNum->getText() == $expectNum) return $this->success('切换' . $product . '查看数据成功');
        return $this->failed('切换' . $product . '查看数据失败');
    }

    /**
     * 单个指派bug。
     * Assign bug.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function assignBug($user)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->firstAssign->click();
        $form->dom->assignTo->picker($user);
        $form->dom->submitBtn->click();
        $form->wait(1);
        if($form->dom->firstAssign->getText() == $user) return $this->success('单个指派Bug成功');
        return $this->failed('单个指派Bug失败');
    }

    /**
     * 批量指派bug。
     * Batch assign bug.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function batchAssignBug($user)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->secondCheckbox->click();
        $form->dom->batchAssignTo->click();
        $form->dom->assignToAdmin->click();
        $form->wait(1);
        if($form->dom->firstAssign->getText() == $user) return $this->success('批量指派Bug成功');
        return $this->failed('批量指派Bug失败');
    }

    /**
     * 确认bug。
     * Confirm bug.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function confirmBug($user)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->confirmBtn->click();
        $form->dom->confirmAssignTo->picker($user);
        $form->dom->confirm->click();
        $form->wait(1);
        if($form->dom->firstConfirm->getText() == '已确认') return $this->success('确认Bug成功');
        return $this->failed('确认Bug失败');
    }

    /**
     * 解决bug。
     * Resolve bug.
     *
     * @param  array $bug
     * @access public
     * @return object
     */
    public function resolveBug(array $bug)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->resolveBtn->click();
        $resolveForm = $this->loadPage('bug', 'bug');
        $form->wait(1);
        $title = $form->dom->resolveTitle->getText();
        if(isset($bug['resolution']) && !empty($bug['resolution'])) $form->dom->resolution->picker($bug['resolution']);
        if(isset($bug['build']) && !empty($bug['build']))           $form->dom->build->picker($bug['build']);
        $form->dom->resolve->click();
    }
}
