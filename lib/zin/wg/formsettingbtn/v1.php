<?php
declare(strict_types=1);
/**
 * The formBatchPanel widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

class formSettingBtn extends wg
{
    protected static array $defaultProps = array(
        'customFields' => array(),
        'urlParams'    => ''
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): wg
    {
        $customFields = $this->prop('customFields', array());

        global $lang;

        $customLink = createLink('custom', 'ajaxSaveCustomFields', $this->prop('urlParams', ''));
        return dropdown
        (
            set::arrow('false'),
            set::placement('bottom-end'),
            to::trigger(btn(set::icon('cog-outline'), setClass('ghost'), set::caret(false))),
            to::menu(menu
            (
                setClass('dropdown-menu'),
                on::click('e.stopPropagation();'),
                formpanel
                (
                    setClass('form-setting-btn'),
                    set::title($lang->customField),
                    set::url($customLink),
                    set::actions(array
                    (
                        btn(set::text($lang->save), setClass('primary'), on::click('onSubmitFormtSetting')),
                        btn(set::text($lang->cancel), set::btnType('reset'), on::click('closeCustomPopupMenu')),
                        btn(set::text($lang->restore), setClass('text-primary ghost font-bold'), set::href('#'), set('data-url', $customLink), on::click('revertDefaultFields')),
                    )),
                    to::headingActions(array(btn(set::icon('close'), setClass('ghost'), set::size('sm'), on::click('closeCustomPopupMenu')))),
                    array_map(function($field)
                    {
                        return checkbox
                        (
                            set::name('fields[]'),
                            set::value($field['name']),
                            set::text($field['text']),
                            set::checked(isset($field['show']) ? $field['show'] : false),
                            set('data-default', isset($field['default']) ? $field['default'] : false)
                        );
                    }, $customFields),
                )
            ))
        );
    }
}
