<?php
/**
 * The products entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class productsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function get($programID = 0)
    {
        $fields = $this->param('fields', '');
        if(strpos(strtolower(",{$fields},"), ',dropmenu,') !== false) return $this->getDropMenu();

        if(!$programID) $programID = $this->param('program', 0);
        $projectID     = $this->param('project', 0);
        $mergeChildren = $this->param('mergeChildren', '');

        if($programID)
        {
            $control = $this->loadController('program', 'product');
            $control->product($programID, $this->param('status', 'all'), $this->param('order', 'order_asc'), 0, $this->param('limit', '20'), $this->param('page', '1'));

            /* Response */
            $data = $this->getData();
            if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
            if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

            $products = $data->data->products;

        }
        elseif($projectID)
        {
            $control = $this->loadController('project', 'manageProducts');
            $control->manageProducts($projectID);

            /* Response */
            $data = $this->getData();
            if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
            if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

            $products = $data->data->linkedProducts;
        }
        else
        {
            $control = $this->loadController('product', 'all');
            $control->all($this->param('status', 'all'), $this->param('order', 'program_asc'), 0, 0, $this->param('limit', 100), $this->param('page', 1));

            /* Response */
            $data = $this->getData();
            if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
            if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

            $products = $data->data->productStats;
            if($mergeChildren) $products = $data->data->productStructure;
        }
        $pager = $data->data->pager;

        $result = array();
        if($mergeChildren)
        {
            $programs = $this->mergeChildren($products);
            return $this->send(200, $programs);
        }
        else
        {
            $accounts = array();
            foreach($products as $product)
            {
                $accounts[$product->PO]        = $product->PO;
                $accounts[$product->QD]        = $product->QD;
                $accounts[$product->RD]        = $product->RD;
                $accounts[$product->createdBy] = $product->createdBy;
                if(isset($product->feedback)) $accounts[$product->feedback] = $product->feedback;
                if(!empty($product->mailto))
                {
                    foreach(explode(',', $product->mailto) as $account)
                    {
                        $account = trim($account);
                        if(empty($account)) continue;
                        $accounts[$account] = $account;
                    }
                }

                $result[] = $this->format($product, 'createdDate:time,whitelist:userList,createdBy:user,PO:user,RD:user,QD:user');
            }

            $data = array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'products' => $result);
            $withUser = $this->param('withUser', '');
            if(!empty($withUser)) $data['users'] = $this->loadModel('user')->getListByAccounts($accounts, 'account');

            return $this->send(200, $data);
        }
    }

    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $useCode = $this->checkCodeUsed();

        $fields = 'program,line,name,PO,QD,RD,type,desc,whitelist';
        if($useCode) $fields .= ',code';

        $this->batchSetPost($fields);

        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('whitelist', $this->request('whitelist', array()));

        $control = $this->loadController('product', 'create');

        $requireFields = 'name';
        if($useCode) $requireFields .= ',code';
        $this->requireFields($requireFields);

        $control->create($this->request('program', 0));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        /* Response */
        $product = $this->loadModel('product')->getByID($data->id);
        $product = $this->format($product, 'createdDate:time,whitelist:userList,createdBy:user,PO:user,RD:user,QD:user');

        return $this->send(201, $product);
    }

    /**
     * Get dropmenu.
     *
     * @access public
     * @return string
     */
    public function getDropMenu()
    {
        $control = $this->loadController('product', 'ajaxGetDropMenu');
        $control->ajaxGetDropMenu($this->request('productID', 0), $this->request('module', 'product'), $this->request('method', 'browse'), $this->request('extra', ''), $this->request('from', ''));

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $dropMenu = array('owner' => array(), 'other' => array(), 'closed' => array());
        foreach($data->data->products as $programID => $products)
        {
            foreach($products as $product)
            {
                $product = $this->filterFields($product, 'id,program,name,code,status,PO');

                if($product->status == 'closed')
                {
                    $dropMenu['closed'][] = $product;
                }
                elseif($product->PO == $this->app->user->account)
                {
                    $dropMenu['owner'][] = $product;
                }
                else
                {
                    $dropMenu['other'][] = $product;
                }
            }
        }
        return $this->send(200, $dropMenu);
    }

    /**
     * Merge children products.
     *
     * @param  array  $products
     * @access public
     * @return string
     */
    public function mergeChildren($products)
    {
        $programs = array();
        foreach($products as $programID => $program)
        {
            $programs[$programID] = new stdclass();
            if(!empty($programID))
            {
                $programs[$programID]->id   = $programID;
                $programs[$programID]->name = $program->programName;
                $programs[$programID]->type = 'program';
            }

            $unclosedTotal = 0;
            foreach($program as $lineID => $value)
            {
                if(!isset($programs[$programID]->children)) $programs[$programID]->children = array();
                if(isset($value->products))
                {
                    if(empty($lineID))
                    {
                        foreach($value->products as $product)
                        {
                            unset($product->desc);
                            $programs[$programID]->children[$product->id] = $product;
                            if($product->status != 'closed') $unclosedTotal += 1;
                        }
                    }
                    else
                    {
                        $line = new stdclass();
                        $line->id   = $lineID;
                        $line->name = $value->lineName;
                        $line->type = 'line';

                        $line->children = array();
                        foreach($value->products as $product)
                        {
                            unset($product->desc);
                            $line->children[$product->id] = $product;
                            if($product->status != 'closed') $unclosedTotal += 1;
                        }
                        if(isset($line->children)) $line->children = array_values($line->children);

                        $programs[$programID]->children[$lineID] = $line;
                    }
                    if(isset($programs[$programID]->children)) $programs[$programID]->children = array_values($programs[$programID]->children);
                    $programs[$programID]->unclosedTotal = $unclosedTotal;
                }
            }
        }

        $topProducts = array();
        if(isset($programs[0]))
        {
            $topProducts = $programs[0]->children;
            unset($programs[0]);
        }

        $programs = array_values($programs);
        foreach($topProducts as $product) $programs[] = $product;

        return $programs;
    }
}
