<?php
/**
 * The productplans entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class releasesEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function get($productID = 0, $projectID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$projectID) $projectID = $this->param('project');
        if(!$productID and !$projectID) return $this->sendError(400, 'Need product or project id.');

        if($projectID)
        {
            $control = $this->loadController('projectrelease', 'browse');
            $control->browse($projectID, 0, $this->param('status', 'all'));
        }
        else
        {
            $control = $this->loadController('release', 'browse');
            $control->browse($productID, $this->param('branch', 0), $this->param('status', 'all'));
        }

        /* Response */
        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success')
        {
            $result   = array();
            $releases = $data->data->releases;
            foreach($releases as $release) $result[] = $this->format($release, 'deleted:bool,date:date');

            return $this->send(200, array('releases' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }
}
