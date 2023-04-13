<?php
/**
 * The xmind library of zentaopms, can be used to bakup and restore a database.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@cnezsoft.com>
 * @package     Xmind
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class xmind{
    function createModuleNode($xmlDoc, $context, $productNode, &$moduleNodes)
    {
        $config     = $context['config'];
        $moduleList = $context['moduleList'];

        foreach($moduleList as $key => $name)
        {
            $suffix      =  $config['module'].':'.$key;
            $moduleNode  =  $this->createNode($xmlDoc, $name, $suffix, array('nodeType' => 'module'));
            $productNode->appendChild($moduleNode);

            $moduleNodes[$key] = $moduleNode;
        }
    }

    function createSceneNode($xmlDoc, $context, $productNode, &$moduleNodes, &$sceneNodes)
    {
        $sceneMaps  = $context['sceneMaps'];
        $config     = $context['config'];

        $topScenes  = $context['topScenes'];

        foreach($topScenes as $scene)
        {
            $suffix    = $config['scene'].':'.$scene->sceneID;
            $sceneNode =  $this->createNode($xmlDoc, $scene->sceneName, $suffix, array('nodeType' => 'scene'));

            $this->createNextChildScenesNode($scene, $sceneNode, $xmlDoc, $context, $moduleNodes, $sceneNodes);

            if(isset($moduleNodes[$scene->moduleID]))
            {
                $moduleNode = $moduleNodes[$scene->moduleID];
                $moduleNode->appendChild($sceneNode);
            }
            else
            {
                $productNode->appendChild($sceneNode);
            }

            $sceneNodes[$scene->sceneID] = $sceneNode;
        }
    }

    function createNextChildScenesNode($parentScene,$parentNode, $xmlDoc, $context, &$moduleNodes, &$sceneNodes)
    {
        $sceneMaps  = $context['sceneMaps'];
        $config     = $context['config'];

        foreach($sceneMaps as $key => $scene)
        {
            if($scene->parentID != $parentScene->sceneID + CHANGEVALUE) continue;

            $suffix    = $config['scene'].':'.$scene->sceneID;
            $sceneNode = $this->createNode($xmlDoc, $scene->sceneName, $suffix, array('nodeType'=>'scene'));

            $this->createNextChildScenesNode($scene, $sceneNode, $xmlDoc, $context, $moduleNodes, $sceneNodes);

            $parentNode->appendChild($sceneNode);
            $sceneNodes[$scene->sceneID] = $sceneNode;
        }
    }

    function createTestcaseNode($xmlDoc, $context, $productNode, &$moduleNodes, &$sceneNodes)
    {
        $caseList = $context['caseList'];

        foreach($caseList as $case)
        {
            if(empty($case->testcaseID)) continue;

            $parentNode = $sceneNodes[$case->sceneID];
            if(!isset($parentNode)) $parentNode = $moduleNodes[$case->moduleID];
            if(!isset($parentNode)) $parentNode = $productNode;

            $this->createTestcaseNodeImpl($case, $xmlDoc, $context, $parentNode);
        }
    }

    function createTestcaseNodeImpl($case, $xmlDoc, $context, $parentNode)
    {
        $caseList = $context['caseList'];
        $stepList = $context['stepList'];
        $config   = $context['config'];
        $suffix   = $config['case'].':'.$case->testcaseID.','.$config['pri'].':'.$case->pri;
        $caseNode = $this->createNode($xmlDoc, $case->name, $suffix, array('nodeType'=>'testcase'));

        $parentNode->appendChild($caseNode);

        $topStepList = $this->findTopStepListByCase($case, $stepList);

        foreach($topStepList as $step)
        {
            $subStepList = $this->findSubStepListByStep($step,$stepList);

            $suffix   = count($subStepList) > 0 ? $config['group'] : '';
            $stepNode = $this->createNode($xmlDoc, $step->desc, $suffix, array('nodeType' => 'step'));
            $caseNode->appendChild($stepNode);

            if(count($subStepList))
            {
                foreach($subStepList as $sub)
                {
                    $subNode = $this->createNode($xmlDoc, $sub->desc, '', array('nodeType'=>'substep'));
                    $stepNode->appendChild($subNode);

                    if(!empty($sub->expect))
                    {
                        $expectNode = $this->createNode($xmlDoc, $sub->expect, '', array('nodeType'=>'expect'));
                        $subNode->appendChild($expectNode);
                    }
                }
            }

            if(count($subStepList) == 0 && !empty($step->expect))
            {
                $expectNode = $this->createNode($xmlDoc, $step->expect, '', array('nodeType'=>'expect'));
                $stepNode->appendChild($expectNode);
            }
        }
    }

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

    public function createNode($xmlDoc, $text, $suffix = '', $attrs=array())
    {
        $node = $xmlDoc->createElement('node');

        $textAttr      =  $xmlDoc->createAttribute('TEXT');
        $textAttrValue =  $xmlDoc->createTextNode($this->toText($text,$suffix));

        $textAttr->appendChild($textAttrValue);
        $node->appendChild($textAttr);

        $positionAttr      =  $xmlDoc->createAttribute("POSITION");
        $positionAttrValue =  $xmlDoc->createTextNode('right');

        $positionAttr->appendChild($positionAttrValue);
        $node->appendChild($positionAttr);

        foreach($attrs as $key => $value)
        {
            $attr      = $xmlDoc->createAttribute($key);
            $attrValue = $xmlDoc->createTextNode($value);

            $attr->appendChild($attrValue);
            $node->appendChild($attr);
        }

        return $node;
    }

    public function toText($str, $suffix)
    {
        if(empty($suffix)) return $str;
        return $str . '['.$suffix.']';
    }

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

    function endsWith($haystack, $needle)
    {
        return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }
}