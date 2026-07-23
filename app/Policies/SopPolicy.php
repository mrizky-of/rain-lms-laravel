<?php

namespace App\Policies;

use App\Policies\Concerns\AdminOnlyPolicy;

class SopPolicy
{
    use AdminOnlyPolicy;
}
