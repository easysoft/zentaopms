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
        if(!$userID) return $this->getInfo($this->param('fields', ''));

        /* Get user by id. */
        $control = $this->loadController('user', 'profile');
        $control->profile($userID);

        $data = $this->getData();
        $user = $data->data->user;
        unset($user->password);

        $this->send(200, $this->format($user, 'last:time,locked:time'));
    }

    /**
     * Get my info.
     *
     * @param string $fields
     *
     * @access private
     * @return void
     */
    private function getInfo($fields = '')
    {
        $info = new stdclass();

        $info->profile = $this->loadModel('user')->getById($this->app->user->account);
        unset($info->profile->password);

        if(!$fields) return $this->send(200, $info);

        /* Set other fields. */
        $fields = explode(',', $fields);

        $this->loadModel('my');
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'product':
                    $info->product = $this->my->getProducts();
                    break;
                case 'project':
                    $info->project = $this->my->getProjects();
                    break;
                case 'doc':
                    $info->doc = $this->my->getDocs();
                    break;
                case 'actions':
                    $info->actions = $this->my->getActions();
                    break;
                case 'task':
                    $info->task = array('count' => 0, 'recentTask' => array());

                    $control = $this->loadController('my', 'task');
                    $control->task($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->task['count']       = $data->data->pager->recTotal;
                        $info->task['recentTasks'] = $data->data->tasks;
                    }

                    break;
                case 'todo':
                    $info->todo = array('count' => 0, 'recentTodos' => array());

                    $control = $this->loadController('my', 'todo');
                    $control->todo($this->param('date', 'all'), '', 'all', 'date_desc', 0, 0, $this->param('limit', 10), 1);
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->todo['count']       = $data->data->pager->recTotal;
                        $info->todo['recentTodos'] = $data->data->todos;
                    }

                    break;
            }
        }

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
