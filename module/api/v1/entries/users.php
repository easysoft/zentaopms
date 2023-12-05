<?php
/**
 * The users entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @return string
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
     * @return string
     */
    public function post()
    {
        $fields = 'type,dept,account,password,visions,realname,join,role,email,commiter,gender,group,passwordStrength';
        $this->batchSetPost($fields);

        if(!in_array($this->request('gendar', zget($_POST, 'gendar', 'f')), array('f', 'm'))) return $this->sendError(400, "The value of gendar must be 'f' or 'm'");

        $password = $this->request('password', zget($_POST, 'password', '')) ? md5($this->request('password', zget($_POST, 'password', ''))) : '';

        $visions = $this->request('visions', array('rnd'));
        if(!is_array($visions)) $visions = explode(',', $visions);

        if($this->request('group')) $this->setPost('group', explode(',', $this->request('group')));
        $this->setPost('password1', $password);
        $this->setPost('password2', $password);
        $this->setPost('passwordStrength', 3);
        $this->setPost('visions', $visions);
        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));
        unset($_POST['password']);

        $control = $this->loadController('user', 'create');
        $this->requireFields('account,password1,realname');

        $control->create();

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $user = $this->loadModel('user')->getByID($data->id, 'id');
        unset($user->password);

        return $this->send(201, $this->format($user, 'last:time,locked:time'));
    }
}
