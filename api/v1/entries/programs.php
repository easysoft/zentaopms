<?php
/**
 * 禅道API的programs资源类
 * 版本V1
 *
 * The programs entry point of zentaopms
 * Version 1
 */
class ProgramsEntry extends Entry 
{
    public function get()
    {
        $program = $this->loadController('program', 'browse');
        $program->browse();

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success')
        {
            $programs = $data->data->programs;
            $result   = array();
            foreach($programs as $program)
            {
                $result[] = $program;
            }
            return $this->send(200, array('programs' => $result));
        }
        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }
}
