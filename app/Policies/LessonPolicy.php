<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class LessonPolicy
{
    use AdminOnlyPolicy;
}
