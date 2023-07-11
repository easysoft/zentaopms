<?php
declare(strict_types=1);
/**
 * The create view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     sonarqube
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('sonarqubeCreateForm'),
    set::title($lang->sonarqube->createServer),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->sonarqube->name),
            set::value($sonarqube->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('url'),
            set::label($lang->sonarqube->url),
            set::value($sonarqube->url),
            set::placeholder($lang->sonarqube->placeholder->url)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('account'),
            set::label($lang->sonarqube->account),
            set::value($sonarqube->account),
            set::placeholder($lang->sonarqube->placeholder->account)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('password'),
            set::label($lang->sonarqube->password),
            set::value($sonarqube->password),
        )
    ),
);
