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
jsVar('from',         $from);
jsVar('isVerify',     $isVerify);

jsVar('isModuleCalcExist', $isModuleCalcExist);
jsVar('moduleCalcTip',     $moduleCalcTip);
jsVar('checkModuleFile',   $this->lang->metric->checkFile);

detailHeader
(
    to::title
    (
        entityLabel
        (
            setClass('text-xl font-black'),
            set::level(1),
            set::text($lang->metric->implement->common)
        ),
        label
        (
            to::before(icon
            (
                setClass('warning-ghost margin-left8'),
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
        div
        (
            setClass('verify-content'),
            div
            (
                setClass('verify-result')
            ),
            div
            (
                setClass('metric-result')
            )
        )
    ),

    set::footerClass('footer-actions'),
    set::footerActions
    ([
        [
            'type' => 'secondary',
            'class' => 'btn-verify',
            'text' => $lang->metric->verifyFile,
            'url' => helper::createLink('metric', 'implement', "metricID={$metric->id}&from={$from}")
        ],
        [
            'type' => 'primary',
            'text' => $lang->metric->publish,
            'class' => 'ajax-submit publish-btn-disabled',
            'btnType' => 'submit',
            'disabled' => true
        ],
        [
            'type' => 'primary',
            'text' => $lang->metric->publish,
            'class' => 'ajax-submit publish-btn hidden',
            'btnType' => 'submit',
            'url' => helper::createLink('metric', 'publish', "metricID={$metric->id}&from={$from}")
        ]
    ])
);

render();
