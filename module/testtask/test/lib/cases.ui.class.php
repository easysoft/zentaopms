<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class casesTester extends tester
{
    /**
     * 检查测试单下不同标签下的数据。
     * Check the data of testtasks under different tags.
     *
     * @param  string $tags
     * @param  string $num
     * @access public
     * @return void
     */
    public function checkTagData($tags, $num)
    {
        $form = $this->initForm('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        if($tags == 'all')
        {
            $form->dom->btn($this->lang->testtask->allCases)->click();
            if($form->dom->num->getText() == $num) $this->success('标签下数据统计正确');
            return $this->failed('标签下数据统计错误');
        }
    }
}
