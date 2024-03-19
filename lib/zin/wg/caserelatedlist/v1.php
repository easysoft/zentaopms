<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'relatedlist' . DS . 'v1.php';

class caseRelatedList extends relatedList
{
    protected static array $defineProps = array
    (
        'case' => '?object'  // 当前用例。
    );

    protected function created()
    {
        global $app, $lang;

        $case = $this->prop('case', data('case'));
        if(!$case) return array();

        $data = array();
        $data['linkBugs'] = array
        (
            'title' => $lang->testcase->legendLinkBugs,
            'items' => array_filter(array_merge($case->toBugs, array($case->fromBug))),
            'url'   => hasPriv('bug', 'view') ? createLink('bug', 'view', 'bugID={id}') : false
        );

        $linkCases = array();
        foreach($case->linkCaseTitles as $caseID => $linkCaseTitle)
        {
            $linkCase = new \stdclass();
            $linkCase->id    = $caseID;
            $linkCase->title = $linkCaseTitle;

            $linkCases[] = $linkCase;
        }

        $data['linkCases'] = array
        (
            'title' => $lang->testcase->linkCase,
            'items' => $linkCases,
            'url'   => hasPriv('testcase', 'view') ? createLink('testcase', 'view', 'caseID={id}') : false
        );

        $this->setProp('data', $data);
    }
}
