<?php
declare(strict_types=1);
/**
 * The mainNavbar widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

/**
 * 主内容导航（三级导航，mainNavbar）部件类。
 * The main navbar widget class.
 *
 * @author Hao Sun
 */
class mainNavbar extends nav
{
    /**
     * Define the blocks.
     *
     * @var array
     * @access protected
     */
    static $defineBlocks = array
    (
        'left' => array('map' => 'dropdown'),
        'right' => array('map' => 'toolbar'),
    );

    /**
     * Override the build method.
     *
     * @access protected
     * @return array
     */
    protected function build(): wg
    {
        $leftBlock  = $this->block('left');
        $rightBlock = $this->block('right');

        return div
        (
            setID('mainNavbar'),
            div
            (
                setClass('container'),
                empty($leftBlock) ? null : div(setClass('main-navbar-left'), $leftBlock),
                parent::build(),
                empty($rightBlock) ? null : div(setClass('main-navbar-right'), $rightBlock)
            )
        );
    }
}
