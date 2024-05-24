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
        'customFields'    => array(),
        'urlParams'       => '',
        'canGlobal'       => false,
        'submitCallBack'  => '',
        'restoreCallBack' => '',
        'text'            => ''
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
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
                set::checked($showFields ? in_array($field, $showFields) : true),
                empty($text) ? set::rootClass('hidden') : null
            );
        }
        return $items;
    }

    protected function build()
    {
        list($urlParams, $text, $submitCallback, $restoreCallback, $canGlobal) = $this->prop(array('urlParams', 'text', 'submitCallback', 'restoreCallback', 'canGlobal'));
        $customFields = $this->prop('customFields', array());
        $urlParams    = $this->prop('urlParams', '');

        global $lang;

        if($canGlobal)
        {
            global $app;
            $app->loadLang('datatable'); // Use lang->datatable->setGlobal variable.
        }

        $customLink = createLink('custom', 'ajaxSaveCustomFields', $urlParams);
        $cancelLink = createLink('custom', 'ajaxGetCustomFields', $urlParams);
        return dropdown
        (
            set::arrow('false'),
            set::placement('bottom-end'),
            set::id('formSettingBtn'),
            to::trigger(btn(set::icon('cog-outline'), $text, setClass('ghost'), set::caret(false))),
            to::menu(menu
            (
                setClass('dropdown-menu'),
                on::click('e.stopPropagation();'),
                formpanel
                (
                    setClass('form-setting-btn'),
                    set::title($lang->customField),
                    $canGlobal ? to::titleSuffix(checkList(set::name('global'), setClass('text-base font-normal ml-2'), set::inline(true), set::items(array(array('text' => $lang->datatable->setGlobal, 'value' => '1'))))) : null,
                    set::url($customLink),
                    set::showExtra(false),
                    set::actions(array
                    (
                        btn(set::text($lang->save), setClass('primary'), on::click('onSubmitFormtSetting'), $submitCallback ? on::click($submitCallback) : null),
                        btn(set::text($lang->cancel), set::btnType('button'), on::click('cancelFormSetting'), set('data-url', $cancelLink)),
                        btn(set::text($lang->restore), setClass('text-primary ghost'), set('data-url', $customLink), on::click('revertDefaultFields'), $restoreCallback ? on::click($restoreCallback) : null)
                    )),
                    to::headingActions(array
                    (
                        btn(set::icon('close'), setClass('ghost'), set::size('sm'), on::click('closeCustomPopupMenu'))
                    )),
                    $this->buildCustomFields($customFields)
                )
            ))
        );
    }
}
