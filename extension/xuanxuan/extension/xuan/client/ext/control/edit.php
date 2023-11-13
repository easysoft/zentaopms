<?php
declare(strict_types=1);
/**
 * The edit control file of client module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     client
 * @link        https://www.zentao.net
 */
class client extends control
{
    /**
     * 编辑一个客户端版本。
     * Edit a client version.
     *
     * @param  int    $clientID
     * @access public
     * @return void
     */
    public function edit(int $clientID)
    {
        $this->checkSafeFile();

        if($_POST)
        {
            $this->post->set('desc', mb_substr($this->post->desc, 0, 100));
            $this->client->update($clientID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $this->view->title  = $this->lang->client->edit;
        $this->view->client = $this->client->getByID($clientID);
        $this->display();
    }
}
