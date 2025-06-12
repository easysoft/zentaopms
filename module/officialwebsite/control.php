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
     *  绑定社区账号。
     *  Bind community account.
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

            $_POST['sn'] = $this->config->global->sn;

            $response = common::http($apiURL, $_POST);
            $response = json_decode($response, true);

//            if($response['result'] == 'fail')
//            {
//                return $this->send(array('result' => 'fail', 'message' => $response['message']));
//            }

            if(!isset($this->config->global)) $this->config->global = new stdclass();

            $this->loadModel('setting')->setItem('system.common.global.bindCommunity', true);
            $this->loadModel('setting')->setItem('system.common.global.bindCommunityMobile', $this->post->mobile);

            $agreeUX = $this->post->agreeUX;
            if($agreeUX)
            {
                $this->loadModel('setting')->setItem('system.common.global.agreeUX', true);
            }

            return $this->send(array('result' => 'success', 'load' => $this->createLink('officialwebsite', 'community')));
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

    /**
     *  解绑社区账号
     *  Unbind community account。
     *
     * @return int|null
     */
    public function unBindCommunity()
    {
        $this->loadModel('setting')->setItem('system.common.global.bindCommunity', false);
        $this->loadModel('setting')->setItem('system.common.global.bindCommunityMobile', '');
        $this->config->global->agreeUX = false;
        $this->config->global->bindCommunityMobile = '';
        return $this->send(array('result' => 'success', 'load' => inlink('index')));
    }

    /**
     *  取消同意改进计划
     *  Cancel the agreement to improve the plan。
     *
     * @return int|null
     */
    public function cancelAgreeUX()
    {
        $this->loadModel('setting')->setItem('system.common.global.agreeUX', false);
        $this->config->global->agreeUX = false;
        return $this->send(array('result' => 'success', 'load' => inlink('community')));
    }

    /**
     *  获取图形验证码
     *  Obtain graphical captcha。
     *
     * @return int|null
     */
    public function getCaptcha()
    {
        $apiRoot = 'https://zentao.xsj.oop.cc';
        $apiURL  = $apiRoot . "/guarder-getCaptcha.json";
        $response = common::http($apiURL);
        $response = json_decode($response, true);
        return $this->send($response);
    }

    /**
     *  发动短信验证码
     *  Activate SMS verification code
     *
     * @return int|null
     */
    public function sendcode()
    {
        $apiRoot = 'https://zentao.xsj.oop.cc';
        $apiURL  = $apiRoot . "/sms-sendcode.json";
        $response = common::http($apiURL, $_POST);
        $response = json_decode($response, true);
        return $this->send($response);
    }

    /**
     *  用户体验改进计划详情
     *
     * @return void
     */
    public function planModal()
    {
        $this->display();
    }
}