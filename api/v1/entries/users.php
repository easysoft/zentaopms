<?php
/**
 * 禅道API的users资源类
 * 版本V1
 *
 * The users entry point of zentaopms
 * Version 1
 */
class usersEntry extends entry 
{
    public function get()
    {
        $control = $this->loadController('company', 'browse');
        $control->browse('inside', 0, $this->param('type', 'bydept'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $users  = $data->data->users;
            $pager  = $data->data->pager;
            $result = array();
            foreach($users as $user) $result[] = $this->format($user, 'locked:time');
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'users' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }

    public function post()
    {
        $fields = 'account,dept,realname,email,commiter,gender';
        $this->batchSetPost($fields);

        $this->setPost('password1', $this->request('password'));
        $this->setPost('password2', $this->request('password'));
        $this->setPost('passwordStrength', 3);
        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));

        $control = $this->loadController('user', 'create');
        $this->requireFields('account,password1,realname');

        $control->create();

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $user = $this->loadModel('user')->getByID($data->id, 'id');
        unset($user->password);

        $this->send(201, $this->format($user, 'last:time,locked:time'));
    }
}
