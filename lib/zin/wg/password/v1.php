<?php
declare(strict_types=1);

namespace zin;

class password extends wg
{
    protected static array $defineProps = array(
        'id?: string="password1"',
        'name?: string="password1"',
        'strengthID?: string="passwordStrength"'
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): array
    {
        global $app, $config, $lang;
        $app->loadLang('user');
        $jsRoot = $app->getWebRoot() . 'js/';

        list($id, $name, $strengthID) = $this->prop(array('id', 'name', 'strengthID'));

        return array
        (
            h::jsCall('$.getScript', $jsRoot . 'md5.js'),
            jsVar('passwordStrengthList', $lang->user->passwordStrengthList),
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
                    setClass('input-group-addon hidden')
                )
            )
        );
    }
}
