<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class QuizPolicy
{
    use AdminOnlyPolicy;
}
