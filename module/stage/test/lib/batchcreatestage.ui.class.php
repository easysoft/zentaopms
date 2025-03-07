<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchCreateStageTester extends tester
{
    /**
     * Batch create a stage.
     * 批量创建一个阶段。
     *
     * @param  array  $stage
     * @param  string $type  waterfall|waterfallplus
     * @access public
     * @return object
     */
    public function batchCreateStage(array $stage, string $type = '')
    {
        if($type == 'waterfall')
        {
            $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
            $form->dom->batchCreateBtn->click();
        }
        if($type == 'waterfallplus')
        {
            $form = $this->initform('stage', 'plusbrowse', array(), 'appiframe-admin');
            $form->dom->batchcreatebtn->click();
        }
        $batchcreateform = $this->loadpage('stage', 'batchcreate');
        if(isset($stage['name']))    $batchcreateform->dom->name->setvalue($stage['name']);
        if(isset($stage['percent'])) $batchcreateform->dom->percent->setvalue($stage['percent']);
        if(isset($stage['type']))    $batchcreateform->dom->type->select('text', $stage['type']);
        $batchcreateform->dom->submitbtn->click();
        $batchcreateform->wait(1);

        /* 检查工作量占比不能为空 */
        $percenttip = "percent[1]tip";
        if($batchcreateform->dom->$percenttip)
        {
            $percenttiptext = $batchcreateform->dom->$percenttip->gettext();
            $percenttip     = sprintf($this->lang->error->notempty, $this->lang->stage->percent);
            return ($percenttiptext == $percenttip) ? $this->success('工作量占比必填提示信息正确') : $this->failed('工作量占比必填提示信息不正确');
        }
        /* 检查工作量占比累计不能超过100% */
        if($batchcreateform->dom->percentovertip)
        {
            $alertmodaltext = $batchcreateform->dom->percentovertip->gettext();
            $percentovertip = $this->lang->stage->error->percentover;
            return ($alertmodaltext == $percentovertip) ? $this->success('工作量占比累计超出100%时提示信息正确') : $this->failed('工作量占比累计超出100%时提示信息不正确');
        }
        /* 跳转到阶段列表，检查阶段信息。*/
        if($type == 'waterfall')
        {
            $browsepage = $this->loadpage('stage', 'browse');
            if($browsepage->dom->stagename->gettext() != $stage['name']) return $this->failed('阶段名称错误');
            if($browsepage->dom->stagetype->gettext() != $stage['type']) return $this->failed('阶段类型错误');
        }
        if($type == 'waterfallplus')
        {
            $plusbrowsepage = $this->loadpage('stage', 'plusbrowse');
            if($plusbrowsepage->dom->stagename->gettext() != $stage['name']) return $this->failed('阶段名称错误');
            if($plusbrowsepage->dom->stagetype->gettext() != $stage['type']) return $this->failed('阶段类型错误');
        }
        return $this->success('批量新建阶段成功');
    }
}
