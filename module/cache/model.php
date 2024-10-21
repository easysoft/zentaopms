<?php
declare(strict_types=1);
/**
 * The model file of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     caselib
 * @version     $Id: model.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class cacheModel extends model
{
    /**
     * 构造方法。
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->cache = $this->app->loadClass('cache');
    }

    /**
     * 魔术方法，加载子类。
     * Magic get method.
     *
     * @param  string $handlerName
     * @access public
     * @return object
     */
    public function __get(string $handlerName)
    {
        include dirname(__FILE__) . DS . 'handler' . DS . strtolower($handlerName) . '.php';
        $handlerName = $handlerName . 'Handler';

        $handler         = new $handlerName();
        $handler->cache  = $this->cache;
        $handler->app    = $this->app;
        $handler->config = $this->config;

        return $handler;
    }
}
