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

    private function buildCustomFields(array $customFields): array
    {
        $listFields = zget($customFields, 'list', array());
        $showFields = zget($customFields, 'show', array());
        if(!$listFields) return array();

        $items = array();
        foreach($listFields as $field => $text)
        {
            $items[] = checkbox
            (
                set::name('fields[]'),
                set::value($field),
                set::text($text),
                set::checked($showFields ? in_array($field, $showFields) : true)
            );
        }
        return $items;
    }

    protected function build(): wg
    {
        $customFields = $this->prop('customFields', array());

        global $lang;

        $customLink = createLink('custom', 'ajaxSaveCustomFields', $this->prop('urlParams', ''));
        $cancelLink = createLink('custom', 'ajaxGetCustomFields', $this->prop('urlParams', ''));
        return dropdown
        (
            set::arrow('false'),
            set::placement('bottom-end'),
            set::id('formSettingBtn'),
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
                        btn(set::text($lang->cancel), set::btnType('button'), on::click('cancelFormSetting'), set('data-url', $cancelLink)),
                        btn(set::text($lang->restore), setClass('text-primary ghost'), set::href('#'), set('data-url', $customLink), on::click('revertDefaultFields')),
                    )),
                    to::headingActions(array(btn(set::icon('close'), setClass('ghost'), set::size('sm'), on::click('closeCustomPopupMenu')))),
                    $this->buildCustomFields($customFields)
                )
            ))
        );
    }
}
