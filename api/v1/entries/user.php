<?php
/**
 * 禅道API的user资源类
 * 版本V1
 *
 * The user entry point of zentaopms
 * Version 1
 */
class userEntry extends Entry
{
    public function get($userID = 0)
    {
        /* Get my info defaultly. */
        if(!$userID) return $this->getInfo();

        /* Get user by id. */
        $control = $this->loadController('user', 'profile');
        $control->profile($userID);

        $data = $this->getData();
        $user = $data->data->user;
        unset($user->password);

        $this->send(200, $this->format($user, 'last:time,locked:time'));
    }

    private function getInfo()
    {
        $info = $this->loadModel('my')->getInfo();

        $info->product = $this->my->getProducts();
        $info->project = $this->my->getProjects();
        $info->actions = $this->my->getActions();

        $this->send(200, $info);
    }

    public function put($userID)
    {
        $oldUser = $this->loadModel('user')->getByID($userID, 'id');

        /* Set $_POST variables. */
        $fields = 'account,dept,realname,email,commiter,gender';
        $this->batchSetPost($fields, $oldUser);

        $this->setPost('password1', $this->request('password', ''));
        $this->setPost('password2', $this->request('password', ''));
        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));

        $control = $this->loadController('user', 'edit');
        $control->edit($userID);

        $this->getData();
        $user = $this->user->getByID($userID, 'id');
        unset($user->password);

        $this->send(200, $this->format($user, 'last:time,locked:time'));
    }

    public function delete($userID)
    {
        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));

        $control = $this->loadController('user', 'delete');
        $control->delete($userID);

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
