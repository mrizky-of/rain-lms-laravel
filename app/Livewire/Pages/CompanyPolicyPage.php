<?php

namespace App\Livewire\Pages;

use App\Models\CompanyPolicy;
use App\Models\PolicyAcknowledgement;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kebijakan Perusahaan - RAIN LMS')]
class CompanyPolicyPage extends Component
{
    public function acknowledge(
        int $policyId
    ): void {
        $policy =
            CompanyPolicy::query()
                ->where(
                    'is_active',
                    true
                )
                ->findOrFail(
                    $policyId
                );

        PolicyAcknowledgement::updateOrCreate(
            [
                'company_policy_id' => $policy->id,

                'user_id' => auth()->id(),
            ],
            [
                'acknowledged_at' => now(),
            ]
        );

        session()->flash(
            'success',
            'Kebijakan telah Anda pahami dan akui.'
        );
    }

    public function render()
    {
        $userId =
            auth()->id();

        return view(
            'livewire.pages.company-policy-page',
            [
                'policies' => CompanyPolicy::query()
                    ->where(
                        'is_active',
                        true
                    )
                    ->with([
                        'acknowledgements' => fn ($query) => $query->where(
                            'user_id',
                            $userId
                        ),
                    ])
                    ->orderByDesc(
                        'effective_date'
                    )
                    ->get(),
            ]
        );
    }
}
