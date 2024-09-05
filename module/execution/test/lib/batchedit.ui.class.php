<?php
include dirname(__FILE__, 5) .'/test/lib/ui.php';
class batchEditTester extends tester
{
    /**
     * 批量编辑页面表单。
     * Batch edit page form.
     *
     * @param  array  $execution
     * @access public
     * @return string
     */
    public function inputFields($execution)
    {
        $form = $this->initForm('execution', 'all', '', 'appIframe-execution');
        $form->dom->firstCheckbox->click();
        $form->dom->btn($this->lang->edit)->click();

        $batchEditForm = $this->loadPage('execution', 'batchEdit');
        $id = $batchEditForm->dom->id_static_0->getText();
        $beginDom = "begin[{$id}]";
        $endDom   = "end[{$id}]";
        if(isset($execution['name']))  $batchEditForm->dom->name_0->setValue($execution['name']);
        if(isset($execution['begin'])) $batchEditForm->dom->$beginDom->datePicker($execution['begin']);
        if(isset($execution['end']))   $batchEditForm->dom->$endDom->datePicker($execution['end']);
        $batchEditForm->dom->submitBtn->click();
        $batchEditForm->wait(1);
        return $id;
    }

    /**
     * 检查执行名称字段。
     * Check execution name field.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function checkName($execution)
    {
        $this->inputFields($execution);
        $form = $this->loadPage();

        $text = $form->dom->alertModal('text');
        if($execution['name'] =='')
        {
            $info = sprintf($this->lang->error->notempty, $this->lang->execution->name);
            if($text == $info) return $this->success('执行名称为空提示信息正确');
            return $this->failed('执行名称为空提示信息错误');
        }
        $info = sprintf($this->lang->error->repeat, $this->lang->execution->name, $execution['name']);
        if($text == $info) return $this->success('执行名称重复提示信息正确');
        return $this->failed('执行名称重复提示信息错误');
    }

    /**
     * 删除字符串中的日期。
     * Delete date in string.
     *
     * @param  string $str
     * @access public
     * @return string
     */
    public function deleteDate($str)
    {
        preg_match_all('/(\d{4}-\d{2}-\d{2})/', $str, $matches);
        $date   = $matches[0][0];
        $params = str_replace($date, '', $str);
        $params = trim($params);
        return $params;
    }

    /**
     * 检查日期字段。
     * Check begin and end date field.
     *
     * @param  array  $execution
     * @param  string $type      begin|end|other
     * @access public
     * @return object
     */
    public function checkDate($execution, $type)
    {
        $id = $this->inputFields($execution);
        $form = $this->loadPage();

        $beginTipDom = "begin[{$id}]Tip";
        $endTipDom   = "end[{$id}]Tip";

        if($type == 'begin')
        {
            $beginText = $form->dom->$beginTipDom->getText();
            /* 检查计划开始日期为空报错 */
            if($execution['begin'] == '')
            {
                $beginInfo = sprintf($this->lang->error->notempty, $this->lang->execution->begin);
                if($beginText == $beginInfo) return $this->success('执行开始日期为空提示信息正确');
                return $this->failed('执行开始日期为空提示信息错误');
            }
            /* 检查计划开始日期小于项目开始日期报错 */
            $beginInfo = sprintf($this->lang->execution->errorLesserProject, '');
            if($beginInfo == $this->deleteDate($beginText)) return $this->success('执行开始日期小于项目开始日期提示信息正确');
            return $this->failed('执行开始日期小于项目开始日期提示信息错误');
        }
        if($type == 'end')
        {
            $endText = $form->dom->$endTipDom->getText();
            /* 检查计划结束日期为空报错 */
            if($execution['end'] == '')
            {
                $endInfo = sprintf($this->lang->error->notempty, $this->lang->execution->end);
                if($endText == $endInfo) return $this->success('执行结束日期为空提示信息正确');
                return $this->failed('执行结束日期为空提示信息错误');
            }
            /* 检查计划结束日期大于项目结束日期报错 */
            $endInfo = sprintf($this->lang->execution->errorGreaterProject, '');
            if($endInfo == $this->deleteDate($endText)) return $this->success('执行结束日期大于项目结束日期提示信息正确');
            return $this->failed('执行结束日期大于项目结束日期提示信息错误');
        }
        /* 检查计划开始日期大于计划结束日期报错 */
        $endText = $form->dom->$endTipDom->getText();
        $endInfo = sprintf($this->lang->execution->errorLesserPlan, $execution['end'], $execution['begin']);
        if($endInfo == $endText) return $this->success('执行结束日期小于执行开始日期提示信息正确');
        return $this->failed('执行结束日期小于执行开始日期提示信息错误');
    }

    /**
     * 成功编辑。
     * Success edit.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function batchEdit($execution)
    {
        $this->inputFields($execution);
        $form = $this->loadPage();
        if($form->dom->firstName->getText() != $execution['name']) return $this->failed('批量编辑后执行名称错误');
        if($form->dom->firstBegin->getText() != $execution['begin']) return $this->failed('批量编辑后执行开始日期错误');
        if($form->dom->firstEnd->getText() != $execution['end']) return $this->failed('批量编辑后执行结束日期错误');
        return $this->success('批量编辑执行成功');
    }
}
