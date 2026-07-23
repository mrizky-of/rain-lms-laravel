<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class QuestionOptionPolicy
{
    use AdminOnlyPolicy;
}
