<?php
declare(strict_types=1);
/**
 * The api home file of api module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

$libColors    = array('var(--color-secondary-500)', 'var(--color-primary-500)', 'var(--color-warning-500)', 'var(--color-success-500)', 'var(--color-special-500)');
$isNolink     = $type == 'nolink';
$isEmpty      = $isNolink ? empty($libs) : empty($programs);
$canCreateLib = hasPriv('api', 'createLib');
$canEditLib   = hasPriv('api', 'editLib');
$canDeleteLib = hasPriv('api', 'deleteLib');
$showLibAction = $isNolink ? ($canEditLib || $canDeleteLib) : $canCreateLib;
