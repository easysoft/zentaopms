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
        $info->profile->role  = array('code' => $info->profile->role, 'name' => $this->lang->user->roleList[$info->profile->role]);
        $info->profile->admin = strpos($this->app->company->admins, ",{$profile->account},") !== false;

        if(!$fields) return $this->send(200, $info);

        /* Set other fields. */
        $fields = explode(',', strtolower($fields));

        $this->loadModel('my');
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'product':
                    $info->product = array('total' => 0, 'products' => array());

                    $products = $this->my->getProducts('ownbyme');
                    if($products)
                    {
                        $info->product['total']    = $products->allCount;
                        $info->product['products'] = $products->products;
                    }
                    break;
                case 'undoneproduct':
                    $info->undoneProduct = array('total' => 0, 'products' => array());

                    $products = $this->my->getProducts('undone');
                    if($products)
                    {
                        $info->undoneProduct['total']    = $products->allCount;
                        $info->undoneProduct['products'] = $products->products;
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
                case 'execution':
                    $info->execution = array('total' => 0, 'executions' => array());
                    if(!common::hasPriv('my', 'execution')) break;

                    $control = $this->loadController('my', 'execution');
                    $control->execution($this->param('type', 'undone'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->execution['total'] = $data->data->pager->recTotal;
                        $info->execution['executions'] = array_values((array)$data->data->executions);
                    }
                    break;
                case 'actions':
                    $info->actions = $this->my->getActions();
                    break;
                case 'task':
                    $info->task = array('total' => 0, 'tasks' => array());
                    if(!common::hasPriv('my', 'task')) break;

                    $control = $this->loadController('my', 'task');
                    $control->task($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->task['total'] = $data->data->pager->recTotal;
                        $info->task['tasks'] = array_values((array)$data->data->tasks);
                    }

                    break;
                case 'bug':
                    $info->bug = array('total' => 0, 'bugs' => array());
                    if(!common::hasPriv('my', 'bug')) break;

                    $control = $this->loadController('my', 'bug');
                    $control->bug($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $bugs = array();
                        foreach($data->data->bugs as $bug)
                        {
                            $status = array('code' => $bug->status, 'name' => $this->lang->bug->statusList[$bug->status]);
                            if($bug->status == 'active' and $bug->confirmed) $status = array('code' => 'confirmed', 'name' => $this->lang->bug->labelConfirmed);
                            if($bug->resolution == 'postponed') $status = array('code' => 'postponed', 'name' => $this->lang->bug->labelPostponed);
                            if(!empty($bug->delay)) $status = array('code' => 'delay', 'name' => $this->lang->bug->overdueBugs);
                            $bug->status = $status;

                            $bugs[$bug->id] = $bug;
                        }

                        $storyChangeds = $this->dao->select('t1.id')->from(TABLE_BUG)->alias('t1')
                            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
                            ->where('t1.id')->in(array_keys($bugs))
                            ->andWhere('t1.story')->ne('0')
                            ->andWhere('t1.storyVersion != t2.version')
                            ->fetchAll();
                        foreach($storyChangeds as $bugID)
                        {
                            $status = array('code' => 'storyChanged', 'name' => $this->lang->bug->storyChanged);
                            $bugs[$bugID]->status = $status;
                        }

                        $info->bug['total'] = $data->data->pager->recTotal;
                        $info->bug['bugs']  = array_values($bugs);
                    }

                    break;
                case 'todo':
                    $info->todo = array('total' => 0, 'todos' => array());
                    if(!common::hasPriv('my', 'todo')) break;

                    $control = $this->loadController('my', 'todo');
                    $control->todo($this->param('date', 'all'), '', 'all', 'date_desc', 0, 0, $this->param('limit', 5), 1);
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->todo['total'] = $data->data->pager->recTotal;
                        $info->todo['todos'] = array_values((array)$data->data->todos);
                    }

                    break;
                case 'story':
                    $info->story = array('total' => 0, 'stories' => array());
                    if(!common::hasPriv('my', 'story')) break;

                    $control = $this->loadController('my', 'story');
                    $control->story($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $stories = array();
                        foreach($data->data->stories as $story)
                        {
                            $story->status = array('code' => $story->status, 'name' => $this->lang->story->statusList[$story->status]);
                            $stories[$story->id] = $story;
                        }

                        $info->story['total']   = $data->data->pager->recTotal;
                        $info->story['stories'] = array_values($stories);
                    }

                    break;
                case 'issue':
                    $info->issue = array('total' => 0, 'issues' => array());
                    if(!common::hasPriv('my', 'issue')) break;

                    if(!empty($this->config->maxVersion))
                    {
                        $control = $this->loadController('my', 'issue');
                        $control->issue('createdBy', 'id_desc', 0, $this->param('limit', 5), 1);
                        $data = $this->getData();

                        if($data->status == 'success')
                        {
                            $info->issue['total']  = $data->data->pager->recTotal;
                            $info->issue['issues'] = array_values((array)$data->data->issues);
                        }
                    }
                    break;
                case 'risk':
                    $info->risk = array('total' => 0, 'risks' => array());
                    if(!common::hasPriv('my', 'risk')) break;

                    if(!empty($this->config->maxVersion))
                    {
                        $control = $this->loadController('my', 'risk');
                        $control->risk('createdBy', 'id_desc', 0, $this->param('limit', 5), 1);
                        $data = $this->getData();

                        if($data->status == 'success')
                        {
                            $info->risk['total'] = $data->data->pager->recTotal;
                            $info->risk['risks'] = array_values((array)$data->data->risks);
                        }
                    }
                    break;
                case 'meeting':
                    $info->meeting = array('total' => 0, 'meetings' => array());
                    if(!common::hasPriv('my', 'myMeeting')) break;

                    if(!empty($this->config->maxVersion))
                    {
                        $control = $this->loadController('my', 'myMeeting');
                        $control->myMeeting('all', 'id_desc', 0, $this->param('limit', 5), 1);
                        $data = $this->getData();

                        if($data->status == 'success')
                        {
                            $info->meeting['total']    = $data->data->pager->recTotal;
                            $info->meeting['meetings'] = array_values((array)$data->data->meetings);
                        }
                    }
                    break;
                case 'overview':
                    $info->overview = $this->my->getOverview();
                    break;
                case 'contribute':
                    $info->contribute = $this->my->getContribute();
                    break;
                case 'rights':
                    $inAdminGroup = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
                        ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.group=t2.id')
                        ->where('t1.account')->eq($info->profile->account)
                        ->andWhere('t2.role')->eq('admin')
                        ->fetch();

                    $info->rights = array();
                    $info->rights['admin']  = (!empty($inAdminGroup) or $this->app->user->admin);
                    $info->rights['rights'] = $this->app->user->rights['rights'];
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
