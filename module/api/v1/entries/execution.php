<?php
/**
 * The execution entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class executionEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function get($executionID)
    {
        $fields = $this->param('fields');
        $status = $this->param('status', 'all');

        $control = $this->loadController('execution', 'view');
        $control->view($executionID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $execution = $this->format($data->data->execution, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,PM:user,PO:user,RD:user,QD:user,whitelist:userList,begin:date,end:date,realBegan:date,realEnd:date,deleted:bool');

        $execution->progress    = ($execution->totalConsumed + $execution->totalLeft) ? round($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 100, 1) : 0;
        $execution->teamMembers = array_values((array)$data->data->teamMembers);
        $execution->products    = array();
        foreach($data->data->products as $productID => $executionProduct)
        {
            if($status == 'noclosed' and $executionProduct->status == 'closed') continue;

            $product = new stdclass();
            $product->id = $executionProduct->id;
            $product->name = $executionProduct->name;
            $product->plans = array();
            foreach($executionProduct->plans as $planID)
            {
                $plan = new stdclass();
                $plan->id   = trim($planID, ',');
                $plan->name = $data->data->planGroups->{$productID}->{$plan->id};
                $product->plans[] = $plan;
            }
            $execution->products[] = $product;
        }

        $this->loadModel('testcase');
        $execution->caseReview = ($this->config->testcase->needReview or !empty($this->config->testcase->forceReview));

        if(!$fields) $this->send(200, $execution);

        $users = $data->data->users;

        /* Set other fields. */
        $fields = explode(',', strtolower($fields));
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'modules':
                    $control = $this->loadController('tree', 'browsetask');
                    $control->browsetask($executionID);
                    $data = $this->getData();
                    if(isset($data->status) and $data->status == 'success')
                    {
                        $execution->modules = $data->data->tree;
                    }
                case 'builds':
                    $execution->builds  = $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'noempty,noterminate,nodone', $executionID, 'execution');
                    break;
                case 'moduleoptionmenu':
                    $execution->moduleOptionMenu = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 'allModule');
                    break;
                case 'members':
                    $execution->members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');;
                    unset($execution->members['']);
                    break;
                case 'stories':
                    $stories = $this->loadModel('story')->getExecutionStories($executionID);
                    foreach($stories as $storyID => $story) $stories[$storyID] = $this->filterFields($story, 'id,title,module,pri,status,stage,estimate');

                    $execution->stories = array_values($stories);
                    break;
                case 'actions':
                    $actions = $data->data->actions;
                    $execution->actions = $this->loadModel('action')->processActionForAPI($actions, $users, $this->lang->execution);
                    break;
                case "dynamics":
                    $dynamics = $data->data->dynamics;
                    $execution->dynamics = $this->loadModel('action')->processDynamicForAPI($dynamics);
                    break;
                case 'chartdata':
                    list($dateList, $interval) = $this->loadModel('execution')->getDateList($execution->begin, $execution->end, 'noweekend', 0, 'Y-m-d');
                    $execution->chartData = $this->execution->buildBurnData($executionID, $dateList, 'left');
                    break;
            }
        }

        return $this->send(200, $execution);
    }

    /**
     * PUT method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function put($executionID)
    {
        $oldExecution = $this->loadModel('execution')->getByID($executionID);

        $useCode = $this->checkCodeUsed();
        /* Set $_POST variables. */
        $fields = 'project,name,begin,end,lifetime,desc,days,acl,status,PO,PM,QD,RD';
        if($useCode) $fields .= 'code';
        $this->batchSetPost($fields, $oldExecution);

        $this->setPost('whitelist', $this->request('whitelist', explode(',', $oldExecution->whitelist)));

        $products = $this->loadModel('product')->getProducts($executionID);
        $this->setPost('products', $this->request('products', array_keys($products)));

        $control = $this->loadController('execution', 'edit');
        $control->edit($executionID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $execution = $this->execution->getByID($executionID);
        return $this->send(200, $this->format($execution, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,PM:user,PO:user,RD:user,QD:user,whitelist:userList,begin:date,end:date,realBegan:date,realEnd:date,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function delete($executionID)
    {
        $control = $this->loadController('execution', 'delete');
        $control->delete($executionID, 'true');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
