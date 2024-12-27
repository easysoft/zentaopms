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
    }
}
