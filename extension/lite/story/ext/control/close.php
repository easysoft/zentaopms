<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * 关闭需求。
     * Close the story.
     *
     * @param  int    $storyID
     * @param  string $from
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function close(int $storyID, string $from = '', string $storyType = 'story')
    {
        foreach($this->lang->{$storyType}->reasonList as $key => $value)
        {
            if(!in_array($key, array('done', 'duplicate', 'cancel'))) unset($this->lang->{$storyType}->reasonList[$key]);
        }

        return parent::close($storyID, $from, $storyType);
    }
}
