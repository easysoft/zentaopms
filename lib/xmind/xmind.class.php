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

            $suffix     = $config['scene'] . ':' . $scene->sceneID;
            $sceneTopic = $this->createTopic($xmlDoc, $scene->sceneName, $suffix, array('nodeType' => 'scene'));

            $sceneChildrenTopics = $this->createTopics($xmlDoc);
            $sceneChildren       = $xmlDoc->createElement('children');
            $sceneChildren->appendChild($sceneChildrenTopics);
            $sceneTopic->appendChild($sceneChildren);

            $this->createNextChildScenesTopic($scene, $sceneTopic, $xmlDoc, $context, $moduleTopics, $sceneTopics);

            if(isset($moduleTopics[$scene->moduleID]))
            {
                $moduleTopic = $moduleTopics[$scene->moduleID];
                $moduleTopic->appendChild($sceneTopic);
            }
            else
            {
                $productTopics->appendChild($sceneTopic);
            }

            $sceneTopics[$scene->sceneID] = $sceneChildrenTopics;
        }
    }

    /**
     * Create next child scene node.
     *
     * @param  object      $parentScene
     * @param  object      $parentTopic
     * @param  DOMDocument $xmlDoc
     * @param  array       $context
     * @param  array       $moduleTopics
     * @param  array       $sceneTopics
     * @access public
     * @return void
     */
    function createNextChildScenesTopic($parentScene,$parentTopic, $xmlDoc, $context, &$moduleTopics, &$sceneTopics)
    {
        $sceneMaps = $context['sceneMaps'];
        $config    = $context['config'];

        foreach($sceneMaps as $scene)
        {
            if($scene->parentID != $parentScene->sceneID) continue;

            $suffix     = $config['scene'] . ':' . $scene->sceneID;
            $sceneTopic = $this->createTopic($xmlDoc, $scene->sceneName, $suffix, array('nodeType'=>'scene'));

            $this->createNextChildScenesTopic($scene, $sceneTopic, $xmlDoc, $context, $moduleTopics, $sceneTopics);

            $sceneChildrenTopics = $this->createTopics($xmlDoc);
            $sceneChildren       = $xmlDoc->createElement('children');
            $sceneChildren->appendChild($sceneChildrenTopics);
            $sceneTopic->appendChild($sceneChildren);

            $sceneTopics[$scene->sceneID] = $sceneChildrenTopics;
        }
    }

    /**
     * Create test case node.
     *
     * @param  DOMDocument $xmlDoc
     * @param  array       $context
     * @param  object      $productTopic
     * @param  array       $moduleTopics
     * @param  array       $sceneTopics
     * @access public
     * @return void
     */
    function createTestcaseTopic($xmlDoc, $context, $productTopic, &$moduleTopics, &$sceneTopics)
    {
        $caseList = $context['caseList'];

        foreach($caseList as $case)
        {
            if(empty($case->testcaseID)) continue;

            if($case->sceneID && isset($sceneTopics[$case->sceneID]))
            {
                $this->createOneTestcaseTopic($case, $xmlDoc, $context, $sceneTopics[$case->sceneID]);
            }
            else
            {
                if($case->moduleID && isset($moduleTopics[$case->moduleID]))
                {
                    $this->createOneTestcaseTopic($case, $xmlDoc, $context, $moduleTopics[$case->moduleID]);
                }
                else
                {
                    $this->createOneTestcaseTopic($case, $xmlDoc, $context, $productTopic);
                }
            }
        }
    }

    /**
     * Create one test case node.
     *
     * @param  object      $case
     * @param  DOMDocument $xmlDoc
     * @param  array       $context
     * @param  object      $parentTopic
     * @access public
     * @return void
     */
    function createOneTestcaseTopic($case, $xmlDoc, $context, $parentTopic)
    {
    }
}
