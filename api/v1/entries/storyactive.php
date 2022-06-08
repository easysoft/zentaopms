<?php
/**
 * The bug change point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 **/

class storyActiveEntry extends Entry
{
    /**
     * POST method.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function post($storyID)
    {
        $fields = 'assignedTo,status,comment';
        $this->batchSetPost($fields);

        $control = $this->loadController('story', 'activate');

        $control->activate($storyID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $story = $this->loadModel('story')->getByID($storyID);

        $this->send(200, $story);
    }
}

