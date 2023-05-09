<?php
declare(strict_types=1);
class caselibZen extends caselib
{
    /**
     * 为创建用例库设置导航。
     * Set menu for create caselib.
     *
     * @access protected
     * @return void
     */
    protected function setCreateMenu()
    {
        $libraries = $this->caselib->getLibraries();
        $libID     = $this->caselib->saveLibState(0, $libraries);
        $this->caselib->setLibMenu($libraries, $libID);
    }

    /**
     * 构建创建用例库页面数据。
     * Build form fields for create caselib.
     *
     * @access protected
     * @return void
     */
    protected function buildCreateForm()
    {
        $this->view->title = $this->lang->caselib->common . $this->lang->colon . $this->lang->caselib->create;
        $this->display();
    }

    /**
     * 构建创建产品页面数据。
     * Build form fields for create.
     *
     * @param  object $data
     * @param  string $uid
     * @access protected
     * @return object
     */
    protected function prepareCreateExtras(object $data, string $uid = ''): object
    {
        $lib = $data->setForce('type', 'library')
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', helper::now())
            ->setIF($this->lang->navGroup->caselib != 'qa', 'project', (int)$this->session->project)
            ->stripTags($this->config->caselib->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        return $this->loadModel('file')->processImgURL($lib, $this->config->caselib->editor->create['id'], $uid);
    }

    /**
     * 构建创建产品页面数据。
     * Build form fields for create.
     *
     * @param  int  $libID
     * @access protected
     * @return array
     */
    protected function responseAfterCreate(int $libID): array
    {
        $this->loadModel('action')->create('caselib', $libID, 'opened');

        if($this->viewType == 'json') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $libID);
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('caselib', 'browse', "libID=$libID"));
    }

}
