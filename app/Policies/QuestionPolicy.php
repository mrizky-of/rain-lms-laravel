<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class QuestionPolicy
{
    use AdminOnlyPolicy;
}
