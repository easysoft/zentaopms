<?php
/**
 * The xmind library of zentaopms, can be used to bakup and restore a database.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     Xmind
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class xmind
{
    /**
     * Add suffix before string.
     *
     * @param  string $str
     * @param  string $suffix
     * @access public
     * @return string
     */
    public function toText($str, $suffix)
    {
        if(empty($suffix)) return $str;
        return $str . '['.$suffix.']';
    }

    /**
     * Create module node.
     *
     * @param  DOMDocument $xmlDoc
     * @param  array       $context
     * @param  DOMElement  $productTopics
     * @param  array       $moduleTopics
     * @access public
     * @return void
     */
    function createModuleTopic($xmlDoc, $context, $productTopics, &$moduleTopics)
    {
        $config     = $context['config'];
        $moduleList = $context['moduleList'];
        $caseModules = array_column($context['caseList'], 'moduleID');
        $caseModules = array_filter($caseModules);
        $caseModules = array_combine($caseModules, $caseModules);

        foreach($moduleList as $key => $name)
        {
            if(!isset($caseModules[$key])) continue;

            $suffix      = $config['module'] . ':' . $key;
            $moduleTopic = $this->createTopic($xmlDoc, $name, $suffix, array('nodeType' => 'module'));

            $moduleChildrenTopics = $this->createTopics($xmlDoc);
            $moduleChildren       = $xmlDoc->createElement('children');
            $moduleChildren->appendChild($moduleChildrenTopics);

            $moduleTopic->appendChild($moduleChildren);
            $productTopics->appendChild($moduleTopic);

            $moduleTopics[$key] = $moduleChildrenTopics;
        }
    }

    /**
     * Create scene node.
     *
     * @param  DOMDocument $xmlDoc
     * @param  array       $context
     * @param  DOMElement  $productTopics
     * @param  array       $moduleTopics
     * @param  array       $sceneTopics
     * @access public
     * @return void
     */
    function createSceneTopic($xmlDoc, $context, $productTopics, &$moduleTopics, &$sceneTopics)
    {
        $config    = $context['config'];
        $topScenes = $context['topScenes'];
        $caseScenes = array_column($context['caseList'], 'moduleID');
        $caseScenes = array_filter($caseScenes);
        $caseScenes = array_combine($caseScenes, $caseScenes);

        foreach($topScenes as $key => $scene)
        {
            if(!isset($caseScenes[$key])) continue;
        }
    }
}
