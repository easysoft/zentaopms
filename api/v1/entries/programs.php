<?php
class ProgramsEntry extends Entry 
{
    public function get()
    {
        $program = $this->loadController('program', 'browse');
        $program->browse();
    }
}
