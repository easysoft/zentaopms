<?php
/**
 * The product entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class productEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function get($productID)
    {
        $fields = $this->param('fields');

        $control = $this->loadController('product', 'view');
        $control->view($productID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $product = $this->format($data->data->product, 'createdDate:time,whitelist:userList,createdBy:user,PO:user,RD:user,QD:user,feedback:user');

        $this->loadModel('testcase');
        $product->caseReview = ($this->config->testcase->needReview or !empty($this->config->testcase->forceReview));

        if(!$fields) return $this->send(200, $product);

        /* Set other fields. */
        $fields = explode(',', strtolower($fields));
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'modules':
                    $control = $this->loadController('tree', 'browse');
                    $control->browse($productID, 'story');
                    $data = $this->getData();
                    if(isset($data->status) and $data->status == 'success')
                    {
                        $product->modules = $data->data->tree;
                    }
                    break;
                case 'execution':
                    $product->execution = $this->loadModel('product')->getExecutionPairsByProduct($productID);
                    break;
                case 'bugstatistic':
                    $product->bugStatistic = $this->loadModel('bug')->getStatistic($productID);
                    break;
                case 'moduleoptionmenu':
                    $modules = $this->loadModel('tree')->getOptionMenu($productID, $this->param('moduleType', 'story'));
                    $product->moduleOptionMenu = array();
                    foreach($modules as $id => $name) $product->moduleOptionMenu[] = array('id' => $id, 'name' => $name);
                    break;
                case 'parentstories':
                    $product->parentstories= $this->loadModel('story')->getParentStoryPairs($productID);
                    break;
                case 'builds':
                    $product->builds = $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'noempty,noterminate,nodone,withbranch', $this->param('object', 0), $this->param('objectType', 'execution'));
                    break;
                case 'actions':
                    $product->addComment = common::hasPriv('action', 'comment') ? true : false;

                    $users   = $this->loadModel('user')->getPairs();
                    $actions = $data->data->actions;
                    $product->actions = $this->loadModel('action')->processActionForAPI($actions, $users, $this->lang->product);
                    break;
                case 'lastexecution':
                    $execution = $this->dao->select('t2.id,t2.name,t2.type,t2.progress')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                        ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                        ->where('t2.deleted')->eq(0)
                        ->andWhere('t1.product')->eq($productID)
                        ->andWhere('t2.type')->in('sprint,stage')
                        ->orderBy('t2.id desc')
                        ->limit(1)
                        ->fetch();

                    $product->lastExecution = $execution;
                    break;
            }
        }

        return $this->send(200, $product);
    }

    /**
     * PUT method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function put($productID)
    {
        $useCode = $this->checkCodeUsed();
        $oldProduct = $this->loadModel('product')->getByID($productID);

        /* Set $_POST variables. */
        $fields = 'program,line,name,PO,QD,RD,type,desc,whitelist,status,acl';
        if($useCode) $fields .= ',code';
        $this->batchSetPost($fields, $oldProduct);

        $control = $this->loadController('product', 'edit');
        $control->edit($productID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $product = $this->product->getByID($productID);
        return $this->send(200, $this->format($product, 'createdDate:time,whitelist:userList,createdBy:user,PO:user,RD:user,QD:user'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function delete($productID)
    {
        $control = $this->loadController('product', 'delete');
        $control->delete($productID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
