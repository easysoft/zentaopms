<?php
declare(strict_types=1);
/**
 * The about view file of misc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     misc
 * @link        https://www.zentao.net
 */
namespace zin;

set::title(' ');
set::size('lg');

$zentaoItems = array();
foreach($lang->misc->zentao as $label => $groupItems)
{
    if(strpos(',labels,icons,version,others,', ",$label,") !== false) continue;

    $api = $this->app->getClientLang() == 'en' ? $config->misc->enApi : $config->misc->api;

    $liItems = array();
    foreach($groupItems as $groupItem => $groupLabel)
    {
        $bizLink   = $this->app->getClientLang() == 'en' ? 'https://www.zentao.pm' : 'https://www.zentao.net/page/enterprise.html';
        $link      = $groupItem == 'bizversion' ? $bizLink : "{$api}/goto.php?item={$groupItem}&from=about" ;
        $liItems[] = h::li
        (
            setStyle('line-height', '24px'),
            a
            (
                setID($groupItem),
                set('href', $link),
                set('target', '_blank'),
                set('class', ($groupItem == 'zentaotrain' || $groupItem == 'bizversion' ? 'text-danger' : '')),
                $groupLabel
            )
        );
    }

    $zentaoItems[] = panel
    (
        set::className('w-1/4 mx-3'),
        set::title($lang->misc->zentao->labels[$label]),
        set::titleClass('strong'),
        set::headingClass('gray-200'),
        set::bodyClass('pd-10px'),
        h::ul
        (
            set::className('pl-5 mb-2'),
            setStyle('list-style-type', 'disc'),
            $liItems
        )
    );
}

h::table
(
    set::className('table borderless'),
    h::tr
    (
        h::td
        (
            set::className('text-center'),
            zui::width('200px'),
            img
            (
                set('src', $config->webRoot . 'theme/default/images/main/' . $lang->logoImg)
            ),
            h4
            (
                set::className('text-center my-2 font-bold'),
                trim($config->visions, ',') == 'lite' ? $lang->liteName . $config->liteVersion : sprintf($lang->misc->zentao->version, $config->version)
            )
        ),
        h::td
        (
            div
            (
                set::className('flex flex-nowrap justify-start'),
                $zentaoItems
            )
        )
    ),
    h::tr
    (
        h::td
        (
            set::className('copyright text-right align-middle'),
            set('colspan', 2),
            div
            (
                set::className('pull-left'),
                html($lang->designedByAIUX),
            ),
            div
            (
                set::className('pull-right'),
                html($lang->misc->copyright)
            )
        )
    )
);

render('modalDialog');
