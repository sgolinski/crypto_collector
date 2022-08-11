<?php

namespace App\Infrastructure\Projection;

interface Projection
{
    public function listensTo();
    public function project($event);
}
