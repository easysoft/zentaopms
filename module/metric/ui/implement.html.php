<?php
declare(strict_types=1);
/**
 * The implement file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metric
 * @link        http://www.zentao.net
 */
namespace zin;
jsVar('metricId',     $metric->id);
jsVar('code',         $metric->code);
jsVar('verifyCustomMethods', $verifyCustom);
jsVar('isVerify',     $isVerify);

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($lang->metric->implement->common)
        ),
        label
        (
            to::before(icon
            (
                setClass('warning500 margin-left8'),
                'help',
            )),
            set::text($lang->metric->implement->tip),
            setClass('label ghost')
        )
    )
);

$fnGenerateInstructions = function() use($lang)
{
    $instructions = array();
    foreach($lang->metric->implement->instructionTips as $tip)
    {
        $instructions[] = p
        (
            set::className('font-medium text-md'),
            setStyle('padding-top', '12px'),
            html($tip)
        );
    }

    return $instructions;
};

$fnGenerateDataDisplay = function() use($resultData, $resultHeader, $lang, $metric)
{
    if(empty($resultData)) return null;
    if(count($resultData) == 1 && count((array)$resultData[0]) == 1) return div
        (
            set::className('card-data'),
            center
            (
                p
                (
                    set::className('card-digit'),
                    $resultData[0]->value
                ),
                p
                (
                    set::className('card-title'),
                    $lang->metric->objectList[$metric->object]
                )
            )

        );

    return dtable
        (
            set::height(400),
            set::cols($resultHeader),
            set::data($resultData)
        );
};

panel
(
    setClass('clear-shadow'),
    set::bodyClass('relative'),
    btn
    (
        setClass('ghost btn-download'),
        $lang->metric->implement->downloadPHP,
        set::url(helper::createLink('metric', 'downloadTemplate', "metricID={$metric->id}")),
        set::target('_blank')
    ),
    div
    (
        h1
        (
            setClass('border-bottom margin-top24'),
            span
            (

                $lang->metric->implement->instruction,
                setClass('gray-pale text-md font-bold')
            )
        ),
        div
        (
            setClass('leading-loose'),
            $fnGenerateInstructions()
        ),
        h1
        (
            setClass('border-bottom margin-top24'),
            span
            (
                $lang->metric->verifyResult,
                setClass('gray-pale text-md font-bold')
            )
        ),
        empty($result) ? div
        (
            setClass('verify-content'),
        ) : $fnGenerateDataDisplay(),
    ),

    set::footerClass('footer-actions'),
    set::footerActions
    ([
        [
            'type' => 'secondary',
            'class' => 'btn-verify',
            'text' => $lang->metric->verifyFile,
            'url' => helper::createLink('metric', 'implement', "metricID={$metric->id}")
        ],
        [
            'type' => 'primary',
            'text' => $lang->metric->publish,
            'class' => 'ajax-submit',
            'btnType' => 'submit',
            'disabled' => empty($result),
            'url' => helper::createLink('metric', 'publish', "metricID={$metric->id}")
        ],
    ])
);

render();
