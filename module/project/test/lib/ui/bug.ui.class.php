<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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

        /* 检查解决方案必填提示 */
        if($resolveForm->dom->resolutionTip)
        {
            if($this->checkFormTips('bug')) return $this->success('解决Bug表单页提示信息正确');
            return $this->failed('解决Bug表单页提示信息不正确');
        }

        $form->wait(1);
        /* 搜索已解决的Bug,检查列表状态字段 */
        $form->dom->search(array("{$this->lang->bug->title},=,{$title}"));
        $form->wait(1);
        if($form->dom->status->getText() == '已解决') return $this->success('解决Bug成功');
        return $this->failed('解决Bug失败');
    }

    /**
     * 关闭bug。
     * Close bug.
     *
     * @access public
     * @return object
     */
    public function closeBug()
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->closeBtn->click();
        $title = $form->dom->closeTitle->getText();
        $form->dom->close->click();
        $form->wait(1);
        $form->dom->search(array("{$this->lang->bug->title},=,{$title}"));
        $form->wait(1);
        if($form->dom->status->getText() == '已关闭') return $this->success('关闭Bug成功');
        return $this->failed('关闭Bug失败');
    }

    /**
     * 激活bug。
     * Active bug.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function activeBug($user)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->activeBtn->click();
        $title = $form->dom->activeTitle->getText();
        $form->dom->activeAssignTo->picker($user);
        $form->dom->active->click();
        $form->wait(1);
        $form->dom->search(array("{$this->lang->bug->title},=,{$title}"));
        $form->wait(1);
        if($form->dom->status->getText() == '激活') return $this->success('激活Bug成功');
        return $this->failed('激活Bug失败');
    }

    /**
     * 导出bug。
     * Export bug.
     *
     * @param  array $bug
     * @access public
     * @return object
     */
    public function exportBug($bug)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->exportBtn->click();
        if(isset($bug['fileName']))   $form->dom->fileName->setValue($bug['fileName']);
        if(isset($bug['fileType']))   $form->dom->fileName->setValue($bug['fileType']);
        if(isset($bug['encode']))     $form->dom->fileName->setValue($bug['encode']);
        if(isset($bug['exportType'])) $form->dom->fileName->setValue($bug['exportType']);

        $form->dom->exportBtnAlert->click();
        $form->wait(1);
        if($form->dom->exportBtnAlert) return $this->failed('导出Bug失败');
        return $this->success('导出Bug成功');
    }
}
