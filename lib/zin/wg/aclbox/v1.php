<?php
declare(strict_types=1);

namespace zin;
class aclBox extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'aclItems?: array',                    // 访问控制可选项。
        'aclValue?: string="open"',            // 访问控制默认选中值。
        'whitelistLabel?: string=""',          // 白名单标签。
        'userName?: string="whitelist[]"',     // 用户组名称。
        'userValue?: string=""',               // 用户组默认选中值。
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        list($aclItems, $aclValue, $whitelistLabel, $groupLabel, $userLabel, $groupName, $userName, $groupItems, $groupValue, $userValue) = $this->prop(array('aclItems', 'aclValue', 'whitelistLabel', 'groupLabel', 'userLabel', 'groupName', 'userName', 'groupItems', 'groupValue', 'userValue'));

        return div
        (
            div
            (
                setClass('aclBox'),
                radiolist
                (
                    set(array('items' => $aclItems, 'value' => $aclValue, 'name' => 'acl')),
                    on::change()->toggleClass('.whitelistBox', 'hidden', "\$element.find('[name=acl]:checked').val() === 'open'")
                )
            ),
            formGroup
            (
                setClass('whitelistBox'),
                $aclValue == 'open' ? setClass('hidden') : null,
                set(array('label' => $whitelistLabel, 'required' => false)),
                whitelist
                (
                    set(array('inputGroupClass' => 'w-full', 'name' => $userName, 'value' => $userValue))
                )
            )
        );
    }
}
