<?php
declare(strict_types=1);
/**
 * The createproject view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('sonarqubeCreateForm'),
    set::title($lang->sonarqube->createProject),
    formGroup
    (
        set::name('projectName'),
        set::label($lang->sonarqube->projectName)
    ),
    formGroup
    (
        set::name('projectKey'),
        set::label($lang->sonarqube->projectKey)
    ),
);
