<?php
declare(strict_types=1);
/**
 * The step2 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

include $this->app->getConfigRoot() . 'timezones.php';

set::zui(true);

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        panel
        (
            setClass('py-2'),
            set::title($lang->install->setConfig),
            form
            (
                set::submitBtnText($lang->install->next),
                set::actions(array('submit')),
                h::table
                (
                    setClass('table bordered'),
                    h::tr
                    (
                        h::th
                        (
                            width('1/5'),
                            $lang->install->key
                        ),
                        h::th($lang->install->value),
                        h::th()
                    ),
                    h::tr
                    (
                        h::th($lang->install->timezone),
                        h::td
                        (
                            picker
                            (
                                set::name('timezone'),
                                set::items($timezoneList),
                                set::value($config->timezone)
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        h::th($lang->install->defaultLang),
                        h::td
                        (
                            picker
                            (
                                set::name('defaultLang'),
                                set::items($config->langs),
                                set::value($app->getClientLang())
                            )
                        ),
                        h::td()
                    ),
                    $config->edition != 'open' ? h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th($lang->install->dbDriver),
                        h::td
                        (
                            picker
                            (
                                set::name('dbDriver'),
                                set::items($lang->install->dbDriverList),
                                set::value('mysql')
                            )
                        ),
                        h::td()
                    ) : input
                    (
                        setClass('hidden'),
                        set::name('dbDriver'),
                        set::value('mysql')
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th($lang->install->dbHost),
                        h::td
                        (
                            input
                            (
                                set::name('dbHost'),
                                set::value($dbHost)
                            )
                        ),
                        h::td($lang->install->dbHostNote)
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th($lang->install->dbPort),
                        h::td
                        (
                            input
                            (
                                set::name('dbPort'),
                                set::value($dbPort)
                            )
                        )
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th($lang->install->dbEncoding),
                        h::td
                        (
                            input
                            (
                                set::name('dbEncoding'),
                                set::value($this->config->db->encoding)
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th($lang->install->dbUser),
                        h::td
                        (
                            input
                            (
                                set::name('dbUser'),
                                set::value($dbUser)
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th($lang->install->dbPassword),
                        h::td
                        (
                            input
                            (
                                set::name('dbPassword'),
                                set::value($dbPassword)
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        h::th($lang->install->dbName),
                        h::td
                        (
                            input
                            (
                                set::name('dbName'),
                                set::value($dbName)
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        h::th($lang->install->dbPrefix),
                        h::td
                        (
                            input
                            (
                                set::name('dbPrefix'),
                                set::value('zt_')
                            )
                        ),
                        h::td
                        (
                            checkbox
                            (
                                set::text($lang->install->clearDB),
                                set::name('clearDB')
                            )
                        )
                    )
                )
            )
        )
    )
);

render('pagebase');
