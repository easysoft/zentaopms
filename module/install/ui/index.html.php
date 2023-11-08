<?php
declare(strict_types=1);
/**
 * The index view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$langItems = array();
foreach($app->config->langs as $key => $value) $langItems[] = array('text' => $value, 'value' => $key, 'data-on' => 'click', 'data-call' => 'switchLang', 'data-params' => $key);

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        setClass('px-1 mt-2'),
        panel
        (
            setClass('p-8'),
            set::title($lang->install->welcome),
            set::titleClass('text-lg'),
            to::headingActions
            (
                dropdown
                (
                    btn($app->config->langs[$app->cookie->lang]),
                    set::name('lang'),
                    set::items($langItems)
                )
            ),
            cell
            (
                html(nl2br($lang->install->desc))
            ),
            cell
            (
                setClass('flex'),
                cell
                (
                    setClass('mt-5'),
                    width('calc(100% - 210px)'),
                    html(nl2br(sprintf($lang->install->links, $versionName)))
                ),
                cell
                (
                    setClass('flex'),
                    $this->app->clientLang != 'en' ? img
                    (
                        set::src($this->app->getWebRoot() . 'theme/default/images/main/weixin.jpg'),
                        width('200px'),
                        height('200px'),
                    ) : null
                )
            ),
            cell
            (
                setClass('text-center'),
                btn
                (
                    setClass('px-6'),
                    set::url(inlink('license')),
                    set::type('primary'),
                    $lang->install->start
                )
            )
        )
    )
);

render('pagebase');

