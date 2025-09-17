<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createSceneTester extends tester
{
    /**
     * 建场景。
     * Create scene.
     *
     * @param  array $scene
     * @access public
     */
    public function createScene($scene = array())
    {
        $form = $this->initForm('testcase', 'createScene', array('productID' => '1'), 'appIframe-qa');
        $form->wait(1);

        if($form->dom->manageModule)
        {
            $form->dom->manageModule->click();
            $form->wait(1);
            if(isset($scene['module1'])) $form->dom->module1->setValue($scene['module1']);
            if(isset($scene['module2'])) $form->dom->module2->setValue($scene['module2']);
            $form->dom->saveBtn->click();
            $form->wait(2);
        }

        $form = $this->loadPage('testcase', 'createScene');
        if(isset($scene['module']))      $form->dom->module->picker($scene['module']);
        if(isset($scene['parentscene'])) $form->dom->parent->picker($scene['parentscene']);
        $form->wait(1);
        if(isset($scene['scene'])) $form->dom->title->setValue($scene['scene']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();

        /* 检查建场景页面必填和重复校验提示信息是否正确 */
        if(is_object($form->dom->titleTip))
        {
            $nameTipform = $form->dom->titleTip->getText();
            $requiredTip = sprintf($this->lang->error->notempty, $this->lang->scene->title);
            $repeatTip   = sprintf($this->lang->error->unique, $this->lang->scene->title, $scene['scene']);
            if($nameTipform == $requiredTip) return $this->success('建场景必填提示信息正确');
            if($nameTipform == $repeatTip)   return $this->success('建场景名称重复时提示信息正确');
            return $this->failed('建场景提示信息错误');
        }

        /* 断言检查场景是否创建成功 */
        else if(!is_object($form->dom->titleTip) && $this->response('method') != 'createScene')
        {
            $browsePage = $this->loadPage('testcase', 'browse');
            $form->wait(2);
            $browsePage->dom->onlyScene->click();

            $browseScenePage = $this->loadPage('testcase', 'browseScene');
            $browseScenePage->wait(1);
            $sceneName = $browseScenePage->dom->getElement("//*[@id='scenes']/div[2]/div[1]/div/div[last()]/div")->element->getText();

            if($sceneName != $scene['scene']) return $this->failed('场景名称错误');
            return $this->success('场景创建成功');
        }
    }
}
