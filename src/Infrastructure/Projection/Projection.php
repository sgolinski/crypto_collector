<?php

namespace App;

interface Projection
{
    public function listensTo();
    public function project($event);
}
