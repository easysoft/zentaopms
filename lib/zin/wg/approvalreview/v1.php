<?php
declare(strict_types=1);

namespace zin;
class approvalReview extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'nodes: array',  // 审批节点。
    );

    protected function build()
    {
        global $lang;

        list($nodes, $users) = $this->prop(array('nodes', 'users'));
        $content = array();
        if(empty($nodes))
        {
            $content[] = div
            (
                setClass('h-8 center'),
                span($lang->noData)
            );
        }
        else
        {
            $nodeTrs = array();
            foreach($nodes as $node)
            {
                $rangeUsers = $users;
                if(isset($node['range']['reviewer']))
                {
                    $rangeUsers = array();
                    foreach($node['range']['reviewer'] as $range) $rangeUsers[$range] = zget($users, $range, array());
                }

                $reviewers = array();
                foreach(array('appointees', 'role', 'position', 'self', 'upLevel', 'superior', 'superiorList', 'productRole', 'projectRole', 'executionRole') as $key)
                {
                    if(isset($node[$key]['reviewer']))
                    {
                        foreach($node[$key]['reviewer'] as $reviewer) $reviewers[$reviewer] = zget($users, $reviewer);
                    }
                }

                $ccers = array();
                foreach(array('appointees', 'role', 'position', 'upLevel', 'superior') as $key)
                {
                    if(isset($node[$key]['ccer']))
                    {
                        foreach($node[$key]['ccer'] as $ccer) $ccers[$ccer] = zget($users, $ccer);
                    }
                }
                $nodeTrs[] = h::tr
                (
                    h::td
                    (
                        setClass('text-center'),
                        $node['title'],
                        formHidden('ids[]', $node['id'])
                    ),
                    h::td
                    (
                        setClass('text-center'),
                        in_array('reviewer', $node['types']) ? div
                        (
                            picker
                            (
                                set::id("reviewer{$node['id']}"),
                                set::name('reviewer[' . $node['id'] . '][]'),
                                set::items(array_diff(array_intersect_key($users, $rangeUsers), $reviewers)),
                                set::multiple(true)
                            ),
                            $reviewers ? div
                            (
                                setClass('otherReviewer mt-2.5'),
                                $lang->approval->otherReviewer . implode(',', $reviewers)
                            ) : null
                        ) : div
                        (
                            formHidden('reviewer[' . $node['id'] . '][]', ''),
                            $reviewers ? implode(',', $reviewers) : null
                        )
                    ),
                    h::td
                    (
                        setClass('text-center'),
                        in_array('ccer', $node['types']) ? div
                        (
                            picker
                            (
                                set::name('ccer[' . $node['id'] . '][]'),
                                set::items(array_diff(array_intersect_key($users, $rangeUsers), $ccers)),
                                set::multiple(true)
                            ),
                            $ccers ? div
                            (
                                setClass('otherCcer mt-2.5'),
                                $lang->approval->otherCcer . implode(',', $ccers)
                            ) : null
                        ) : div
                        (
                            formHidden('ccer[' . $node['id'] . '][]', ''),
                            $ccers ? implode(',', $ccers) : null
                        )
                    )
                );
            }
            $content[] = h::table
            (
                setClass('table bordered'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            setClass('text-center'),
                            width('1/5'),
                            $lang->approval->node
                        ),
                        h::th
                        (
                            setClass('text-center'),
                            width('2/5'),
                            $lang->approval->reviewer
                        ),
                        h::th
                        (
                            setClass('text-center'),
                            width('2/5'),
                            $lang->approval->ccer
                        )
                    )
                ),
                h::tbody
                (
                    $nodeTrs
                )
            );
        }
        return div
        (
            setID('reviewerBox'),
            setStyle('width', '100%'),
            $content
        );
    }
}
