<?php
/**
 * The release entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class releaseEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function get($releaseID)
    {
        $control = $this->loadController('release', 'view');
        $control->view($releaseID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $release = $this->format($data->data->release, 'date:date,deleted:bool');

        return $this->send(200, $release);
    }

    /**
     * PUT method.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function put($releaseID)
    {
        $oldRelease = $this->loadModel('release')->getByID($releaseID);

        /* Set $_POST variables. */
        $fields = 'name,build,status,desc';
        $this->batchSetPost($fields, $oldRelease);

        $control = $this->loadController('release', 'edit');
        $control->edit($releaseID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $release = $this->release->getByID($releaseID);
        $this->sendSuccess(200, $this->format($release, 'date:date,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function delete($releaseID)
    {
        $control = $this->loadController('release', 'delete');
        $control->delete($releaseID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
