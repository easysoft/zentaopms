<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchEditProduct extends tester
{
    /*
     * 创建产品线
     * create product line
     *
     * @param  $line
     * @return mixed
     */
    public function createLine($line)
    {
        $form = $this->initForm('product', 'all', array(), 'appIframe-product');
        $form->dom->manageLineBtn->click();
        //设置表单字段
        if (isset($line->name))    $form->dom->lineName->setValue($line->name);
        if (isset($line->program)) $form->dom->lineprogram->setValue($line->program);
        $form->dom->btn($this->lang->save)->click();
        $form->dom->manageLineBtn->click();
        return ($form->dom->lineName->getValue() == $line->name) ? $this->success('产品线名称正确') : $this->failed('产品线名称不正确');
        return ($form->dom->lineprogram->getValue() == $line->program) ? $this->success('所属项目集正确') : $this->failed('所属项目集不正确');
    }

    public function delLine()
    {
        $form = $this->initForm('product', 'all', array(), 'appIframe-product');
        $form->dom->manageLineBtn->click();
        $form->dom->delLineBtn->click();
        return ($form->dom->modules[0]->getValue() == '') ? $this->success('删除成功') : $this->failed('删除失败');
    }
}
