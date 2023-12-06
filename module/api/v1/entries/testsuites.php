<?php
/**
 * The testsuites entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class testsuitesEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function get($productID = 0)
    {
        if(empty($productID)) $productID = $this->param('product', 0);
        if(empty($productID)) return $this->sendError(400, 'Need product id.');

        $control = $this->loadController('testsuite', 'browse');
        $control->browse($productID, 'all', $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success')
        {
            $suites = $data->data->suites;
            $pager  = $data->data->pager;
            $result = array();
            foreach($suites as $suite)
            {
                $result[] = $this->format($suite, 'addedBy:user,addedDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool');
            }

            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'testsuites' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function post($productID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$productID and isset($this->requestBody->product)) $productID = $this->requestBody->product;
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $fields = 'name,type';
        $this->batchSetPost($fields);
        $this->setPost('product', $productID);
        $this->setPost('desc', $this->request('desc', ''));
        $this->setPost('type', $this->request('type', 'private'));

        $control = $this->loadController('testsuite', 'create');
        $this->requireFields('name');

        $control->create($productID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $suite = $this->loadModel('testsuite')->getByID($data->id);

        return $this->send(200, $this->format($suite, 'addedBy:user,addedDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool'));
    }
}
