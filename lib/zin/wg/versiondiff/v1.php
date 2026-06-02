<?php
declare(strict_types=1);
namespace zin;

class versiondiff extends wg
{
    protected static array $defineProps = array(
        'versionID:string',
        'currentVersion:string',
        'canDiffVersion:bool',
        'diffMode:bool',
        'baseline:string',
        'browseTemplate:string',
        'diffLang:array',
        'versionItems?:array',
        'settingsItems?:array'
    );

    public static function getPageCSS(): string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        list($versionID, $currentVersion, $canDiffVersion, $diffMode, $browseTemplate, $diffLang, $versionItems, $baseline, $settingsItems) = $this->prop(array('versionID', 'currentVersion', 'canDiffVersion', 'diffMode', 'browseTemplate', 'diffLang', 'versionItems', 'baseline', 'settingsItems'));

        global $app;

        /* 如果版本不可见，调整到最新版本。 If the version does not visible, adjust to the latest version. */
        if(!isset($versionItems[$versionID])) return $app->control->send(array('load' => sprintf($browseTemplate, 0)));

        return dropdown
        (
            jsVar('versionLangData', $diffLang),
            jsVar('versionID', $versionID),
            jsVar('currentVersion', $currentVersion),
            jsVar('canDiffVersion', $canDiffVersion),
            jsVar('+diffMode', $diffMode),
            jsVar('browseTemplate', $browseTemplate),
            jsVar('appTab', $app->tab),
            div
            (
                setClass($this->prop('class')),
                btn
                (
                    setID('versionBox'),
                    setClass('ghost gray-300-outline rounded-full', $this->prop('appendClass')),
                    setData('initmode', $diffMode ? 'diff' : 'normal'),
                    set::text($currentVersion),
                    set::hint($currentVersion),
                    set::caret(),
                    $diffMode ? setData(array('value' => $versionID)) : null
                ),
                span
                (
                    setID('compareBox'),
                    setClass($diffMode ? '' : 'hidden'),
                    btn
                    (
                        setClass('ghost'),
                        set::size('sm'),
                        set::icon('exchange'),
                        on::click()->call('exchangeVersion', jsRaw('event'))
                    ),
                    btn
                    (
                        setID('nextBox'),
                        setClass('ghost gray-300-outline rounded-full'),
                        set::text($diffMode ? zget(zget($versionItems, $baseline, array()), 'title') : ''),
                        set::hint($diffMode ? zget(zget($versionItems, $baseline, array()), 'title') : null),
                        set::caret(),
                        $diffMode ? setData(array('value' => $baseline)) : null
                    )
                )
            ),
            set::menu([
               'checkOnClick'  => '.has-checkbox .item',
               'items'         => array_values($versionItems),
               'width'         => 300,
               'header'        => jsRaw('setVersionDropdownHeader'),
               'footer'        => jsRaw('setVersionDropdownFooter'),
               'getItem'       => jsRaw('getVersionItem'),
               'onClickItem'   => jsRaw('setClickVersionItem'),
               'settingsItems' => $settingsItems
            ]),
            set::triggerProps([
                'onShown' => jsRaw('showMenu')
            ])
        );
    }
}
