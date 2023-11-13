<?php
declare(strict_types=1);
/**
 * The create control file of client module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     client
 * @link        https://www.zentao.net
 */
class client extends control
{
    /**
     * 添加一个客户端版本。
     * Create a client version.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $this->checkSafeFile();

        if($_POST)
        {
            $this->post->set('desc', mb_substr($this->post->desc, 0, 100));

            $this->client->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $this->view->title = $this->lang->client->create;
        $this->display();
    }
}
