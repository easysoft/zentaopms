<?php
declare(strict_types=1);
/**
 * The create view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('linuxList', $config->zanode->linuxList);
jsVar('windowsList', $config->zanode->windowsList);
jsVar('hostID', $hostID);

formPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->zanode->create,
        icon
        (
            'help',
            setData
            (
                array
                (
                    'toggle'     => 'tooltip',
                    'placement'  => 'right',
                    'title'      => $lang->zanode->tips,
                    'type'       => 'white',
                    'class-name' => 'text-gray border border-light'
                )
            ),
            setClass('text-gray')
        )
    )),
    formGroup
    (
        set::label($lang->zahost->type),
        set::width('1/2'),
        set::control('static'),
        picker
        (
            set::name('hostType'),
            set::value('node'),
            set::items($lang->zanode->typeList),
            set::required(true),
            on::change('onChangeType')
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('parent'),
            set::label($lang->zanode->hostName),
            set::items($hostPairs),
            set::value($hostID),
            set::width('1/2'),
            on::change('onHostChange')
        ),
        a
        (
            set::href(createLink('zahost', 'create')),
            $lang->zahost->create,
            setData(array('toggle' => 'modal')),
            setClass('leading-8 ml-2')
        )
    ),
    formGroup
    (
        set::name('name'),
        set::label($lang->zanode->name),
        set::required(true),
        set::width('1/2')
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::name('extranet'),
            set::label($lang->zahost->IP),
            set::required(true),
            set::width('1/2')
        )
    ),
    formGroup
    (
        set::name('image'),
        set::label($lang->zanode->image),
        set::items(array()),
        set::width('1/2'),
        on::change('onImageChange')
    ),
    formGroup
    (
        set::label($lang->zanode->cpuCores),
        set::width('1/2'),
        set::control('static'),
        picker
        (
            set::name('cpuCores'),
            set::items($config->zanode->os->cpuCores),
            set::required(true)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->zanode->memory),
            set::required(true),
            set::width('1/2'),
            inputGroup
            (
                input
                (
                    set::name('memory')
                ),
                'GB'
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->zanode->diskSize),
            set::width('1/2'),
            inputGroup
            (
                input
                (
                    set::name('diskSize')
                ),
                'GB'
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('osName'),
            set::label($lang->zanode->osName),
            set::required(true),
            set::readonly(true),
            set::width('1/2')
        )
    ),
    formRow
    (
        setID('osNamePhysicsContainer'),
        setClass('hidden'),
        formGroup
        (
            setID('osNamePhysicsPre'),
            set::label($lang->zanode->osName),
            set::items($config->zanode->osType),
            set::value('linux'),
            set::name('osNamePre'),
            set::required(true),
            set::width('1/4'),
            on::change('onChangeSystem')
        ),
        formGroup
        (
            set::name('osNamePhysics'),
            set::items($config->zanode->linuxList),
            set::width('1/4'),
            set::required(true)
        )
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->zanode->desc),
        set::control('editor')
    )
);
