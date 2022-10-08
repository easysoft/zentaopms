<?php
/**
 * The build entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class buildEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function get($buildID)
    {
        $control = $this->loadController('build', 'view');
        $control->view($buildID);

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success') return $this->send(200, $this->format($data->data->build, 'builder:user,stories:idList,bugs:idList,deleted:bool'));

        /* Exception handling. */
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $this->sendError(400, 'error');
    }

    /**
     * PUT method.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function put($buildID)
    {
        $oldBuild = $this->loadModel('build')->getByID($buildID);

        /* Set $_POST variables. */
        $fields = 'execution,product,name,builder,date,scmPath,filePath,desc';
        $this->batchSetPost($fields, $oldBuild);

        $control = $this->loadController('build', 'edit');
        $control->edit($buildID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $build = $this->build->getByID($buildID);
        $this->send(200, $this->format($build, 'builder:user,stories:idList,bugs:idList,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function delete($buildID)
    {
        $control = $this->loadController('build', 'delete');
        $control->delete($buildID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
