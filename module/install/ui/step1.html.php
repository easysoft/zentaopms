<?php
declare(strict_types=1);
/**
 * The step1 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$checkTrs    = array();
$extendItems = array('pdo', 'pdoMySQL', 'json', 'openssl', 'mbstring', 'zlib', 'curl', 'filter', 'iconv');
$dirItems    = array('tmpRoot', 'dataRoot', 'session');
foreach($extendItems as $extendItem)
{
    $result     = ${"{$extendItem}Result"};
    $failLang   = $extendItem . 'Fail';
    $checkTrs[] = h::tr
    (
        h::th($lang->install->{$extendItem}),
        h::td($result == 'ok' ? $lang->install->loaded : $lang->install->unloaded),
        h::td
        (
            setClass($result . ' text-white' . ($result == 'ok' ? ' bg-success' : ' bg-danger')),
            $lang->install->{$result}
        ),
        h::td
        (
            setClass('text-left'),
            $result == 'fail' ? $lang->install->{$failItem} : ''
        )
    );
}
foreach($dirItems as $dirItem)
{
    $info       = ${"{$dirItem}Info"};
    $result     = ${"{$dirItem}Result"};
    $checkTrs[] = h::tr
    (
        h::th($lang->install->{$dirItem}),
        h::td
        (
            $info['exists']   ? $lang->install->exists   : $lang->install->notExists,
            $info['writable'] ? $lang->install->writable : $lang->install->notWritable
        ),
        h::td
        (
            setClass($result . ' text-white' . ($result == 'ok' ? ' bg-success' : ' bg-danger')),
            $lang->install->{$result}
        ),
        h::td
        (
            setClass('text-left'),
            !$info['exists'] ? html(sprintf(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $lang->install->mkdirWin : $lang->install->mkdirLinux, $info['path'], $info['path'])) : '',
            !$info['writable'] ? html(sprintf(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $lang->install->chmodWin : $lang->install->chmodLinux, $info['path'], $info['path'])) : ''
        )
    );
}

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        panel
        (
            setClass('py-2'),
            set::title($lang->install->checking),
            $notice ? to::heading
            (
                span
                (
                    setClass('text-gray'),
                    icon
                    (
                        setClass('text-warning px-1'),
                        'help'
                    ),
                    $notice
                )
            ) : '',
            h::table
            (
                setClass('table bordered'),
                h::tbody
                (
                    h::tr
                    (
                        h::th
                        (
                            width('1/5'),
                            $lang->install->checkItem
                        ),
                        h::th
                        (
                            width('1/4'),
                            $lang->install->current
                        ),
                        h::th
                        (
                            width('1/6'),
                            $lang->install->result
                        ),
                        h::th($lang->install->action)
                    ),
                    h::tr
                    (
                        h::th($lang->install->phpVersion),
                        h::td($phpVersion),
                        h::td
                        (
                            setClass($phpResult . ' text-white' . ($phpResult == 'ok' ? ' bg-success' : ' bg-danger')),
                            $lang->install->{$phpResult}
                        ),
                        h::td
                        (
                            setClass($phpResult),
                            $phpResult == 'fail' ? $lang->install->phpFail : ''
                        )
                    ),
                    $checkTrs
                )
            ),
            cell
            (
                setClass('text-center mt-4'),
                $phpResult        == 'ok' && $pdoResult      == 'ok' && $pdoMySQLResult == 'ok'
                && $tmpRootResult == 'ok' && $dataRootResult == 'ok' && $sessionResult  == 'ok'
                && $jsonResult    == 'ok' && $opensslResult  == 'ok' && $mbstringResult == 'ok'
                && $zlibResult    == 'ok' && $curlResult     == 'ok' && $filterResult   == 'ok'
                && $iconvResult   == 'ok' ?  btn
                (
                    setClass('px-6 mx-4'),
                    set::url(inlink('step2')),
                    set::type('primary'),
                    $lang->install->next
                ) : btn
                (
                    setClass('px-6 mx-4'),
                    set::url(inlink('step1')),
                    set::type('primary'),
                    $lang->install->reload
                )
            )
        )
    )
);

render('pagebase');
