<?php
declare(strict_types=1);
/**
 * The downloadclient view file of misc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     misc
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->downloadClient));

formPanel
(
    set::id('downloadClient'),
    set::actions(false),
    $action == 'check' ? div(set::className('center p-4'), div($errorInfo), div(a(set::className('btn primary btn-wide'), set::href($this->createLink('misc', 'downloadClient', "action=check")), $lang->refresh))) : null,
    $action == 'selectPackage' ? formGroup
    (
        set::label($lang->misc->client->version),
        input(set::value($config->xuanxuan->version), set::disabled(true))
    ) : null,
    $action == 'selectPackage' ? formGroup
    (
        set::label($lang->misc->client->os),
        picker
        (
            set::id('os'),
            set::name('os'),
            set::items($lang->misc->client->osList),
            set::value($os)
        )
    ) : null,
    $action == 'selectPackage' ? div(set::className('center'), btn(set::className('primary btn-wide'), set('data-on', 'click'), set('data-call', 'clickSubmit'), $lang->save)) : null,
    $action == 'getPackage' ? div
    (
        input(set::className('hidden'), set::name('os'), set::value($os)),
        ul
        (
            li(set::id('downloading'),   html("{$lang->misc->client->downloading}<span>0</span>M")),
            li(set::id('downloaded'),    set::className('hidden'), $lang->misc->client->downloaded),
            li(set::id('setting'),       set::className('hidden'), $lang->misc->client->setting),
            li(set::id('setted'),        set::className('hidden'), $lang->misc->client->setted),
            li(set::id('configError'),   set::className('hidden'), $lang->misc->client->errorInfo->configError),
            li(set::id('downloadError'), set::className('hidden'), $lang->misc->client->errorInfo->downloadError)
        ),
        div(set::id('hasError')),
        div(set::id('clearTmp'))
    ) : null,
    input(set::className('hidden'), set::name('action'), set::value($action))
);

render('modalDialog');
