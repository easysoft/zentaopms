<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageProductsTester extends tester
{
    /**
     * 维护执行关联产品。
     * Manage Products.
     *
     * @param  array $execution
     * @access public
     * @return void
     */
    public function manageProducts($execution)
    {
        $form = $this->initForm('execution', 'manageproducts', array('execution' => $execution['id']), 'appIframe-execution');
        if($execution['operate'] == 'link')   $form->dom->productb->click();
        if($execution['operate'] == 'unlink') $form->dom->producta->click();
        $form->dom->btn($this->lang->save)->click();
    }
}
