<?php
class feedbackModel extends model
{
    public function getFeedbackPairs($type)
    {
        return array('admin' => 'Admin', 'user1' => 'User1');
    }
}
