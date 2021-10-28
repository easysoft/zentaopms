<?php
/**
 * The files entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class filesEntry extends Entry
{
    /**
     * POST method.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        $uid = $this->param('uid', '');

        $control = $this->loadController('file', 'ajaxUpload');
        $control->ajaxUpload($uid);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'error')
        {
            return isset($data->code) and $data->code == 404 ? $this->send404() : $this->sendError(400, $data->message);
        }

        $this->send(200, array('id' => $data->id));
    }
}
