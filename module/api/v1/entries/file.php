<?php
/**
 * The file entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class fileEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get($fileID)
    {
        $control = $this->loadController('file', 'download');
        $control->download($fileID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        return $this->send(200, $data);
    }
    /**
     * PUT method.
     *
     * @access public
     * @return string
     */
    public function put($fileID)
    {
        $uid = $this->param('uid', '');
        $action = $this->param('action', '');
        if($action == 'remove') unset($_SESSION['album']['used'][$uid][$fileID]);

        return $this->send(200, array('id' => $fileID));
    }
}
