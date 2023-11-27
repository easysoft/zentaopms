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

$showVersion = getenv('ALLOW_SELECT_VERSION') && (strtolower(getenv('ALLOW_SELECT_VERSION')) == 'true' || strtolower(getenv('ALLOW_SELECT_VERSION')) == '1');
$dbTypeItems = array();
foreach($lang->instance->dbTypes as $type => $db) $dbTypeItems[] = array('text' => $db, 'value' => $type);

$colWidth = isInModal() ? 'full' : '2/3';

jsVar('gitlabUrlTips', $lang->gitlab->placeholder->url);
jsVar('gitlabTokenTips', $lang->gitlab->placeholder->token);
jsVar('sonarqubeUrlTips', $lang->sonarqube->placeholder->url);
jsVar('jenkinsTokenTips', $lang->jenkins->tokenFirst);
jsVar('jenkinsPasswordTips', $lang->jenkins->tips);
jsVar('sonarqubeAccountTips', $lang->sonarqube->placeholder->account);
jsVar('apps', $apps);
jsVar('appID', $appID);
jsVar('defaultApp', $defaultApp);
jsVar('externalApps', $config->space->zentaoApps);
if($config->inQuickon)
{
    jsVar('pgList', $pgList);
    jsVar('mysqlList', $mysqlList);
    jsVar('showVersion', $showVersion);
    jsVar('resourceAlert', $lang->instance->notices['notEnoughResource']);
}

if($config->inQuickon)
{
    formPanel
    (
        setClass('storePanel'),
        set::id('createStoreAppForm'),
        set::title($lang->space->install),
        $appID ? set::submitBtnText($lang->instance->install) : null,
        $appID ? set::actions(array('submit', array('text' => $lang->instance->stop, 'data-type' => 'submit', 'data-dismiss' => 'modal'))) : null,
        set::actionsClass('w-2/3'),
        formRow
        (
            setStyle('display', $appID ? 'none' : 'block'),
            formGroup
            (
                set::width($colWidth),
                set::label($lang->app->common),
                set::name('storeAppType'),
                set::items($apps),
                set::value($appID ? $appID : $defaultApp),
                set::disabled(!!$appID),
                set::required(true),
                on::change('onChangeStoreAppType')
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
                on::change('onChangeType')
            )
        ),
        formRow
        (
            setStyle('display', 'block'),
            formGroup
            (
                set::width($colWidth),
                set::label($lang->instance->name),
                set::name('customName'),
                set::control('input'),
                set::value($appID ? $apps[$appID] : ''),
                set::required(true)
            )
        ),
        $showVersion ? formGroup
        (
            set::width($colWidth),
            set::label($lang->instance->version),
            set::name('version'),
            set::required(true),
            set::control('picker'),
            set::items($versionList)
        ) : formGroup
        (
            set::width($colWidth),
            set::label($lang->instance->version),
            set::name('app_version'),
            set::required(true),
            set::readonly(true),
            input
            (
                set::type('hidden'),
                set::name('version')
            )
        ),
        formRow
        (
            formGroup
            (
                set::width($colWidth),
                set::label($lang->instance->domain),
                set::required(true),
                inputGroup
                (
                    input
                    (
                        setClass('form-control'),
                        set::name('customDomain'),
                        set::value($thirdDomain)
                    ),
                    $this->cne->sysDomain()
                )
            )
        ),
        formRow
        (
            setClass('dbType' . ($showDb ? '' : ' hidden')),
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
            )
        ),
        formRow
        (
            setClass('dbType dbService' . ($showDb ? '' : ' hidden')),
            formGroup
            (
                set::width($colWidth),
                set::label($lang->space->instanceType),
                set::name('dbService'),
                set::items(array()),
                set::required(true)
            )
        )
    );
}

formPanel
(
    $config->inQuickon ? setClass('externalPanel hidden') : setClass('externalPanel'),
    set::id('createAppForm'),
    set::title($lang->space->install),
    set::url($this->createLink('gitlab', 'create')),
    set::actionsClass('w-2/3'),
    formGroup
    (
        set::width($colWidth),
        set::label($lang->app->common),
        set::name('appType'),
        set::items($lang->space->appType),
        set::required(true),
        on::change('onChangeAppType')
    ),
    $config->inQuickon ? formGroup
    (
        set::label($lang->space->addType),
        set::name('type'),
        set::value('external'),
        set::control('radioListInline'),
        set::items(array(array('text' => $lang->store->common, 'value' => 'store'), array('text' => $lang->space->handConfig, 'value' => 'external'))),
        set::required(true),
        on::change('onChangeType')
    ) : null,
    formGroup
    (
        set::width($colWidth),
        set::label($lang->gitlab->name),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        set::width($colWidth),
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
            set::width($colWidth),
            set::label($lang->user->account),
            set::name('account'),
            set::required(true)
        )
    ),
    formRow
    (
        setClass('token'),
        formGroup
        (
            set::width($colWidth),
            set::label($lang->gitlab->token),
            set::name('token'),
            set::placeholder($lang->gitlab->placeholder->token),
            set::required(true)
        )
    ),
    formRow
    (
        setClass('jenkins sonarqube password hidden'),
        formGroup
        (
            set::width($colWidth),
            set::label($lang->user->password),
            set::name('password')
        )
    )
);

render();
