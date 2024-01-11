<?php
declare(strict_types=1);
/**
 * The xuanxuan view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     setting
 * @link        https://www.zentao.net
 */

namespace zin;

if($type == 'edit')
{
    $https = zget($config->xuanxuan, 'https', 'off');
    formPanel
    (
        set::title($lang->im->settings),
        set::labelWidth('140px'),
        formGroup
        (
            set::label($lang->im->version),
            span
            (
                setClass('mt-1.5 w-1/4'),
                $config->xuanxuan->version
            )
        ),
        formGroup
        (
            set::label($lang->im->turnon),
            radioList
            (
                width('1/4'),
                set::name('turnon'),
                set::inline(true),
                set::items($lang->im->turnonList),
                set::value($turnon)
            )
        ),
        formGroup
        (
            set::label($lang->im->key),
            input
            (
                width('1/4'),
                set::name('key'),
                set::readonly(true),
                set::value(zget($config->xuanxuan, 'key', ''))
            ),
            a
            (
                setClass('ml-4 mt-1.5'),
                on::click('createKey'),
                set::href('javascript:;'),
                $lang->im->createKey
            )
        ),
        formGroup
        (
            set::label($lang->im->backendLang),
            set::required(true),
            picker
            (
                setStyle(array('width' => '25%')),
                set::required(true),
                set::name('backendLang'),
                set::items($lang->setting->langs),
                set::value(zget($config->xuanxuan, 'backendLang', ''))
            )
        ),
        formGroup
        (
            set::label($lang->im->xxdServer),
            set::required(true),
            input
            (
                width('1/4'),
                set::name('server'),
                set::value($domain)
            ),
            span
            (
                setClass('text-gray ml-4 mt-1.5'),
                $lang->im->xxdServerTip
            )
        ),
        formGroup
        (
            set::label($lang->im->pollingInterval),
            set::required(true),
            input
            (
                width('1/4'),
                set::name('pollingInterval'),
                set::value(zget($config->xuanxuan, 'pollingInterval', 60))
            ),
            span
            (
                setClass('text-gray ml-4 mt-1.5'),
                $lang->im->xxdPollIntTip
            )
        ),
        formGroup
        (
            set::label($lang->im->xxd->ip),
            set::required(true),
            input
            (
                width('1/4'),
                set::name('ip'),
                set::value(zget($config->xuanxuan, 'ip', '0.0.0.0'))
            )
        ),
        formGroup
        (
            set::label($lang->im->xxd->chatPort),
            set::required(true),
            input
            (
                width('1/4'),
                set::name('chatPort'),
                set::value(zget($config->xuanxuan, 'chatPort', 11444))
            )
        ),
        formGroup
        (
            set::label($lang->im->xxd->commonPort),
            set::required(true),
            input
            (
                width('1/4'),
                set::name('commonPort'),
                set::value(zget($config->xuanxuan, 'commonPort', 11443))
            )
        ),
        formGroup
        (
            set::label($lang->im->xxd->uploadFileSize),
            set::required(true),
            inputGroup
            (
                setClass('grow-0 w-1/4'),
                $lang->im->xxd->max,
                input
                (
                    set::name('uploadFileSize'),
                    set::value(zget($config->xuanxuan, 'uploadFileSize', 20))
                ),
                'M'
            )
        ),
        formGroup
        (
            set::label($lang->im->xxd->aes),
            radioList
            (
                width('1/4'),
                set::name('aes'),
                set::inline(true),
                set::items($lang->im->aesOptions),
                set::value(zget($config->xuanxuan, 'aes', 'on'))
            ),
            span
            (
                setClass('ml-4 mt-1.5 text-gray'),
                $lang->im->xxdAESTip
            )
        ),
        formGroup
        (
            set::label($lang->im->xxd->https),
            radioList
            (
                width('1/4'),
                set::name('https'),
                set::inline(true),
                set::items($lang->im->httpsOptions),
                set::value($https),
                on::change("$('[name=sslkey],[name=sslcrt]').closest('.form-row').toggleClass('hidden');")
            )
        ),
        formRow
        (
            $https == 'off' ? set::hidden(true) : null,
            formGroup
            (
                set::label($lang->im->xxd->sslkey),
                set::required(true),
                textarea
                (
                    set::name('sslkey'),
                    set::value(zget($config->xuanxuan, 'sslkey', '')),
                    set::placeholder($lang->im->placeholder->xxd->sslkey),
                    set::rows(5)
                )
            )
        ),
        formRow
        (
            $https == 'off' ? set::hidden(true) : null,
            formGroup
            (
                set::label($lang->im->xxd->sslcrt),
                set::required(true),
                textarea
                (
                    set::name('sslcrt'),
                    set::value(zget($config->xuanxuan, 'sslcrt', '')),
                    set::placeholder($lang->im->placeholder->xxd->sslcrt),
                    set::rows(5)
                )
            )
        ),
        formGroup
        (
            set::label($lang->im->debug),
            radioList
            (
                width('1/4'),
                set::name('debug'),
                set::inline(true),
                set::items($lang->im->debugStatus),
                set::value(zget($config->xuanxuan, 'debug', 0))
            )
        )
    );
}
else
{
    $disabled = empty($config->xuanxuan->turnon);
    panel
    (
        set::title($lang->im->settings),
        tableData
        (
            set::tdClass('flex gap-2'),
            item(set::name($lang->im->version), $config->xuanxuan->version),
            item(set::name($lang->im->turnon), zget($lang->im->turnonList, $config->xuanxuan->turnon)),
            item(set::name($lang->im->key), zget($config->xuanxuan, 'key', '')),
            item(set::name($lang->im->backendLang), zget($lang->setting->langs, $config->xuanxuan->backendLang, '')),
            item(set::name($lang->im->xxdServer), $domain),
            item(set::name($lang->im->pollingInterval), zget($config->xuanxuan, 'pollingInterval', 60) . $lang->im->secs),
            item(set::name($lang->im->xxd->ip), zget($config->xuanxuan, 'ip', '0.0.0.0')),
            item(set::name($lang->im->xxd->chatPort), zget($config->xuanxuan, 'chatPort', 11444)),
            item(set::name($lang->im->xxd->commonPort), zget($config->xuanxuan, 'commonPort', 11443)),
            item(set::name($lang->im->xxd->uploadFileSize), $lang->im->xxd->max . zget($config->xuanxuan, 'uploadFileSize', 20) . 'M'),
            item(set::name($lang->im->xxd->aes), zget($lang->im->aesOptions, zget($config->xuanxuan, 'aes', 'on'))),
            item(set::name($lang->im->xxd->https), zget($lang->im->httpsOptions, zget($config->xuanxuan, 'https', 'off'))),
            item(set::name($lang->im->debug), zget($lang->im->debugStatus, zget($config->xuanxuan, 'debug', 0))),
            $disabled ? null : item
            (
                set::name($lang->im->xxd->os),
                select
                (
                    set::name('os'),
                    set::items($lang->im->osList),
                    set::value(zget($config->xuanxuan, $os)),
                    on::change("$('#downloadXXD').attr('href', $.createLink('setting', 'downloadXXD', 'type=package&os=' + \$ele.val()));")
                )
            ),
            item
            (
                hasPriv('im', 'downloadXxdPackage') ? a
                (
                    setID('downloadXXD'),
                    setClass('btn not-open-url' . ($disabled ? ' disabled' : ' primary')),
                    $disabled ? null : set::href(inlink('downloadXXD', "type=package&os={$os}")),
                    $lang->im->downloadXXD
                ) : null,
                a
                (
                    setClass('btn not-open-url' . ($disabled ? ' disabled' : ' primary')),
                    $disabled ? null : set::href(inlink('downloadXXD', 'type=config')),
                    $lang->im->downloadConfig
                ),
                a
                (
                    setClass('btn'),
                    set::href(inlink('xuanxuan', 'type=edit')),
                    $lang->im->changeSetting
                ),
                a
                (
                    setClass('btn'),
                    set::target('_blank'),
                    set::href('http://www.zentao.net/book/zentaopmshelp/302.html'),
                    $lang->im->help
                )
            )
        )
    );
}

render();
