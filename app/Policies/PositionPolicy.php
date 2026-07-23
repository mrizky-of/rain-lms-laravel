<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class PositionPolicy
{
    use AdminOnlyPolicy;
}
