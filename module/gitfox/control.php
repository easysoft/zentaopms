<?php
declare(strict_types=1);
/**
 * The control file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfox extends control
{
    /**
     * 创建一个gitfox。
     * Create a gitfox.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $gitfox = form::data($this->config->gitfox->form->create)->get();
            $this->checkToken($gitfox);
            $gitfoxID = $this->loadModel('pipeline')->create($gitfox);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action');
            $this->action->create('gitlab', $gitfoxID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gitfox->common . $this->lang->colon . $this->lang->gitfox->lblCreate;

        $this->display();
    }
}

