<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class storykanbanTester extends tester
{
    /**
     * 检查需求看板数据。
     * Check storykanban data.
     *
     * @param  string $stage
     * @param  string $num
     * @access public
     * @return void
     */
    public function check($stage, $num)
    {
        $form = $this->initForm('execution', 'storykanban', array('execution' => '2'), 'appIframe-execution');
        $form->dom->kanbanBtn->click();
        $form->wait(1);
        preg_match_all('/\d+(?:\.\d+)?/', $form->dom->$stage->getText(), $matches);
        if($matches[0][0] != $num) return $this->failed("{$stage}列数据有误");
        return $this->success("{$stage}列数据正确");
    }
}
