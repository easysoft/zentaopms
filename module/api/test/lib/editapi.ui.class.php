<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /*
     * 编辑接口库。
     * Edit a apiLib.
     *
     * @param  string $editLib
     * @access public
     * @return void
     */
    public function editApiLib($editLib)
    {
        $form = $this->initForm('api', 'index', array(), 'appIframe-doc');
        $form->dom->fstMoreBtn->click();
        $form->dom->fstEditBtn->click();
    }
}
