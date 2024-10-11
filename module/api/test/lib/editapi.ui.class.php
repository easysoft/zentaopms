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
        $form->wait(1);
        $form->dom->name->setValue($editLib->title);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        if($form->dom->fstLibTitle->getText() != $editLib->title) return $thhis->failed('编辑接口库失败');
        return $this->success('编辑接口库成功');
    }

    /*
     * 编辑接口文档。
     * Edit a apiDoc.
     *
     * @param  string $apiDoc
     * @param  string $apiPath
     * @access public
     * @return void
     */
    public function editApiDoc($apiDoc, $apiPath)
    {
        /*创建一个接口文档*/
    }
}
