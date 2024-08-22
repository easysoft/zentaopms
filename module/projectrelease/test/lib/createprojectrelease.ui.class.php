<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProjectReleaseTester extends tester
{
    /**
     * Check the page input when creating the project release.
     * 创建项目发布时检查页面输入
     *
     * @param  array $release
     * @access public
     */
    public function checkInput($release)
    {
        $form = $this->initForm('projectrelease', 'create', array('projectID' => 1), 'appIframe-project');
        if(isset($release['name']))        $form->dom->name->setValue($release['name']);
        if(isset($release['status']))      $form->dom->status->picker($release['status']);
        $form->wait(2);
        if(isset($release['plandate']))    $form->dom->date->datepicker($release['plandate']);
        if(isset($release['releasedate'])) $form->dom->releasedDate->datepicker($release['releasedate']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($release);
    }

    /**
     * Check the result after creating the project release.
     * 创建项目发布后检查结果
     *
     * @param  array $release
     * @access public
     * @return object
     */
}
