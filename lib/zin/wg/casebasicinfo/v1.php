<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class caseBasicInfo extends wg
{
    protected static array $defineProps = array
    (
        'case' => '?object'   // 当前用例。
    );

    protected function getStage($case)
    {
        global $lang;

        $caseStage = array();
        if($case->stage)
        {
            foreach(explode(',', $case->stage) as $stage)
            {
                if(empty($stage)) continue;
                $caseStage[] = div(zget($lang->testcase->stageList, $stage));
            }
        }
        return div($caseStage);
    }

    protected function getModule($case): array
    {
        global $app;

        $modulePath  = $this->prop('modulePath', data('modulePath'));
        $caseModule  = $this->prop('caseModule', data('caseModule'));
        $isLibCase   = $this->prop('isLibCase',  data('isLibCase'));
        $tab         = $app->tab;
        $moduleItems = array();
        if(!empty($modulePath))
        {
            $canBrowseCaselib         = hasPriv('caselib', 'browse');
            $canBrowseTestCase        = hasPriv('testcase', 'browse');
            $canBrowseProjectTestCase = hasPriv('testcase', 'browse');

            if($caseModule->branch && isset($branches[$caseModule->branch]))
            {
                $moduleItems[] = $branches[$caseModule->branch];
                $moduleItems[] = icon('angle-right');
            }

            foreach($modulePath as $key => $module)
            {
                if($tab == 'qa' || $tab == 'ops')
                {
                    if($isLibCase)
                    {
                        $moduleItems[] = $canBrowseCaselib ? a(set::href(createLink('caselib', 'browse', "libID={$case->lib}&browseType=byModule&param={$module->id}")), $module->name) : $module->name;
                    }
                    else
                    {
                        $moduleItems[] = $canBrowseTestCase ? a(set::href(createLink('testcase', 'browse', "productID={$case->product}&branch={$module->branch}&browseType=byModule&param={$module->id}")), $module->name) : $module->name;
                    }
                }
                else if($tab == 'project')
                {
                    $moduleItems[] = $canBrowseProjectTestCase ? a(set::href(createLink('project', 'testcase', "projectID={$this->session->project}&productID=$case->product&branch=$module->branch&browseType=byModule&param=$module->id")), $module->name) : $module->name;
                }
                else
                {
                    $moduleItems[] = $module->name;
                }
                if(isset($modulePath[$key + 1])) $moduleItems[] = icon('angle-right');
            }

        }
        return empty($modulePath) ? array('/') : $moduleItems;
    }

    protected function getFromCase($case): array
    {
        if(!isset($case->linkCaseTitles)) $case->linkCaseTitles = array();

        $linkCaseTitles = array();
        foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
        {
            $linkCaseTitles[] = a
            (
                set::href(createLink('testcase', 'view', "caseID={$linkCaseID}", '', true)),
                setData(array('toggle' => 'modal')),
                "#{$linkCaseID} {$linkCaseTitle}"
            );
        }
        return $linkCaseTitles;
    }

    protected function getStory($case): array
    {
        global $app, $lang;

        $tab       = $app->tab;
        $storyText = isset($case->storyTitle) ? "#{$case->story}:{$case->storyTitle}" : '';
        $param     = $tab == 'project' ? "&version=0&projectID={$this->session->project}" : '';
        $story     = array();
        $story[]   = hasPriv('story', 'view') ? a(set::href(createLink('story', 'view', "storyID={$case->story}{$param}")), setData(array('toggle' => 'modal', 'size' => 'lg')), $storyText) : $storyText;
        if($case->story && $case->storyStatus == 'active' && $case->latestStoryVersion > $case->storyVersion)
        {
            $story[] = span
            (
                '(',
                $lang->story->changed,
                common::hasPriv('testcase', 'confirmStoryChange', $case) ? a
                (
                    set::href(createLink('testcase', 'confirmStoryChange', "caseID={$case->id}")),
                    setData('app', $tab),
                    setClass('mx-1 px-1 primary-pale'),
                    $lang->confirm
                ) : '',
                ')'
            );
        }
        return $story;
    }

    protected function getStatus($case): array
    {
        global $app, $lang;

        $from   = $this->prop('from', data('from'));
        $taskID = $this->prop('taskID', data('taskID'));

        $status = array();
        $status[] = $app->control->processStatus('testcase', $case);

        if($from == 'testtask' && $case->version > $case->currentVersion)
        {
            $status[] = span
            (
                set('title', $lang->testcase->fromTesttask),
                ' (',
                $lang->testcase->changed,
                hasPriv('testcase', 'confirmchange') ? a(setClass('btn size-xs primary-pale mx-1 ajax-submit'), set::href(createLink('testcase', 'confirmchange', "caseID=$case->id&taskID=$taskID")), $lang->testcase->sync) : '',
                ')'
            );
        }

        if(isset($case->fromCaseVersion) && $case->fromCaseVersion > $case->version && $from != 'testtask' && !empty($case->product))
        {
            $status[] = span
            (
                set('title', $lang->testcase->fromCaselib),
                ' (',
                $lang->testcase->changed,
                hasPriv('testcase', 'confirmLibcaseChange') ? a(setClass('btn size-xs primary-pale mx-1 ajax-submit'), set::href(createLink('testcase', 'confirmLibcaseChange', "caseID={$case->id}&libcaseID={$case->fromCaseID}")), $lang->testcase->sync) : '',
                hasPriv('testcase', 'ignoreLibcaseChange') ? a(setClass('btn size-xs primary-pale mx-1 ajax-submit'), set::href(createLink('testcase', 'ignoreLibcaseChange', "caseID={$case->id}")), $lang->testcase->ignore) : '',
                ')'
            );
        }
        return $status;
    }

    protected function getItems(): array
    {
        global $lang;

        $case = $this->prop('case', data('case'));
        if(!$case) return array();

        $product     = $this->prop('product',    data('product'));
        $branchName  = $this->prop('branchName', data('branchName'));
        $libName     = $this->prop('libName',    data('libName'));
        $branchLabel = sprintf($lang->product->branch, $lang->product->branchName[$product->type]);

        $items = array();
        if($isLibCase)
        {
            $items[$lang->testcase->fromCase] = array('children' => wg($this->getFromCase($case)));
            $items[$lang->testcase->lib]      = hasPriv('caselib', 'browse') ? array('control' => 'link', 'url' => createLink('caselib', 'browse', "libID={$case->lib}"), 'text' => $libName) : $libName;
        }
        else
        {
            if($case->product && !$product->shadow) $items[$lang->testcase->product] = hasPriv('product', 'view') ? array('control' => 'link', 'url' => createLink('product', 'view', "productID={$case->product}"), 'text' => $product->name) : $product->name;
            if($case->branch && $product->type != 'normal') $items[$branchLabel] = hasPriv('testcase', 'browse') ? array('control' => 'link', 'url' => createLink('testcase', 'browse', "productID={$case->product}&branch={$case->branch}"), 'text' => $branchName) : $branchName;
            $items[$lang->testcase->module] = array('children' => wg($this->getModule($case)));
            $items[$lang->testcase->story]  = array('children' => wg($this->getStory($case)));
        }

        $items[$lang->testcase->type]   = zget($lang->case->typeList, $case->type);
        $items[$lang->testcase->stage]  = array('children' => wg($this->getStage($case)));
        $items[$lang->testcase->pri]    = array('control' => 'pri', 'pri' => $case->pri, 'text' => $lang->case->priList);
        $items[$lang->testcase->status] = array('children' => wg($this->getStatus($case)));
        if(!$isLibCase)
        {
            $items[$lang->testcase->lastRunDate]   = !helper::isZeroDate($case->lastRunDate) ? $case->lastRunDate : '';
            $items[$lang->testcase->lastRunResult] = $case->lastRunResult ? $lang->testcase->resultList[$case->lastRunResult] : $lang->testcase->unexecuted;
        }
        $items[$lang->testcase->keywords] = $case->keywords;

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('case-basec-info'),
            set::items($this->getItems())
        );
    }
}
