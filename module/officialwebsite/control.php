<?php
/**
 * The control file of officialwebsite currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     officialwebsite
 * @version     $Id: control.php 4297 2013-01-27 07:51:45Z wwccss $
 * @link        https://www.zentao.net
 */

class officialwebsite extends control
{
    /**
     * 加入社区。
     * Join community.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->display();
    }

    public function getCaptcha()
    {
        $apiRoot = 'https://zentao.xsj.oop.cc';
        $apiURL  = $apiRoot . "/guarder-getCaptcha.json";
        $response = common::http($apiURL);
        $response = json_decode($response, true);
        return $this->send($response);
    }

    public function sendcode()
    {
        $apiRoot = 'https://zentao.xsj.oop.cc';
        $apiURL  = $apiRoot . "/sms-sendcode.json";
        $response = common::http($apiURL, $_POST);
        $response = json_decode($response, true);
        return $this->send($response);
    }

    public function planModal()
    {
        $this->display();
    }
}