<?php
declare(strict_types=1);
/**
* The yyy view file of zanode module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yanyi Cao <caoyanyi@easycorp.ltd>
* @package     zanode
* @link        https://www.zentao.net
*/
namespace zin;
div
(
    h::iframe
    (
        setID('urlIframe'),
        set::src("http://$url/novnc/vnc.html?resize=scale&autoconnect=true&port=6080&path=websockify/?token=$token&password=pass&resize=scale&autoconnect=true"),
        set::allowfullscreen(true),
        set::allowtransparency(true),
        set::frameborder('no'),
        set::style(array('width' => '100%')),
    )
);

h::js("document.getElementById('urlIframe').style.height = parseInt(window.screen.height - 200) + 'px';");
render('pagebase');
