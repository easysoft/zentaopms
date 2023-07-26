<?php
declare(strict_types=1);
/**
 * The createapplication view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('gitlabUrlTips', $lang->gitlab->placeholder->url);
jsVar('gitlabTokenTips', $lang->gitlab->placeholder->token);
jsVar('sonarqubeUrlTips', $lang->sonarqube->placeholder->url);
jsVar('sonarqubeAccountTips', $lang->sonarqube->placeholder->account);
jsVar('jenkinsTokenTips', $lang->jenkins->tokenFirst);
jsVar('jenkinsPasswordTips', $lang->jenkins->tips);

formPanel
(
    set::id('createAppForm'),
    set::title($lang->space->install),
    set::url($this->createLink('gitlab', 'create')),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->app->common),
        set::name('appType'),
        set::items($lang->space->appType),
        set::required(true),
        on::change('onChangeAppType'),
    ),
    formGroup
    (
        set::label($lang->space->addType),
        set::name('type'),
        set::value('external'),
        set::control('radioListInline'),
        set::items(array(array('text' => $lang->store->common, 'value' => 'store'), array('text' => $lang->space->handConfig, 'value' => 'external'))),
        set::required(true),
        on::change('onChangeType'),
    ),
    formGroup
    (
        set::label($lang->gitlab->name),
        set::name('name'),
        set::required(true),
    ),
    formGroup
    (
        set::label($lang->gitlab->url),
        set::name('url'),
        set::required(true),
        set::placeholder($lang->gitlab->placeholder->url)
    ),
    formRow
    (
        setClass('jenkins sonarqube hidden'),
        formGroup
        (
            set::label($lang->user->account),
            set::name('account'),
            set::required(true),
        ),
    ),
    formRow
    (
        setClass('token'),
        formGroup
        (
            set::label($lang->gitlab->token),
            set::name('token'),
            set::placeholder($lang->gitlab->placeholder->token),
            set::required(true),
        ),
    ),
    formRow
    (
        setClass('jenkins sonarqube password hidden'),
        formGroup
        (
            set::label($lang->user->password),
            set::name('password'),
        ),
    ),
);

render();

