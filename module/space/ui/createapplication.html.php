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
jsVar('apps', $apps);
jsVar('mysqlList', $mysqlList);
jsVar('pgList', $pgList);

$showVersion = getenv('ALLOW_SELECT_VERSION') && (strtolower(getenv('ALLOW_SELECT_VERSION')) == 'true' || strtolower(getenv('ALLOW_SELECT_VERSION')) == '1');
$dbTypeItems = array();
foreach($lang->instance->dbTypes as $type => $db) $dbTypeItems[] = array('text' => $db, 'value' => $type);

formPanel
(
    setClass('externalPanel' . (!$appID ? '' : ' hidden')),
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
        set::width('1/2'),
        set::label($lang->gitlab->name),
        set::name('name'),
        set::required(true),
    ),
    formGroup
    (
        set::width('2/3'),
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
            set::width('1/2'),
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
            set::width('1/2'),
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
            set::width('1/2'),
            set::label($lang->user->password),
            set::name('password'),
        ),
    ),
);

formPanel
(
    setClass('storePanel' . ($appID ? '' : ' hidden')),
    set::id('createStoreAppForm'),
    set::title($lang->space->install),
    formRow
    (
        setStyle('display', $appID ? 'block' : 'none'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->instance->name),
            set::name('customName'),
            set::control('input'),
            set::value($appID ? $apps[$appID] : ''),
            set::required(true),
        )
    ),
    formRow
    (
        setStyle('display', $appID ? 'none' : 'block'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->app->common),
            set::name('storeAppType'),
            set::items($apps),
            set::value($appID),
            set::disabled(!!$appID),
            set::required(true),
            on::change('onChangeStoreAppType'),
        )
    ),
    formRow
    (
        setStyle('display', $appID ? 'none' : 'block'),
        formGroup
        (
            set::label($lang->space->addType),
            set::name('type'),
            set::value('store'),
            set::disabled(!!$appID),
            set::control('radioListInline'),
            set::items(array(array('text' => $lang->store->common, 'value' => 'store'), array('text' => $lang->space->handConfig, 'value' => 'external'))),
            set::required(true),
            on::change('onChangeType'),
        )
    ),
    $showVersion ? formGroup
    (
        set::width('1/2'),
        set::label($lang->instance->version),
        set::name('version'),
        set::required(true),
        set::control('picker'),
        set::items($versionList)
    ) : formGroup
    (
        set::width('1/2'),
        set::label($lang->instance->version),
        set::name('app_version'),
        set::required(true),
        set::readonly(true),
        input
        (
            set::type('hidden'),
            set::name('version'),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->instance->domain),
            set::required(true),
            inputGroup
            (
                input
                (
                    setClass('form-control'),
                    set::name('customDomain'),
                    set::value($thirdDomain),
                ),
                $this->cne->sysDomain(),
            ),
        ),
    ),
    formRow
    (
        setClass('dbType'),
        formGroup
        (
            set::label($lang->instance->dbType),
            set::control('radioListInline'),
            set::name('dbType'),
            set::items($dbTypeItems),
            set::value('sharedDB'),
            set::required(true),
            on::change('onChangeDbType'),
            h::a
            (
                setClass('leading-8 ml-4'),
                set::href('https://www.qucheng.com/book/Installation-manual/app-install-33.html'),
                set::target('_blank'),
                $lang->instance->howToSelectDB
            )
        ),

    ),
    formRow
    (
        setClass('dbType dbService'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->space->instanceType),
            set::name('dbService'),
            set::items(array()),
            set::required(true),
        ),
    ),
);

render();

