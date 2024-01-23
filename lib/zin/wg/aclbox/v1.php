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
        'groupLabel?: string=""',              // 权限组标签。
        'groupName?: string="groups[]"',       // 权限组名称。
        'groupItems?: string|array|function',  // 权限组下拉可选值。
        'groupValue?: string=""',              // 权限组默认选中值。
        'userLabel?: string=""',               // 用户组标签。
        'userName?: string="whitelist[]"',     // 用户组名称。
        'userValue?: string=""',               // 用户组默认选中值。
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
        list($aclItems, $aclValue, $whitelistLabel, $groupLabel, $userLabel, $groupName, $userName, $groupItems, $groupValue, $userValue) = $this->prop(array('aclItems', 'aclValue', 'whitelistLabel', 'groupLabel', 'userLabel', 'groupName', 'userName', 'groupItems', 'groupValue', 'userValue'));

        return div
        (
            div
            (
                setClass('aclBox'),
                radiolist
                (
                    set(array('items' => $aclItems, 'value' => $aclValue, 'name' => 'acl')),
                    on::change()->toggleShow('.whitelistBox', "$(target).val() === 'open'")
                )
            ),
            formGroup
            (
                setClass('whitelistBox'),
                $aclValue == 'open' ? setClass('hidden') : null,
                set(array('label' => $whitelistLabel, 'required' => false)),
                div
                (
                    $groupLabel ? div
                    (
                        setClass('input-group'),
                        span
                        (
                            setClass('input-group-addon w-14'),
                            $groupLabel
                        ),
                        picker
                        (
                            set(array('items' => $groupItems, 'name' => $groupName, 'value' => $groupValue, 'multiple' => true))
                        )
                    ) : null,
                    div
                    (
                        setClass('input-group mt-2'),
                        $userLabel ? span
                        (
                            setClass('input-group-addon w-14'),
                            $userLabel
                        ) : null,
                        whitelist
                        (
                            set(array('inputGroupClass' => 'w-full', 'name' => $userName, 'value' => $userValue))
                        )
                    )
                )
            )
        );
    }
}
