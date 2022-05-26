<?php
/**
 * The users entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class usersEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        if(!common::hasPriv('company', 'browse')) return $this->sendError(400, 'error: no company-browse priv.');

        $appendFields = $this->param('fileds', '');
        $type         = $this->param('type', 'bydept');
        $limit        = (int)$this->param('limit', 20);

        $pager = null;
        if($limit)
        {
            $this->app->loadClass('pager', $static = true);
            $pager = pager::init(0, $limit, $this->param('page', 1));
        }

        $users = $this->loadModel('company')->getUsers($this->param('browse', 'inside'), $type, 0, 0, $this->param('order', 'id_desc'), $pager);
        $result = array();
        foreach($users as $user)
        {
            $user = $this->filterFields($user, 'id,dept,account,realname,role,pinyin,email,' . $appendFields);
            $result[] = $this->format($user, 'locked:time');
        }

        $pageID = $pager ? $pager->pageID     : 1;
        $total  = $pager ? $pager->recTotal   : count($result);
        $limit  = $pager ? $pager->recPerPage : $total;

        return $this->send(200, array('page' => $pageID, 'total' => $total, 'limit' => $limit, 'users' => $result));
    }

    /**
     * POST method.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        $fields = 'account,dept,realname,email,commiter,gender';
        $this->batchSetPost($fields);

        if(!in_array($this->request('gendar', 'f'), array('f', 'm'))) return $this->sendError(400, "The value of gendar must be 'f' or 'm'");

        $password = $this->request('password', '') ? md5($this->request('password')) : '';

        $visions = $this->request('visions', 'rnd');
        if(is_array($visions)) $visions = implode(',', $visions);

        $this->setPost('password1', $password);
        $this->setPost('password2', $password);
        $this->setPost('passwordStrength', 3);
        $this->setPost('visions', $visions);
        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));

        $control = $this->loadController('user', 'create');
        $this->requireFields('account,password1,realname');

        $control->create();

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $user = $this->loadModel('user')->getByID($data->id, 'id');
        unset($user->password);

        $this->send(201, $this->format($user, 'last:time,locked:time'));
    }
}
