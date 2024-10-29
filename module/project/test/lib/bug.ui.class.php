<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class bugTester extends tester
{
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->bugNum->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }
}
