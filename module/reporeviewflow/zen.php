<?php
declare(strict_types=1);
/**
 * The zen file of reporeviewflow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     reporeviewflow
 * @link        https://www.zentao.net
 */
class reporeviewflowZen extends reporeviewflow
{
    /**
     * 构建审批流程定义。
     * Build review flow definition.
     *
     * @param  object $formData
     * @access protected
     * @return object
     */
    protected function buildDefinition(object $formData): object
    {
        $definition = new stdClass();
        $definition->ai = new stdClass();
        $definition->ai->enable    = zget($formData, 'aiReview') == 'enable';
        $definition->ai->approvals = new stdClass();
        $definition->ai->approvals->score = zget($formData, 'aiReviewScores', 0);

        $definition->reviewFlow = new stdClass();
        $definition->reviewFlow->approvals = new stdClass();
        $definition->reviewFlow->approvals->defaultReviewers   = explode(',', zget($formData, 'defaultReviewers', ''));
        $definition->reviewFlow->approvals->specifiedReviewers = explode(',', zget($formData, 'specifiedReviewers', ''));
        $definition->reviewFlow->approvals->minReviewers       = zget($formData, 'minReviewers', 0);
        $definition->reviewFlow->approvals->approvalID         = zget($formData, 'flow', 0);

        $definition->reviewFlow->issues = new stdClass();
        $definition->reviewFlow->issues->addressOption = zget($formData, 'addressOption', '');
        $definition->reviewFlow->issues->mandatoryType = zget($formData, 'addressOption') == 'specificMustBeSolved' ? explode(',', zget($formData, 'issueType', array())) : array();

        $definition->reviewFlow->newCommits = new stdClass();
        $definition->reviewFlow->newCommits->addressOption = zget($formData, 'newCommits', '');

        $definition->reviewFlow->merge = new stdClass();
        $definition->reviewFlow->merge->options     = explode(',', zget($formData, 'mergeOptions'));
        $definition->reviewFlow->merge->autoArchive = zget($formData, 'autoArchive') == 'enable';

        return $definition;
    }

}
