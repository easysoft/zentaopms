<?php
/**
 * The products entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * @return void
     */
    public function get($programID = 0)
    {
        $fields = strtolower($this->param('fields', ''));
        if(strpos(",{$fields},", ',dropmenu,') !== false) return $this->getDropMenu();

        if(!$programID) $programID = $this->param('program', 0);

        if($programID)
        {
            $control = $this->loadController('program', 'product');
            $control->product($programID, $this->param('status', 'all'), $this->param('order', 'order_asc'), 0, 10000);

            /* Response */
            $data = $this->getData();
            if(isset($data->status) and $data->status == 'success')
            {
                $result   = array();
                $products = $data->data->products;
                foreach($products as $product) $result[] = $this->format($product, 'createdDate:time');

                return $this->send(200, array('products' => $result));
            }
        }
        else
        {
            $mergeChildren = $this->param('mergeChildren', '');

            $control = $this->loadController('product', 'all');
            $control->all($this->param('status', 'all'), $this->param('order', 'order_asc'));

            /* Response */
            $data = $this->getData();
            if(isset($data->status) and $data->status == 'success')
            {
                $result   = array();
                if($mergeChildren)
                {
                    $programs = array();
                    foreach($data->data->productStructure as $programID => $program)
                    {
                        $programs[$programID] = new stdclass();
                        if(!empty($programID))
                        {
                            $programs[$programID]->id   = $programID;
                            $programs[$programID]->name = $program->programName;
                            $programs[$programID]->type = 'program';
                        }

                        $unclosedTotal = 0;
                        foreach($program as $field => $value)
                        {
                            if(!isset($programs[$programID]->children)) $programs[$programID]->children = array();
                            if(isset($value->products))
                            {
                                $lineID = $field;
                                if(empty($lineID))
                                {
                                    foreach($value->products as $product)
                                    {
                                        unset($product->desc);
                                        $product->stories      = (array)$product->stories;
                                        $product->requirements = (array)$product->requirements;
                                        $closedTotal = ($product->stories['closed'] + $product->requirements['closed']);
                                        $allTotal    = (array_sum($product->stories) + array_sum($product->requirements));
                                        $product->progress = empty($closedTotal) ? 0 : round($closedTotal / $allTotal * 100, 1);
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
                                        $product->stories      = (array)$product->stories;
                                        $product->requirements = (array)$product->requirements;
                                        $closedTotal = ($product->stories['closed'] + $product->requirements['closed']);
                                        $allTotal    = (array_sum($product->stories) + array_sum($product->requirements));
                                        $product->progress = empty($closedTotal) ? 0 : round($closedTotal / $allTotal * 100, 1);
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

                    return $this->send(200, $programs);
                }
                else
                {
                    $products = $data->data->productStats;
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

                        $result[] = $this->format($product, 'createdDate:time');
                    }

                    $data = array();
                    $data['total']    = count($result);
                    $data['products'] = $result;

                    $withUser = $this->param('withUser', '');
                    if(!empty($withUser)) $data['users'] = $this->loadModel('user')->getListByAccounts($accounts, 'account');

                    return $this->send(200, $data);
                }
            }
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        $fields = 'program,code,line,name,PO,QD,RD,type,desc,whitelist';
        $this->batchSetPost($fields);

        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('whitelist', $this->request('whitelist', array()));

        $control = $this->loadController('product', 'create');
        $this->requireFields('name,code');

        $control->create($this->request('program', 0));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        /* Response */
        $product = $this->loadModel('product')->getByID($data->id);
        $product = $this->format($product, 'createdDate:time,whitelist:[]string');

        $this->send(200, $product);
    }

    /**
     * Get dropmenu.
     *
     * @access public
     * @return void
     */
    public function getDropMenu()
    {
        $control = $this->loadController('product', 'ajaxGetDropMenu');
        $control->ajaxGetDropMenu($this->request('productID', 0), $this->request('module', 'product'), $this->request('method', 'browse'), $this->request('extra', ''), $this->request('from', ''));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

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
        $this->send(200, $dropMenu);
    }
}
