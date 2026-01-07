<?php
/**
 * The required fields entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ruogu Liu <liuruogu@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class requiredFieldsEntry extends entry
{
    /**
     * GET method.
     * 获取所有模块的自定义必填字段配置
     * Get required fields configuration for all modules.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $this->loadModel('custom');

        /* Load language for all required modules. */
        foreach($this->config->custom->requiredModules as $requiredModule) $this->app->loadLang($requiredModule);

        $result = array();

        /* Get required fields for each module. */
        foreach($this->config->custom->requiredModules as $moduleName)
        {
            /* Load module config. */
            $this->loadModel($moduleName);
            if($moduleName == 'user') $this->app->loadModuleConfig($moduleName);

            /* Get required fields. */
            $requiredFields = $this->custom->getRequiredFields($this->config->$moduleName);

            /* Handle special case for doc module. */
            if($moduleName == 'doc')
            {
                unset($requiredFields['createlib']);
                unset($requiredFields['editlib']);
            }

            /* Format result. */
            if(!empty($requiredFields))
            {
                $moduleFields = array();
                foreach($requiredFields as $method => $fields)
                {
                    $moduleFields[$method] = array(
                        'method' => $method,
                        'fields' => explode(',', $fields),
                        'fieldsString' => $fields
                    );
                }

                $result[$moduleName] = $moduleFields;
            }
        }

        return $this->send(200, $result);
    }
}
