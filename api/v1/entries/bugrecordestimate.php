<?php
/**
 * The bug recordWorkhour entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class bugRecordEstimateEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function get($bugID)
    {
        if($this->config->edition == 'open') return $this->send400('ZenTaoPMS does not have bug effort function.');

        $control = $this->loadController('effort', 'createForObject');
        $control->createForObject('bug', $bugID);

        $data = $this->getData();
        if(!$data) return $this->error('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $effort = $data->data->efforts;

        return $this->send(200, array('effort' => $effort));
    }

    /**
     * POST method.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function post($bugID)
    {
        if($this->config->edition == 'open') return $this->send400('ZenTaoPMS does not have bug effort function.');

        $control = $this->loadController('effort', 'createForObject');

        $fields = 'id,dates,consumed,objectType,objectID,work';
        $this->batchSetPost($fields);
        $control->createForObject('bug', $bugID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $bug = $this->loadModel('bug')->getByID($bugID);

        return $this->send(200, $this->format($bug, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }
}
