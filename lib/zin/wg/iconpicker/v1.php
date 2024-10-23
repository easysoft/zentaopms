<?php
declare(strict_types=1);
/**
 * The iconPicker widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class iconPicker extends wg
{
    protected static array $defineProps = array(
        'name?: string="icon"',     // 控件名称。
        'value?: string="flow"',    // 控件默认值。
        'items?: array'             // 图标列表项。
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildIcons(): array
    {
        $icons = [];
        $items = $this->prop('items', []);
        foreach($items as $icon)
        {
            $icons[] = button
            (
                setClass('btn square ghost'),
                setData(['icon' => $icon]),
                on::click('selectIcon'),
                icon($icon)
            );
        }
        return $icons;
    }

    protected function build()
    {
        $name = $this->prop('name');
        $icon = $this->prop('value');

        return div
        (
            setID('iconPicker'),
            button
            (
                setClass('btn'),
                setData(['toggle' => 'dropdown']),
                span
                (
                    setID('iconPreview'),
                    setClass('mr-2'),
                    icon($icon)
                ),
                icon('angle-down')
            ),
            div
            (
                setClass('dropdown-menu menu w-64'),
                $this->buildIcons()
            ),
            formHidden($name, $icon, setID('icon'))
        );
    }
}
