<?php
declare(strict_types=1);
/**
 * The detail view file of account module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     account
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

if(isAjaxRequest('modal')) unset($config->account->actionList['edit']['data-toggle']);
$actions = $this->loadModel('common')->buildOperateMenu($account);

detailHeader
(
    isAjaxRequest('modal') ? to::prefix() : '',
    to::title(
        entityLabel(
            set(array('entityID' => $account->id, 'level' => 1, 'text' => $account->name))
        )
    ),
);

detailBody
(
    sectionList
    (
        section
        (
            tableData
            (
                item
                (
                    set::name($lang->account->name),
                    $account->name
                ),
                item
                (
                    set::name($lang->account->provider),
                    \zget($lang->serverroom->providerList, $account->provider)
                ),
                item
                (
                    set::name($lang->account->adminURI),
                    $account->adminURI
                ),
                item
                (
                    set::name($lang->account->account),
                    $account->account
                ),
                item
                (
                    set::name($lang->account->password),
                    $account->password
                ),
                item
                (
                    set::name($lang->account->email),
                    $account->email
                ),
                item
                (
                    set::name($lang->account->mobile),
                    $account->mobile
                ),
            )
        ),
    ),
    history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'account', 'objectID' => $account->id))),
    ),
    floatToolbar
    (
        set::object($account),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::className('ghost text-white'), $lang->goback)),
        set::suffix($actions['suffixActions'])
    ),
);

render();
