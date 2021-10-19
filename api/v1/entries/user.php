<?php
/**
 * The user entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class userEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function get($userID = 0)
    {
        /* Get my info defaultly. */
        if(!$userID) return $this->getInfo($this->param('fields', ''));

        /* Get user by id. */
        $control = $this->loadController('user', 'profile');
        $control->profile($userID);

        $data = $this->getData();
        if(!$data) return $this->send404(); // If no user, send 404.

        $user = $data->data->user;
        unset($user->password);

        $this->send(200, $user);
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

        $profile = $this->loadModel('user')->getById($this->app->user->account);
        unset($profile->password);

        $info->profile = $this->format($profile, 'last:time,locked:time,birthday:date,join:date');
        $info->profile->role = array('code' => $info->profile->role, 'name' => $this->lang->user->roleList[$info->profile->role]);

        if(!$fields) return $this->send(200, $info);

        /* Set other fields. */
        $fields = explode(',', $fields);

        $this->loadModel('my');
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'product':
                    $info->product = array('total' => 0, 'products' => array());

                    $products = $this->my->getProducts();
                    if($products)
                    {
                        $info->product['total']    = $products->allCount;
                        $info->product['products'] = $products->products;
                    }
                    break;
                case 'project':
                    $info->project = array('total' => 0, 'projects' => array());

                    $projects = $this->my->getDoingProjects();
                    if($projects)
                    {
                        $info->project['total']    = $projects->doingCount;
                        $info->project['projects'] = $projects->projects;
                    }
                    break;
                case 'actions':
                    $info->actions = $this->my->getActions();
                    break;
                case 'task':
                    $info->task = array('total' => 0, 'tasks' => array());

                    $control = $this->loadController('my', 'task');
                    $control->task($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->task['total'] = $data->data->pager->recTotal;
                        $info->task['tasks'] = $data->data->tasks;
                    }

                    break;
                case 'bug':
                    $info->bug = array('total' => 0, 'bugs' => array());

                    $control = $this->loadController('my', 'bug');
                    $control->bug($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->bug['total'] = $data->data->pager->recTotal;
                        $info->bug['bugs']  = $data->data->bugs;
                    }

                    break;
                case 'todo':
                    $info->todo = array('total' => 0, 'todos' => array());

                    $control = $this->loadController('my', 'todo');
                    $control->todo($this->param('date', 'all'), '', 'all', 'date_desc', 0, 0, $this->param('limit', 10), 1);
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->todo['total'] = $data->data->pager->recTotal;
                        $info->todo['todos'] = $data->data->todos;
                    }

                    break;
                case 'issue':
                    if(!empty($this->config->maxVersion))
                    {
                        $info->issue = array('total' => 0, 'issues' => array());

                        $control = $this->loadController('my', 'issue');
                        $control->issue('createdBy', 'id_desc', 0, $this->param('limit', 10), 1);
                        $data = $this->getData();

                        if($data->status == 'success')
                        {
                            $info->issue['total']  = $data->data->pager->recTotal;
                            $info->issue['issues'] = $data->data->issues;
                        }
                    }
                    break;
                case 'risk':
                    if(!empty($this->config->maxVersion))
                    {
                        $info->risk = array('total' => 0, 'risks' => array());

                        $control = $this->loadController('my', 'risk');
                        $control->risk('createdBy', 'id_desc', 0, $this->param('limit', 10), 1);
                        $data = $this->getData();

                        if($data->status == 'success')
                        {
                            $info->risk['total'] = $data->data->pager->recTotal;
                            $info->risk['risks'] = $data->data->risks;
                        }
                    }
                    break;
                case 'overview':
                    $info->overview = $this->my->getOverview();
                    break;
                case 'contribute':
                    $info->contribute = $this->my->getContribute();
                    break;
            }
        }

        $this->send(200, $info);
    }

    /**
     * PUT method.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
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

    /**
     * DELETE method.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function delete($userID)
    {
        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));

        $control = $this->loadController('user', 'delete');
        $control->delete($userID);

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
