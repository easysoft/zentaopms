<?php
/**
 * The project entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class projectEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function get($projectID)
    {
        $fields = strtolower($this->param('fields'));

        $control = $this->loadController('project', 'view');
        $control->view($projectID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $project = $this->format($data->data->project, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,realBegan:date,realEnd:date,PM:user,whitelist:userList,deleted:bool');

        $this->loadModel('testcase');
        $project->caseReview = ($this->config->testcase->needReview or !empty($this->config->testcase->forceReview));

        if(empty($fields)) return $this->send(200, $project);

        /* Set other fields. */
        $fields = explode(',', $fields);
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'team':
                    $teams    = array();
                    $accounts = array();
                    foreach($data->data->teamMembers as $account => $team)
                    {
                        $team = $this->filterFields($team, "account,role,join,realname");

                        $teams[$account]    = $team;
                        $accounts[$account] = $account;
                    }
                    $users = $this->loadModel('user')->getListByAccounts($accounts, 'account');
                    foreach($teams as $account => $team)
                    {
                        $user = zget($users, $account, '');
                        $team->avatar = $user->avatar;
                    }

                    $project->teams = $teams;
                    break;
                case "products":
                    $project->products = array();
                    $productList = $this->loadModel('product')->getProducts($projectID, $this->param('status', 'all'));
                    foreach($productList as $product) $project->products[] = $product;
                    break;
                case "stat":
                    $project->stat = $data->data->statData;
                    break;
                case "workhour":
                    $workhour = $data->data->workhour;
                    $workhour->progress = ($workhour->totalConsumed + $workhour->totalLeft) ? floor($workhour->totalConsumed / ($workhour->totalConsumed + $workhour->totalLeft) * 1000) / 1000 * 100 : 0;
                    $project->workhour  = $workhour;
                    break;
                case "actions":
                    $actions = $data->data->actions;
                    $project->actions = $this->loadModel('action')->processActionForAPI($actions, (array)$data->data->users, $this->lang->project);
                    break;
                case "dynamics":
                    $dynamics = $data->data->dynamics;
                    $project->dynamics = $this->loadModel('action')->processDynamicForAPI($dynamics);
                    break;
            }
        }

        return $this->send(200, $project);
    }

    /**
     * PUT method.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function put($projectID)
    {
        $oldProject     = $this->loadModel('project')->getByID($projectID);
        $linkedProducts = $this->loadModel('product')->getProducts($projectID);

        $useCode = $this->checkCodeUsed();
        /* Set $_POST variables. */
        $fields = 'name,begin,end,acl,parent,desc,PM,whitelist,model';
        if($useCode) $fields .= ',code';
        $this->batchSetPost($fields, $oldProject);

        $products = array();
        $plans    = array();
        foreach($linkedProducts as $product)
        {
            $products[] = $product->id;
            foreach($product->plans as $planID) $plans[] = $planID;
        }
        $this->setPost('products', $products);
        $this->setPost('plans', $plans);

        $control = $this->loadController('project', 'edit');
        $control->edit($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $project = $this->project->getByID($projectID);
        return $this->send(200, $this->format($project, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,realBegan:date,realEnd:date,PM:user,whitelist:userList,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function delete($projectID)
    {
        $control = $this->loadController('project', 'delete');
        $control->delete($projectID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
