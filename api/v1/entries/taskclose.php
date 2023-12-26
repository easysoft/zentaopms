<?php
/**
 * The task close entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class taskCloseEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function post($taskID)
    {
        $task = $this->loadModel('task')->getByID($taskID);

        $fields = 'comment';
        $this->batchSetPost($fields);

        $control = $this->loadController('task', 'close');
        $control->close($taskID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        return $this->send(200, $task);
    }
}
