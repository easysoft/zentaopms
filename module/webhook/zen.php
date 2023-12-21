<?php
declare(strict_types=1);
/**
 * The zen file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easysoft.ltd>
 * @package     webhook
 * @link        https://www.zentao.net
 */
class webhookZen extends webhook
{
    /**
     * 通过API获取人员列表。
     * Get users by API.
     *
     * @param  object $webhook
     * @access public
     * @return array
     */
    protected function getResponse(object $webhook): array
    {
        $response = array();
        $selectedDepts = $this->cookie->selectedDepts ? $this->cookie->selectedDepts : '';
        if($webhook->type == 'dinguser')
        {
            $this->app->loadClass('dingapi', true);
            $dingapi  = new dingapi($webhook->secret->appKey, $webhook->secret->appSecret, $webhook->secret->agentId);
            $response = $dingapi->getUsers($selectedDepts);
        }
        elseif($webhook->type == 'wechatuser')
        {
            $this->app->loadClass('wechatapi', true);
            $wechatApi = new wechatapi($webhook->secret->appKey, $webhook->secret->appSecret, $webhook->secret->agentId);
            $response  = $wechatApi->getAllUsers();
        }
        elseif($webhook->type == 'feishuuser')
        {
            $this->app->loadClass('feishuapi', true);
            $feishuApi = new feishuapi($webhook->secret->appId, $webhook->secret->appSecret);
            $response  = $feishuApi->getAllUsers($selectedDepts);
        }

        $this->view->selectedDepts = $selectedDepts;
        return $response;
    }
}
