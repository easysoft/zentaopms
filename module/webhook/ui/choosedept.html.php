<?php
declare(strict_types=1);
/**
 * The chooseDept view file of webhook module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     webhook
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('deptTree', $deptTree);
jsVar('webhookType', $webhookType);
jsVar('webhookID', $webhookID);
jsVar('requestError', $lang->webhook->error->requestError);
jsVar('feishuUrl', $this->createLink('webhook', 'ajaxGetFeishuDeptList', array('webhookID' => $webhookID)));

panel
(
    setClass('m-auto'),
    set::style(array('width' => '900px')),
    set::title($lang->webhook->chooseDept),
    on::click('.save', 'submitSelectedDepts'),
    $webhookType == 'feishuuser' ? div
    (
        setID('notice'),
        setClass('alert secondary-pale'),
        icon('exclamation-sign'),
        $lang->webhook->friendlyTips
    ) : null,
    div(setID('loadPrompt'), span(setClass('text-gray'), $lang->webhook->loadPrompt)),
    h::ul(setID('deptList')),
    div
    (
        setClass('actions mt-3'),
        btn(setClass('primary save'), $lang->save),
        btn(set::url(createLink('webhook', 'browse')), $lang->goback, setClass('ml-2'))
    )
);

render();

