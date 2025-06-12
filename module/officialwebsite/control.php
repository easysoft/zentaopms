<?php
/**
 * The control file of officialwebsite currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jialiang Zhang <zhangjialiang@chandao.com>
 * @package     officialwebsite
 * @link        https://www.zentao.net
 */

class officialwebsite extends control
{
    /**
     *  加入社区。
     *  Join community.
     *
     * @access public
     * @return int|void|null
     */
    public function index()
    {
        if(!empty($_POST))
        {
            $apiRoot = 'https://zentao.xsj.oop.cc';
            $apiURL  = $apiRoot . "/user-mobileLogin.json";
            $response = common::http($apiURL, $_POST);
            $response = json_decode($response, true);
            if($response['result'] == 'fail')
            {
                return $this->send(array('result' => 'fail', 'message' => $response['message']));
            }
            return $this->send(array('result' => 'success', 'load' => $this->createLink('index', 'index')));
        }
        $this->display();
    }

    /**
     *  已绑定页面
     *  Already bound page。
     *
     * @access public
     * @return void|null
     */
    public function community()
    {
        $bindCommunity = $this->config->global->bindCommunity;

        /* 未绑定跳转到绑定页面。*/
        /* Unbound jump to bound page. */
        if(!$bindCommunity) return $this->locate($this->createLink('officialwebsite', 'index'));

        $agreeUX             = $this->config->global->agreeUX;
        $bindCommunityMobile = $this->config->global->bindCommunityMobile;

        $this->view->agreeUX             = $agreeUX;
        $this->view->bindCommunityMobile = $bindCommunityMobile;
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