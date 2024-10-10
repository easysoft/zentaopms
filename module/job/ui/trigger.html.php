<?php
declare(strict_types=1);
/**
 * The trigger view file of job module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     job
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('repo', $repo);
jsVar('dirs', !empty($dirs) ? $dirs : '');
jsVar('buildTag', $lang->job->buildTag);
jsVar('dirChange', $lang->job->dirChange);
jsVar('triggerTypeList', $lang->job->triggerTypeList);

jsVar('svnField', formRow
(
    setClass('svn-fields linkage-fields hidden'),
    formGroup
    (
        set::name('svnDir[]'),
        set::width('1/2'),
        set::label($lang->job->svnDir),
        set::items(!empty($dirs) ? $dirs : array()),
        set::value($job->svnDir)
    )
)->render());
