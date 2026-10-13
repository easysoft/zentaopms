<?php
declare(strict_types=1);
/**
 * The tao file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id: model.php 5118 2021-10-22 10:18:41Z $
 * @link        https://www.zentao.net
 */
class webhookTao extends webhookModel
{
    /**
     * 获取钉钉的密钥。
     * Get the secret of dingding.
     *
     * @param  object       $webhook
     * @access public
     * @return object|false
     */
    protected function getDingdingSecret(object $webhook): object|false
    {
        $webhook->secret = array();
        $webhook->secret['agentId']   = $webhook->agentId;
        $webhook->secret['appKey']    = $webhook->appKey;
        $webhook->secret['appSecret'] = $webhook->appSecret;

        if(empty($webhook->agentId))   dao::$errors['agentId']   = sprintf($this->lang->error->notempty, $this->lang->webhook->dingAgentId);
        if(empty($webhook->appKey))    dao::$errors['appKey']    = sprintf($this->lang->error->notempty, $this->lang->webhook->dingAppKey);
        if(empty($webhook->appSecret)) dao::$errors['appSecret'] = sprintf($this->lang->error->notempty, $this->lang->webhook->dingAppSecret);
        if(dao::isError()) return false;

        $webhook->secret = json_encode($webhook->secret);
        $webhook->url    = $this->config->webhook->dingapiUrl;

        return $webhook;
    }

    /**
     * 获取微信的密钥。
     * Get the secret of wechat.
     *
     * @param  object       $webhook
     * @access public
     * @return object|false
     */
    protected function getWeixinSecret(object $webhook): object|false
    {
        $webhook->secret = array();
        $webhook->secret['agentId']   = $webhook->wechatAgentId;
        $webhook->secret['appKey']    = $webhook->wechatCorpId;
        $webhook->secret['appSecret'] = $webhook->wechatCorpSecret;

        if(empty($webhook->wechatCorpId))     dao::$errors['wechatCorpId']     = sprintf($this->lang->error->notempty, $this->lang->webhook->wechatCorpId);
        if(empty($webhook->wechatCorpSecret)) dao::$errors['wechatCorpSecret'] = sprintf($this->lang->error->notempty, $this->lang->webhook->wechatCorpSecret);
        if(empty($webhook->wechatAgentId))    dao::$errors['wechatAgentId']    = sprintf($this->lang->error->notempty, $this->lang->webhook->wechatAgentId);
        if(dao::isError()) return false;

        $webhook->secret = json_encode($webhook->secret);
        $webhook->url    = $this->config->webhook->wechatApiUrl;

        return $webhook;
    }

    /**
     * 获取飞书的密钥。
     * Get the secret of feishu.
     *
     * @param  object       $webhook
     * @access public
     * @return object|false
     */
    protected function getFeishuSecret(object $webhook): object|false
    {
        $webhook->secret = array();
        $webhook->secret['appId']     = $webhook->feishuAppId;
        $webhook->secret['appSecret'] = $webhook->feishuAppSecret;

        if(empty($webhook->feishuAppId))     dao::$errors['feishuAppId']     = sprintf($this->lang->error->notempty, $this->lang->webhook->feishuAppId);
        if(empty($webhook->feishuAppSecret)) dao::$errors['feishuAppSecret'] = sprintf($this->lang->error->notempty, $this->lang->webhook->feishuAppSecret);
        if(dao::isError()) return false;

        $webhook->secret = json_encode($webhook->secret);
        $webhook->url    = $this->config->webhook->feishuApiUrl;

        return $webhook;
    }

    /**
     * 获取动作的文本。
     * Get the text of action.
     *
     * @param  object $data
     * @param  object $action
     * @param  string $title
     * @param  array  $users
     * @access public
     * @return string
     */
    protected function getActionText(object $data, object $action, string $title, array $users): string
    {
        $text = zget($users, $data->user, $this->app->user->realname) . $this->lang->action->label->{$action->action} . $this->lang->action->objectTypes[$action->objectType] . "[#{$action->objectID}::{$title}]";

        $httpReg = '/\(http(s)?:\/\/[^\)]+\)/';
        if(isset($data->markdown->text))
        {
            $text = preg_replace($httpReg, '', $data->markdown->text);
        }
        elseif(isset($data->markdown->content))
        {
            $text = preg_replace($httpReg, '', $data->markdown->content);
        }
        elseif(isset($data->text->content))
        {
            $text = preg_replace($httpReg, '', $data->text->content);
        }
        elseif(isset($data->card->elements))
        {
            $content = $data->card->elements[0]->content;
            if(strpos($content, '(http') !== false) $text = preg_replace($httpReg, '', $content);
        }
        elseif(isset($data->content))
        {
            $content = empty($data->content->text) ? '' : (string)$data->content->text;
            if(strpos($content, '(http') !== false) $text = preg_replace($httpReg, '', $content);
        }
        elseif(strpos($data->text, '(http') !== false)
        {
            $text = preg_replace($httpReg, '', $data->text);
        }

        $text = preg_replace('/\[([^\]]+)\]/', '$1', $text);
        return $text;
    }
}
