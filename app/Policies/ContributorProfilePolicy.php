<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class ContributorProfilePolicy
{
    use AdminOnlyPolicy;
}
