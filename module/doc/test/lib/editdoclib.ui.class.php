<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 编辑我的文档库。
     * Edit a doclib.
     *
     * @param  string $docLibName
     * @access public
     * @return void
     */
    public function editDocLib($libName, $editLibName)
    {
        /*创建文档库*/
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('type' => 'mine'));
        $form->dom->createLibBtn->click();
    }
}
