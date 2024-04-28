<?php
declare(strict_types=1);
/**
 * The contactUs widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class contactUs extends wg
{
    protected static array $defineProps = [
        'text?: string',
    ];

    protected function buildContent()
    {
        global $config, $lang;

        $content = [];
        foreach($config->contactUs as $key => $value)
        {
            $content[] = $lang->contactUs->$key . $lang->colon . ($key == 'email' ? "<a class='not-open-url' href='mailto:$value'>$value</a>" : $value);
        }
        return html(implode($lang->comma, $content));
    }

    protected function build()
    {
        global $lang;

        return div
        (
            setClass('font-bold text-center mt-2'),
            $this->prop('text', $lang->contactUs->common),
            $this->buildContent()
        );
    }
}
