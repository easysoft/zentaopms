<?php
declare(strict_types=1);

namespace zin;

class password extends wg
{
    protected static array $defineProps = array(
        'id?: string="password1"',
        'name?: string="password1"',
        'checkStrength?: bool=false',
        'strengthID?: string="passwordStrength"',
        'strengthClass?: string="passwordStrength"'
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): array|wg
    {
        global $app, $config, $lang;
        $app->loadLang('user');
        $jsRoot = $app->getWebRoot() . 'js/';

        list($id, $name, $checkStrength, $strengthID, $strengthClass) = $this->prop(array('id', 'name', 'checkStrength', 'strengthID', 'strengthClass'));

        return $checkStrength ? array
        (
            h::jsCall('$.getLib', $jsRoot . 'md5.js'),
            jsVar('window.strengthClass', $strengthClass),
            jsVar('window.passwordStrengthList', $lang->user->passwordStrengthList),
            inputGroup
            (
                input
                (
                    setID($id),
                    on::keyup('checkPassword'),
                    set::type('password'),
                    set::name($name),
                    set::placeholder(zget($lang->user->placeholder->passwordStrength, $config->safe->mode, ''))
                ),
                span
                (
                    setID($strengthID),
                    setClass("input-group-addon {$strengthClass} hidden")
                )
            )
        ) : input
        (
            set::type('password'),
            set::name($name),
            set($this->getRestProps()),
        );
    }
}
