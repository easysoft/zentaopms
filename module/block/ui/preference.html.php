<?php
declare(strict_types=1);
/**
* The preference view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 展示个性化设置页面。
 * Print preference page.
 */
function printPreference()
{
    global $lang, $config;

    return div
    (
        set('class', 'preference-block pt-6 px-6'),
        form
        (
            set::url(helper::createLink('my', 'preference', "showTip=false")),
            set::labelWidth('8rem'),
            set::actions(array('submit')),
            formGroup
            (
                set::value($config->URSR),
                set::label($lang->my->storyConcept),
                set::name('URSR'),
                set::control(array
                (
                    'type'  => 'picker',
                    'items' => $config->URSRList
                ))
            ),
            in_array($config->systemMode, array('ALM', 'PLM')) ? formGroup
            (
                set::value($config->programLink),
                set::label($lang->my->programLink),
                set::name('programLink'),
                set::control(array
                (
                    'type'  => 'picker',
                    'items' => $lang->my->programLinkList
                ))
            ) : null,
            formGroup
            (
                set::value($config->productLink),
                set::label($lang->my->productLink),
                set::name('productLink'),
                set::control(array
                (
                    'type'  => 'picker',
                    'items' => $lang->my->productLinkList
                ))
            ),
            formGroup
            (
                set::value($config->projectLink),
                set::label($lang->my->projectLink),
                set::name('projectLink'),
                set::control(array
                (
                    'type'  => 'picker',
                    'items' => $lang->my->projectLinkList
                ))
            ),
            formGroup
            (
                set::value($config->executionLink),
                set::label($lang->my->executionLink),
                set::name('executionLink'),
                set::control(array
                (
                    'type'  => 'picker',
                    'items' => $lang->my->executionLinkList
                ))
            )
        )
    );
}
