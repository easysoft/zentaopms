<?php
/**
 * The user entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class userEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int|string $userID
     * @access public
     * @return string
     */
    public function get($userID = 0)
    {
        /* Get my info defaultly. */
        if(!$userID) return $this->getInfo($this->param('fields', ''));

        if(!is_numeric($userID))
        {
            $user = $this->loadModel('user')->getById($userID, 'account');
            if(!$user) return $this->send404();
            $userID = $user->id;
        }

        /* Get user by id. */
        $control = $this->loadController('user', 'profile');
        $control->profile($userID);

        $data = $this->getData();
        if(!$data) return $this->send404(); // If no user, send 404.

        $user = $data->data->user;
        if($user)
        {
            $user->group = $this->loadModel('group')->getByAccount($user->account);
            unset($user->password);
        }

        return $this->send(200, $user);
    }

    /**
     * Get my info.
     *
     * @param string $fields
     *
     * @access private
     * @return string
     */
    private function getInfo($fields = '')
    {
        $info = new stdclass();

        $profile = $this->loadModel('user')->getById($this->app->user->account);
        unset($profile->password);

        $info->profile = $this->format($profile, 'last:time,locked:time,birthday:date,join:date');
        $info->profile->role          = array('code' => $info->profile->role, 'name' => $this->lang->user->roleList[$info->profile->role]);
        $info->profile->admin         = strpos($this->app->company->admins, ",{$profile->account},") !== false;
        $info->profile->superReviewer = isset($this->config->story) ? strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',') : false;
        $info->profile->view          = $this->app->user->view;

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
                        $info->product['total']    = $products->unclosedCount;
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
                case 'lastproject':
                    $info->lastProject = array('total' => 0, 'projects' => array());

                    $control = $this->loadController('project', 'ajaxGetDropMenu');
                    $control->ajaxGetDropMenu(0, 'project', 'index');
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $myProjects['owner'] = array();
                        $myProjects['other'] = array();
                        foreach($data->data->projects as $programID => $programProjects)
                        {
                            foreach($programProjects as $project)
                            {
                                if($project->status == 'closed') continue;

                                $project = $this->filterFields($project, 'id,model,type,name,code,parent,status,PM');
                                if($project->PM == $this->app->user->account)
                                {
                                    $myProjects['owner'][] = $project;
                                }
                                else
                                {
                                    $myProjects['other'][] = $project;
                                }
                            }
                        }
                        $lastProjects = array_merge($myProjects['owner'], $myProjects['other']);
                        $lastProjects = array_slice($lastProjects, 0, 3);

                        $info->lastProject['total']    = count($lastProjects);
                        $info->lastProject['projects'] = $lastProjects;
                    }
                    break;
                case 'execution':
                    $info->execution = array('total' => 0, 'executions' => array());
                    if(!common::hasPriv('my', 'work')) break;
                    $this->config->openMethods[] = 'my.execution';

                    $control = $this->loadController('my', 'execution');
                    $control->execution($this->param('type', 'undone'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->execution['total'] = $data->data->pager->recTotal;
                        $info->execution['executions'] = array_values((array)$data->data->executions);
                    }
                    break;
                case 'lastexecution':
                    $info->lastExecution = array('total' => 0, 'executions' => array());

                    $control = $this->loadController('execution', 'ajaxGetDropMenu');
                    $control->ajaxGetDropMenu(0, 'execution', 'browse', '');
                    $data = $this->getData();

                    $account = $this->app->user->account;
                    if($data->status == 'success')
                    {
                        $myExecutions['owner'] = array();
                        $myExecutions['other'] = array();
                        foreach($data->data->projectExecutions as $projectID => $projectExecutions)
                        {
                            foreach($projectExecutions as $execution)
                            {
                                if($execution->status == 'done' or $execution->status == 'closed') continue;

                                if($execution->PM == $account or isset($execution->teams->$account))
                                {
                                    $myExecutions['owner'][] = $this->filterFields($execution, 'id,model,type,name,code,parent,status,PM');
                                }
                                else
                                {
                                    $myExecutions['other'][] = $this->filterFields($execution, 'id,model,type,name,code,parent,status,PM');
                                }
                            }
                        }
                        $lastExecutions = array_merge($myExecutions['owner'], $myExecutions['other']);
                        $lastExecutions = array_slice($lastExecutions, 0, 3);

                        $info->lastExecution['total']      = count($lastExecutions);
                        $info->lastExecution['executions'] = $lastExecutions;
                    }
                    break;
                case 'actions':
                    $info->actions = $this->my->getActions();
                    break;
                case 'task':
                    $info->task = array('total' => 0, 'tasks' => array());
                    if(!common::hasPriv('my', 'work')) break;
                    $this->config->openMethods[] = 'my.task';

                    global $app;
                    $app->rawMethod = 'work';
                    $control = $this->loadController('my', 'task');
                    $control->task($this->param('type', 'assignedTo'), 0, $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->task['total'] = $data->data->pager->recTotal;
                        $info->task['tasks'] = array_values((array)$data->data->tasks);
                    }

                    break;
                case 'bug':
                    $info->bug = array('total' => 0, 'bugs' => array());
                    if(!common::hasPriv('my', 'work')) break;
                    $this->config->openMethods[] = 'my.bug';

                    global $app;
                    $app->rawMethod = 'work';
                    $control = $this->loadController('my', 'bug');
                    $control->bug($this->param('type', 'assignedTo'), 0, $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
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
                            $bug->status     = $status['code'];
                            $bug->statusName = $status['name'];

                            $bugs[$bug->id] = $bug;
                        }

                        $storyChangeds = $this->dao->select('t1.id')->from(TABLE_BUG)->alias('t1')
                            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
                            ->where('t1.id')->in(array_keys($bugs))
                            ->andWhere('t1.story')->ne('0')
                            ->andWhere('t1.storyVersion != t2.version')
                            ->fetchPairs('id', 'id');
                        foreach($storyChangeds as $bugID)
                        {
                            $status = array('code' => 'storyChanged', 'name' => $this->lang->bug->changed);
                            $bugs[$bugID]->status     = $status['code'];
                            $bugs[$bugID]->statusName = $status['name'];
                        }

                        $info->bug['total'] = $data->data->pager->recTotal;
                        $info->bug['bugs']  = array_values($bugs);
                    }

                    break;
                case 'todo':
                    $info->todo = array('total' => 0, 'todos' => array());
                    if(!common::hasPriv('my', 'todo')) break;

                    $control = $this->loadController('my', 'todo');
                    $control->todo($this->param('date', 'before'), '', 'all', 'date_desc', 0, $this->param('limit', 5), 1);
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $info->todo['total'] = $data->data->pager->recTotal;
                        $info->todo['todos'] = array_values((array)$data->data->todos);
                    }

                    break;
                case 'story':
                    $info->story = array('total' => 0, 'stories' => array());
                    if(!common::hasPriv('my', 'work')) break;
                    $this->config->openMethods[] = 'my.story';

                    global $app;
                    $app->rawMethod = 'work';
                    $control = $this->loadController('my', 'story');
                    $control->story($this->param('type', 'assignedTo'), 0, $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 5), $this->param('page', 1));
                    $data = $this->getData();

                    if($data->status == 'success')
                    {
                        $stories = array();
                        foreach($data->data->stories as $story)
                        {
                            $story->statusName = $this->lang->story->statusList[$story->status];
                            $stories[$story->id] = $story;
                        }

                        $info->story['total']   = $data->data->pager->recTotal;
                        $info->story['stories'] = array_values($stories);
                    }

                    break;
                case 'issue':
                    $info->issue = array('total' => 0, 'issues' => array());
                    if(!common::hasPriv('my', 'work')) break;

                    if($this->config->edition == 'max' or $this->config->edition == 'ipd')
                    {
                        global $app;
                        $app->rawMethod = 'work';
                        $this->config->openMethods[] = 'my.issue';
                        $control = $this->loadController('my', 'issue');
                        $control->issue('createdBy', 0, 'id_desc', 0, $this->param('limit', 5), 1);
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
                    if(!common::hasPriv('my', 'work')) break;

                    if($this->config->edition == 'max' or $this->config->edition == 'ipd')
                    {
                        global $app;
                        $app->rawMethod = 'work';
                        $this->config->openMethods[] = 'my.risk';
                        $control = $this->loadController('my', 'risk');
                        $control->risk('assignedTo', 0, 'id_desc', 0, $this->param('limit', 5), 1);
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
                    if(!common::hasPriv('my', 'work')) break;

                    if($this->config->edition == 'max' or $this->config->edition == 'ipd')
                    {
                        global $app;
                        $app->rawMethod = 'work';
                        $this->config->openMethods[] = 'my.myMeeting';
                        $control = $this->loadController('my', 'myMeeting');
                        $control->myMeeting('futureMeeting', '', 'id_desc', 0, $this->param('limit', 5), 1);
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

        return $this->send(200, $info);
    }

    /**
     * PUT method.
     *
     * @param  int|string $userID
     * @access public
     * @return string
     */
    public function put($userID)
    {
        if(!is_numeric($userID))
        {
            $user = $this->loadModel('user')->getById($userID, 'account');
            if(!$user) return $this->send404();
            $userID = $user->id;
        }

        $oldUser = $this->loadModel('user')->getByID($userID, 'id');

        /* Set $_POST variables. */
        $fields = 'dept,realname,role,join,type,visions,mobile,phone,qq,dingding,weixin,skype,whatsapp,slack,address,address,email,commiter,gender,groups,passwordStrength,locked,fails,birthday';
        $this->batchSetPost($fields, $oldUser);
        $this->setPost('account', $oldUser->account);

        $userGroups = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($oldUser->account)->fetchPairs('group', 'group');
        $this->setPost('groups', $this->request('groups', zget($_POST, 'groups', array_values($userGroups))));

        $gender = $this->request('gender', zget($_POST, 'gender', 'f'));
        if(!in_array($gender, array('f', 'm'))) return $this->sendError(400, "The value of gender must be 'f' or 'm'");
        if($this->request('gender') and !in_array($this->request('gender'), array('f', 'm'))) return $this->sendError(400, "The value of gendar must be 'f' or 'm'");
        $this->setPost('gender', $gender);

        $password = $this->request('password', zget($_POST, 'password', ''));
        if($password)
        {
            $this->setPost('password1', md5($password));
            $this->setPost('password2', md5($password));
            $this->setPost('passwordStrength', 2);
        }
        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));

        $control = $this->loadController('user', 'edit');
        $control->edit($userID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        $user = $this->user->getByID($userID, 'id');
        unset($user->password);

        return $this->send(200, $this->format($user, 'last:time,locked:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int|string $userID
     * @access public
     * @return string
     */
    public function delete($userID)
    {
        if(!is_numeric($userID))
        {
            $user = $this->loadModel('user')->getById($userID, 'account');
            if(!$user) return $this->send404();
            $userID = $user->id;
        }

        $this->setPost('verifyPassword', md5($this->app->user->password . $this->app->session->rand));

        $control = $this->loadController('user', 'delete');
        $control->delete($userID);

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
