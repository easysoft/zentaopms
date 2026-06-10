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
            $content[] = span
                (
                    $lang->contactUs->$key . $lang->colon,
                    $key == 'email' ? a
                    (
                        setClass('not-open-url'),
                        set::href("mailto:$value"),
                        $value
                    ) : $value
                );
        }
        return $content;
    }

    protected function build()
    {
        global $lang;

        return div
        (
            setClass('flex justify-center gap-4 font-bold mt-2'),
            span($lang->contactUs->common),
            $this->buildContent()
        );
    }
}
