<?php
class scmUtils
{
    public $commitCommandRegx = '/\s*([a-z]+)\s+((?:build)|(?:story)|(?:task)|(?:bug))\s+#((?:\d|,)+)\s*/i';
    public $tagCommandRegx = '/build[\-_]#((?:\d|,)+)/i';

    /**
     * Parse the comment of git, extract object id list from it.
     *
     * @param  string    $comment
     * @param  array     $allCommands
     * @access public
     */
    public function parseComment($comment, &$allCommands)
    {
        $pattern = $this->commitCommandRegx;
        $matches = array();
        preg_match_all($pattern, $comment,$matches);

        if(count($matches) > 1 && count($matches[1]) > 0)
        {
            $i = 0;
            foreach($matches[1] as $action)
            {
                $action = $matches[1][$i];
                $entityType = $matches[2][$i];
                $entityIds = $matches[3][$i];

                $currArr = $allCommands[$entityType][$action];
                if (empty($currArr)) {
                    $currArr = [];
                }
                $newArr = explode(",", $entityIds);

                $allCommands[$entityType][$action] = array_keys(array_flip($currArr) + array_flip($newArr));

                $i++;
            }
        }
    }

    /**
     * Parse the tag, extract task list from it.
     *
     * @param  string    $comment
     * @param  array     $jobToBuild
     * @access public
     */
    public function parseTag($comment, &$jobToBuild)
    {
        $pattern = $this->tagCommandRegx;
        $matches = array();
        preg_match($pattern, $comment,$matches);

        if(count($matches) > 0)
        {
            $entityIds = $matches[1];

            if (empty($taskToBuild)) {
                $taskToBuild = [];
            }
            $newArr = explode(",", $entityIds);
            $jobToBuild = array_keys(array_flip($taskToBuild) + array_flip($newArr));
        }
    }
}
