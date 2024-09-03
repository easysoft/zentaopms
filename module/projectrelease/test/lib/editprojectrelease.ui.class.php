<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editProjectReleaseTester extends tester
{
    /**
     * Check the page input when editing the project release.
     * 编辑项目发布时检查页面输入
     *
     * @param  array $release
     * @access public
     */
    public function checkInput($release)
    {
        $browseForm = $this->initForm('projectrelease', 'browse', array('project' => 1), 'appIframe-project');
        $browseForm->dom->editBtn->click();
        $form = $this->initForm('projectrelease', 'edit', array('releaseID' => 1), 'appIframe-project');
        if(isset($release['name']))   $form->dom->name->setValue($release['name']);
        if(isset($release['status'])) $form->dom->status->picker($release['status']);
        $form->wait(2);
        if(isset($release['plandate']))    $form->dom->date->datepicker($release['plandate']);
        if(isset($release['releasedate'])) $form->dom->releasedDate->datepicker($release['releasedate']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($release);
    }

    /**
     * Check the result after editing the project release.
     * 编辑项目发布后检查结果
     *
     * @param  array $release
     * @access public
     * @return object
     */
    public function checkResult($release)
    {
        //检查编辑项目发布页面时的提示信息
        if($this->response('method') != 'view')
