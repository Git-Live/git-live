<?php

class CompileFilterIterator extends \RecursiveFilterIterator
{
    public function accept()
    {
        $iterator = $this->getInnerIterator();
        if ($iterator->isDir()) {
            return true;
        }

        if (1 === preg_match('/\.php$/', $iterator->current())) {
            return true;
        } elseif (1 === preg_match('/\.po$/', $iterator->current())) {
            return true;
        }

        return false;
    }
}
