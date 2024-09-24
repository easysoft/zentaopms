<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createGuideTester extends tester
{
    /**
     * Create project guide.
     * 创建项目向导
     *
     * @param  array $type
     * @access public
     */
    public function createGuide($type)
    {
        $form = $this->initForm('project', 'browse');
        $form->dom->createProjectBtn->click();
        $form->dom->$type->click();
        $form->wait(3);

        //向导弹窗中点击项目管理方式后，跳转是否正确
        if(strpos($this->response('url'), 'model='.$type)) return $this->success($type.'向导跳转正确');
        return $this->failed($type.'向导跳转不正确');
    }
}
