<?php
declare(strict_types=1);
/**
 * The view view file of zahost module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zahost
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('hostID', $zahost->id);
jsVar('zahostLang', $lang->zahost);

$imageLink  = html::a($this->createLink('zahost', 'browseImage', "hostID=$zahost->id", '', true), $lang->zahost->image->downloadImage, '', "class='iframe'");
$createNode = html::a($this->createLink('zanode', 'create', "hostID=$zahost->id"), $lang->zahost->createZanode);

$mainActions   = array();
$commonActions = array();
foreach($config->zahost->view->operateList as $operate)
{
    if(!common::hasPriv('zahost', $operate)) continue;
    $action = $config->zahost->dtable->fieldList['actions']['list'][$operate];

    if($operate == 'delete')
    {
        $nodeList = $this->loadModel('zanode')->getListByHost($zahost->id);
        if($nodeList)
        {
            $action['disabled'] = true;
            $action['hint']     = $lang->zahost->undeletedNotice;
        }
    }

    if($operate === 'edit' || $operate === 'delete')
    {
        $commonActions[] = $action;
        continue;
    }
    $mainActions[] = $action;
}

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($zahost->id),
            set::level(1),
            set::text($zahost->name)
        ),
        $zahost->deleted ? span(setClass('label danger'), $lang->zahost->deleted) : null
    )
);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->zahost->baseInfo),
            h::table
            (
                setClass('w-full'),
                h::tr
                (
                    h::td
                    (
                        div
                        (
                            setClass('flex flex-wrap pt-2 mx-4'),
                            div
                            (
                                setClass('w-1/3'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->extranet
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    $zahost->extranet
                                )
                            ),
                            div
                            (
                                setClass('w-1/3'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->cpuCores
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    $zahost->cpuCores . '' . $lang->zahost->cpuUnit
                                )
                            ),
                            div
                            (
                                setClass('w-1/3'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->zaHostType
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    $lang->zahost->zaHostTypeList[$zahost->hostType]
                                )
                            ),
                            div
                            (
                                setClass('w-1/3 mt-4'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->status
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    zget($lang->zahost->statusList, $zahost->status)
                                )
                            ),
                            div
                            (
                                setClass('w-1/3 mt-4'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->memory
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    $zahost->memory . '' . $lang->zahost->unitList['GB']
                                )
                            ),
                            div
                            (
                                setClass('w-1/3 mt-4'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->vsoft
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    zget($lang->zahost->softwareList, $zahost->vsoft)
                                )
                            ),
                            div
                            (
                                setClass('w-1/3 mt-4'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->registerDate
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    helper::isZeroDate($zahost->heartbeat) ? '' : $zahost->heartbeat
                                )
                            ),
                            div
                            (
                                setClass('w-1/3 mt-4'),
                                span
                                (
                                    setClass('text-gray'),
                                    $lang->zahost->diskSize
                                ),
                                span
                                (
                                    setClass('ml-2'),
                                    $zahost->diskSize . '' . $lang->zahost->unitList['GB']
                                )
                            )
                        )
                    )
                )
            )
        ),
        section
        (
            set::title($lang->zahost->desc),
            set::content($zahost->desc),
            set::useHtml(true)
        )
    ),
    sectionList
    (
        div
        (
            setClass('text-lg font-bold'),
            $lang->zahost->init->statusTitle,
            button
            (
                setClass('ghost btn'),
                icon('refresh', setClass('text-primary')),
                $lang->zahost->init->checkStatus,
                on::click('ajaxGetServiceStatus')
            )
        ),
        div
        (
            set::id('statusContainer')
        ),
        div
        (
            setClass('init-success hidden'),
            html(sprintf($lang->zahost->init->initSuccessNotice, $imageLink, $createNode))
        ),
        div
        (
            setClass('init-fail hidden'),
            html($lang->zahost->init->initFailNotice),
            textarea
            (
                set::id('initBash'),
                setClass('hidden'),
                $initBash
            ),
            div
            (
                setClass('zahost-init'),
                $initBash,
                h::button
                (
                    setClass('ghost btn btn-info btn-mini btn-init-copy text-primary'),
                    icon
                    (
                        setClass('icon-common-copy icon-copy'),
                        set::title($lang->zahost->copy),
                        set::name('')
                    ),
                    on::click('onCopy')
                )
            )
        )
    ),
    common::hasPriv('zanode', 'browse') ? sectionList
    (
        section
        (
            set::title($lang->zahost->browseNode)
        ),
        !empty($nodeList) ? h::iframe
        (
            set::src(createLink('zanode', 'nodeList', "hostID={$zahost->id}", '', true)),
        ) : $lang->noData
    ) : null,
    floatToolbar
    (
        to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($mainActions),
        set::suffix($commonActions),
        set::object($zahost)
    ),
    detailSide
    (
        history()
    )
);

render();
