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
        $stepList = $context['stepList'];
        $config   = $context['config'];
        $suffix   = $config['case'].':'.$case->testcaseID.','.$config['pri'].':'.$case->pri;
        $caseTopic = $this->createTopic($xmlDoc, $case->name, $suffix, array('nodeType'=>'testcase'));

        $caseChildrenTopics = $this->createTopics($xmlDoc);
        $caseChildren       = $xmlDoc->createElement('children');
        $caseChildren->appendChild($caseChildrenTopics);
        $caseTopic->appendChild($caseChildren);

        $parentTopic->appendChild($caseTopic);

        $this->createPreconditionTopic($xmlDoc, $config, $caseChildrenTopics, $case->precondition);

        $topStepList = $this->findTopStepListByCase($case, $stepList);
        $this->createStepTopic($xmlDoc, $config, $caseChildrenTopics, $stepList, $topStepList);
    }

    /**
     * 生成用例前置条件节点。
     * Create precondition node.
     *
     * @param  DOMDocument $xmlDoc
     * @param  array       $config
     * @param  object      $parentTopic
     * @param  string      $precondition
     * @access private
     * @return void
     */
    private function createPreconditionTopic($xmlDoc, $config, $parentTopics, $precondition)
    {
        if(empty($precondition)) return false;
        $preconditionTopic = $this->createTopic($xmlDoc, $precondition, $config['precondition'], array('nodeType' => 'precondition'));
        $parentTopics->appendChild($preconditionTopic);
    }

    /**
     * 生成用例步骤节点。
     * Create step node.
     *
     * @param  DOMDocument $xmlDoc
     * @param  array       $config
     * @param  object      $parentTopic
     * @param  array       $allSteps
     * @param  array       $steps
     * @access private
     * @return void
     */
    private function createStepTopic($xmlDoc, $config, $parentTopics, $allSteps, $steps)
    {
        foreach($steps as $step)
        {
            $subSteps = $this->findSubStepListByStep($step, $allSteps);
            $suffix   = count($subSteps) > 0 ? $config['group'] : '';

            $stepTopic = $this->createTopic($xmlDoc, $step->desc, $suffix, array('nodeType' => 'step'));

            $stepChildrenTopics = $this->createTopics($xmlDoc);
            $stepChildren       = $xmlDoc->createElement('children');
            $stepChildren->appendChild($stepChildrenTopics);

            if($subSteps)
            {
                $this->createStepTopic($xmlDoc, $config, $stepChildrenTopics, $allSteps, $subSteps);
            }
            else if(!empty($step->expect))
            {
                $expectTopic = $this->createTopic($xmlDoc, $step->expect, '', array('nodeType' => 'expect'));
                $stepChildrenTopics->appendChild($expectTopic);
            }

            $stepTopic->appendChild($stepChildren);

            $parentTopics->appendChild($stepTopic);
        }
    }

    /**
     * Find substep list by step.
     *
     * @param  object $step
     * @param  array  $stepList
     * @access public
     * @return array
     */
    function findSubStepListByStep($step,$stepList)
    {
        $subList = array();
        foreach($stepList as $one)
        {
            if($one->parentID == $step->stepID)
            {
                $subList[] = $one;
            }
        }

        return $subList;
    }

    /**
     * Find top step list by case.
     *
     * @param  object $case
     * @param  array  $stepList
     * @access public
     * @return array
     */
    public function findTopStepListByCase($case,$stepList)
    {
        $topList = array();
        foreach($stepList as $step)
        {
            if($step->parentID == '0' && $step->testcaseID == $case->testcaseID)
            {
                $topList[] = $step;
            }
        }

        return $topList;
    }

    /**
     * Create xmind topic.
     *
     * @param  DOMDocument $xmlDoc
     * @param  string      $text
     * @param  string      $suffix
     * @param  array       $attrs
     * @access public
     * @return object
     */
    public function createTopic($xmlDoc, $text, $suffix = '', $attrs = array())
    {
        $topic = $xmlDoc->createElement('topic');

        $titleAttr = $xmlDoc->createElement('title', $this->toText($text, $suffix));
        $topic->appendChild($titleAttr);

        foreach($attrs as $key => $value)
        {
            $attr      = $xmlDoc->createAttribute($key);
            $attrValue = $xmlDoc->createTextNode($value);

            $attr->appendChild($attrValue);
            $topic->appendChild($attr);
        }

        return $topic;
    }

    /**
     * Get substring between mark1 and mark2 from kw.
     *
     * @param  string $str
     * @param  string $suffix
     * @access public
     * @return string
     */
    function getBetween($kw1, $mark1, $mark2)
    {
        $kw = $kw1;
        $kw = '123' . $kw . '123';
        $st = strripos($kw, $mark1);
        $ed = strripos($kw, $mark2);

        if(($st == false || $ed == false) || $st >= $ed) return 0;

        $kw = substr($kw, ($st + 1), ($ed - $st - 1));
        return $kw;
    }

    /**
     * Judgment ends with a string.
     *
     * @param  string $haystack
     * @param  string $needle
     * @access public
     * @return string
     */
    function endsWith($haystack, $needle)
    {
        return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }

    /**
     * Create children topics.
     *
     * @param  DOMDocument $xmlDoc
     * @access public
     * @return void
     */
    function createChildrenTopics($xmlDoc)
    {
        $topics = $this->createTopics($xmlDoc);

        $children = $xmlDoc->createElement('children');
        $children->appendChild($topics);
        return $children;
    }

    /**
     * Create topics.
     *
     * @param  DOMDocument $xmlDoc
     * @access public
     * @return void
     */
    public function createTopics($xmlDoc)
    {
        $type = $xmlDoc->createAttribute('type');
        $type->value = 'attached';

        $topics  = $xmlDoc->createElement('topics');
        $topics->appendChild($type);

        return $topics;
    }
}
