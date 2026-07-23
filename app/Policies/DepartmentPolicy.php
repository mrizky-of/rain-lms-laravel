<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class DepartmentPolicy
{
    use AdminOnlyPolicy;
}
