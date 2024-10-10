<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /*
     * 创建接口文档。
     * Create a api.
     *
     * @param  string $apiLib
     * @param  string $apiDoc
     * @param  string $apiPath
     * @access public
     * @return void
     */
    public function createApiDoc($apiLib, $apiDoc, $apiPath)
    {
        /*进入接口空间创建独立接口库*/
        $form = $this->initForm('api', 'index', array(), 'appIframe-doc');
        $form->wait(1);
        $form->dom->createLibBtn->click();
        $form->wait(1);
        $form->dom->name->setValue($apiLib->name);
        $form->dom->btn($this->lang->save)->click();
        /*创建接口文档*/
        $form->dom->createApiBtn->click();
        $form->dom->title->setValue($apiDoc->docA);
        $form->dom->path->setValue($apiPath->pathA);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $form = $this->initForm('api', 'index', array(), 'appIframe-doc');
        $form->dom->search(array("接口名称,=,{$apiDoc->docA}"));
        $form->wait(1);
        if($form->dom->fstDocPath->getText() != $apiPath->pathA) return $this->failed('创建接口文档失败');
        return $this->success('创建接口文档成功');
    }
}
