<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class CompanyContentPolicy
{
    use AdminOnlyPolicy;
}
