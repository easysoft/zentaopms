<?php
declare(strict_types=1);
/**
 * The code view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('changeModeTips', sprintf($lang->custom->changeModeTips, $lang->custom->modeList[$mode == 'light' ? 'ALM' : 'light']));
jsVar('mode', $mode);
jsVar('hasProgram', !empty($programs));

$featureItems = array();
foreach($disabledFeatures as $feature)
{
    if(is_array($feature) and empty($disabledScrumFeatures)) continue;
    $featureItems[] = h::tr
    (
        setClass('text-center'),
        h::td(setClass('text-left'), (is_array($feature) && !empty($disabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $disabledScrumFeatures)) : $this->lang->custom->features[$feature]),
        h::td(setClass('text-center'), icon(setClass('text-danger'), 'ban-circle')),
        h::td(setClass('text-center'), icon(setClass('text-success'), 'check'))
    );
}
foreach($config->custom->allFeatures as $feature)
{
    if(in_array($feature, $disabledFeatures)) continue;
    if($feature == 'scrumDetail' and empty($enabledScrumFeatures)) continue;

    $featureItems[] = h::tr
    (
        setClass('text-center'),
        h::td(setClass('text-left'), ($feature == 'scrumDetail' and !empty($enabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $enabledScrumFeatures)) : $this->lang->custom->features[$feature]),
        h::td(setClass('text-center'), icon(setClass('text-success'), 'check')),
        h::td(setClass('text-center'), icon(setClass('text-success'), 'check'))
    );
}

formPanel
(
    set::title($lang->custom->modeManagement),
    p(setClass('font-bold'), $currentModeTips),
    set::actions(false),
    h::table
    (
        setClass('modeTable'),
        h::thead
        (
            h::tr
            (
                h::th($lang->custom->mode),
                h::th(setClass('text-center'), $lang->custom->modeList['light']),
                h::th(setClass('text-center'), $lang->custom->modeList['ALM'])
            )
        ),
        h::tbody
        (
            h::tr
            (
                h::td($lang->custom->usage),
                h::td(setClass('text-center'), $lang->custom->modeIntroductionList['light']),
                h::td(setClass('text-center'), $lang->custom->modeIntroductionList['ALM'])
            ),
            $featureItems,
            h::tr
            (
                setClass('text-center select-mode'),
                h::td(setClass('text-left font-bold'), $lang->custom->selectUsage),
                h::td
                (
                    $mode == 'light' ? set::title($currentModeTips) : null,
                    btn
                    (
                        setID('useLight'), 
                        setClass('primary wide'), 
                        set('data-mode', 'light'), 
                        $mode != 'light' && empty($programs) ? null : set('data-toggle', 'modal'), 
                        $mode != 'light' && empty($programs) ? null : set('data-target', '#selectProgramModal'), 
                        $mode == 'light' ? set('disabled', true) : null, 
                        set('onclick', 'saveMode(this)'), 
                        $lang->custom->useLight
                    )
                ),
                h::td
                (
                    $mode == 'ALM' ? set::title($currentModeTips) : null,
                    btn(setID('useALM'), setClass('primary wide'), set('data-mode', 'ALM'), $mode == 'ALM' ? set::disabled(true) : null, set('onclick', 'saveMode(this)'), $lang->custom->useALM),
                    formHidden('mode', $mode)
                )
            )
        )
    )
);

modal
(
    setID('selectProgramModal'),
    set::title($lang->custom->selectDefaultProgram),
    set::size('sm'),
    form
    (
        div(setClass('alert secondary-pale'), $lang->custom->selectProgramTips),
        formGroup(set::label($lang->custom->defaultProgram), picker(setID('program'), set::name('program'), set::items($programs), set::value(!empty($programs) ? $programID : ''), set::required(true))),
        set::actions(array(array('text' => $lang->save, 'class' => 'primary btn-save', 'onclick' => 'submitMode(this)')))
    )
);

/* ====== Render page ====== */
render();
