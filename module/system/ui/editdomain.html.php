<?php
declare(strict_types=1);
/**
 * The editdomain view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->system->domain->config),
    setID('domainForm'),
    formRow
    (
        formGroup
        (
            set::label($lang->system->domain->oldDomain),
            set::control('static'),
            set::name(''),
            set::value($this->loadModel('cne')->sysDomain()),
            h::span
            (
                setClass('tips-git leading-8 ml-2 text-danger'),
                $lang->system->domain->notReuseOldDomain
            )
        )
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->system->domain->newDomain),
        set::name('customDomain'),
        set::value(zget($domainSettings, 'customDomain', '')),
        set::required(true),
        h::span
        (
            setClass('tips-git leading-8 ml-2'),
            $lang->system->domain->setDNS
        ),
        h::a
        (
            setClass('leading-8'),
            set::target('_blank'),
            set::href('https://www.qucheng.com/book/Installation-manual/47.html'),
            $lang->system->domain->dnsHelperLink
        )
    ),
    formGroup
    (
        set::label(''),
        set::name('https[]'),
        set::control(array('type' => 'checkbox', 'text' => $lang->system->domain->uploadCert, 'value' => 'true')),
        on::change('onHttpsChange')
    ),
    formGroup
    (
        set::width('2/3'),
        setClass('cert hidden'),
        set::label($lang->system->certPem),
        set::name('certPem'),
        set::control('textarea'),
        set::value(zget($domainSettings, 'certPem', ''))
    ),
    formGroup
    (
        set::width('2/3'),
        setClass('cert hidden'),
        set::label($lang->system->certKey),
        set::name('certKey'),
        set::control('textarea'),
        set::value(zget($domainSettings, 'certKey', ''))
    ),
    formRow
    (
        formGroup
        (
            setClass('cert hidden'),
            set::label(''),
            btn
            (
                $lang->system->verify,
                setID('validateCertBtn'),
                on::click('checkCert')
            ),
            h::span
            (
                setClass('tips-git leading-8 ml-2'),
                setID('validateMsg')
            )
        )
    )
);
render();

