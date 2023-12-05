<?php
/**
 * The files entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class filesEntry extends entry
{
    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $uid = $this->param('uid', '');

        $control = $this->loadController('file', 'ajaxUpload');
        $control->ajaxUpload($uid);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        return $this->send(200, array('id' => $data->id, 'url' => $data->url));
    }
}
