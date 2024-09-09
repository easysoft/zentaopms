<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageLineTester extends tester
{
    /**
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
        if (isset($line->name))    $form->dom->modules_0->setValue($line->name);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        if ($form->dom->lineDialog === false)
        {
            $form->dom->manageLineBtn->click();
            return ($form->dom->newLineName->getText() == $line->name) ? $this->success('产品线名称正确') : $this->failed('产品线名称不正确');
        }
        else
        {
            $tipDom = 'modules[0]Tip';
            $nameDuplicateTip = sprintf($this->lang->product->nameIsDuplicate, $line->name);
            if ($nameDuplicateTip == $form->dom->$tipDom->getText()) return $this->success('维护产品线表单页提示信息正确');
        }
    }

    /**
     * 删除产品线
     * delete product line
     *
     * @param  $line
     * @return mixed
     */
    public function delLine($line)
    {
        $form = $this->initForm('product', 'all', array(), 'appIframe-product');
        $form->dom->manageLineBtn->click();
        $form->wait(1);
        $form->dom->delNewLineBtn->click();
        $form->wait(1);
        $form->dom->confirm->click();
        $form->wait(1);
        return ($form->dom->newLineName->getText() != $line->name) ? $this->success('删除成功') : $this->failed('删除失败');
    }
}
