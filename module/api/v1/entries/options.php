<?php
/**
 * The options entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class optionsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function get($type = '')
    {
        if(!$type) return $this->sendError(400, 'error');

        $options = array();
        switch($type)
        {
            case 'bug':
                $this->app->loadLang('bug');
                $options['type']     = $this->lang->bug->typeList;
                $options['pri']      = $this->lang->bug->priList;
                $options['severity'] = $this->lang->bug->severityList;

                $options['modules'] = new stdclass();
                $product = $this->param('product', 0);
                if($product) $options['modules'] = $this->loadModel('tree')->getOptionMenu($product, 'bug');

                $execution = $this->param('execution', 0);
                $options['build'] = $this->loadModel('build')->getBuildPairs(array($product), 'all', '', $execution, 'execution');

                break;
        }

        return $this->send(200, array('options' => $options));
    }
}
