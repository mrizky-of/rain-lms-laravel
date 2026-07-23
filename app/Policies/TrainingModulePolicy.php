<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class TrainingModulePolicy
{
    use AdminOnlyPolicy;
}
