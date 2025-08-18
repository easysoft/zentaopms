<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createSpaceTester extends tester
{
    /**
     * 创建空间。
     * Create space.
     *
     * @param  string $spaceType
     * @param  string $name
     * @access public
     * @return bool
     */
    public function createSpace($spaceType, $name)
    {
        $form = $this->initForm('doc', $spaceType, '', 'appIframe-doc');
        $form->wait(1);
        $form->dom->btn($this->lang->doc->createSpace)->click();
        $form->wait(1);
        $form->dom->name->setValue($name);
        $form->dom->xpath['saveBtn'] = "//button[@type='submit']";
        $form->dom->saveBtn->click();

        if(empty($name))
        {
            $form = $this->loadPage();
            if(!is_object($form->dom->nameTip)) return $this->failed('空间名称为空时没有提示');
            var_dump($form->dom->nameTip->getValue());
            var_dump(sprintf($this->lang->error->notempty, $this->lang->doclib->spaceName));
            if($form->dom->nameTip->getValue() != sprintf($this->lang->error->notempty, $this->lang->doclib->spaceName)) return $this->failed('空间名称为空时提示正确');
            return $this->success('空间名称为空时提示正确');
        }

        $form->dom->xpath['newSpaceName'] = "//*[@id='mainContent']/div/div/div[2]/div[last()]//strong";
        if(is_object($form->dom->newSpaceName) && $form->dom->newSpaceName->getValue() == $name) return $this->success('空间创建成功');
        return $this->failed('空间创建失败');
    }
}
