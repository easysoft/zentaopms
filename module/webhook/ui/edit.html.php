<?php
declare(strict_types=1);
/**
 * The create view file of webhook module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     webhook
 * @link        https://www.zentao.net
 */
namespace zin;

$fields       = $config->webhook->form->create;
$defaultWidth = '1/2';
$formItems    = array();
$secret       = json_decode($webhook->secret);
foreach($fields as $field => $attr)
{
    if($field == 'type') continue;
    if($field == 'secret' && $webhook->type != 'dinggroup' && $webhook->type != 'feishugroup') continue;
    if($field == 'params' && !str_contains('|bearychat|dinggroup|dinguser|wechatgroup|wechatuser|feishuuser|feishugroup|', "|{$webhook->type}|")) continue;
    if(($field == 'agentId' || $field == 'appKey' || $field == 'appSecret') && $webhook->type != 'dinguser') continue;
    if(($field == 'wechatCorpId' || $field == 'wechatCorpSecret' || $field == 'wechatAgentId') && $webhook->type != 'wechatuser') continue;
    if(($field == 'feishuAppId' || $field == 'feishuAppSecret') && $webhook->type != 'feishuuser') continue;

    $width     = zget($attr, 'width', $defaultWidth);
    $fieldName = $field;
    $notice    = '';
    $required  = zget($attr, 'required', false);
    $rowID     = '';
    $rowClass  = '';
    $title     = $field;
    $default   = zget($webhook, $field, '');
    $control   = array();

    if($field == 'products')   $attr['options'] = $products;
    if($field == 'executions') $attr['options'] = $executions;

    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items']  = $attr['options'];
    if(!empty($attr['inline']))  $control['inline'] = $attr['inline'];
    if($attr['control'] == 'checkList') $fieldName = $field . '[]';
    if(!empty($attr['multiple']))
    {
        $control['multiple'] = true;
        $fieldName = $field . '[]';
    }

    if($webhook->type == 'dinguser')
    {
        if($field == 'agentId')   $default = $secret->agentId;
        if($field == 'appKey')    $default = $secret->appKey;
        if($field == 'appSecret') $default = $secret->appSecret;
    }
    if($webhook->type == 'wechatuser')
    {
        if($field == 'wechatCorpId')     $default = $secret->appKey;
        if($field == 'wechatCorpSecret') $default = $secret->appSecret;
        if($field == 'wechatAgentId')    $default = $secret->agentId;
    }
    if($webhook->type == 'feishuuser')
    {
        if($field == 'feishuAppId')     $default = $secret->appId;
        if($field == 'feishuAppSecret') $default = $secret->appSecret;
    }

    if(isset($lang->webhook->{$field})) $title = $lang->webhook->{$field};
    if($field == 'appKey')     $title = $lang->webhook->dingAppKey;
    if($field == 'appSecret')  $title = $lang->webhook->dingAppSecret;
    if($field == 'agentId')    $title = $lang->webhook->dingAgentId;
    if($field == 'products')   $title = $lang->webhook->product;
    if($field == 'executions') $title = $lang->webhook->execution;

    if($field == 'secret')   $rowID = 'secretTR';
    if($field == 'url')      $rowID = 'urlTR';
    if($field == 'sendType') $rowID = 'sendTypeTR';

    if($field == 'wechatCorpId') $notice = $lang->webhook->note->wechatHelp;
    if($field == 'url')          $notice = zget($lang->webhook->note->typeList, $webhook->type, '');
    if($field == 'agentId')      $notice = $lang->webhook->note->dingHelp;
    if($field == 'sendType')     $notice = $lang->webhook->note->async;
    if($field == 'products')     $notice = $lang->webhook->note->product;
    if($field == 'executions')   $notice = $lang->webhook->note->execution;

    if($field == 'agentId' || $field == 'appKey' || $field == 'appSecret') $rowClass = 'dinguserTR';
    if($field == 'feishuAppId' || $field == 'feishuAppSecret')             $rowClass = 'feishuTR';
    if($field == 'wechatCorpId' || $field == 'wechatCorpSecret' || $field == 'wechatAgentId')   $rowClass = 'wechatTR';
    if($field == 'url' && str_contains("|dinguser|wechatuser|feishuuser|", "|{$webhook->type}|")) $rowClass = 'hidden';

    if($field == 'agentId' || $field == 'appKey' || $field == 'appSecret') $required = true;
    if($field == 'feishuAppId' || $field == 'feishuAppSecret')             $required = true;
    if($field == 'wechatCorpId' || $field == 'wechatCorpSecret' || $field == 'wechatAgentId') $required = true;

    $formItems[] = formRow
    (
        $rowID ? setID($rowID) : null,
        $rowClass ? setClass($rowClass) : null,
        formGroup
        (
            set::width($width),
            set::name($fieldName),
            set::label($title),
            set::control($control),
            set::value($default),
            set::required($required)
        ),
        $notice ? formGroup
        (
            $field == 'url' ? setID('urlNote') : null,
            html($notice)
        ) : null
    );
}

jsVar('urlNote', $lang->webhook->note->typeList);

formPanel
(
    formGroup
    (
        setClass('webhookType'),
        set::label($lang->webhook->type),
        formHidden('type', $webhook->type),
        zget($lang->webhook->typeList, $webhook->type)
    ),
    $formItems
);

render();

