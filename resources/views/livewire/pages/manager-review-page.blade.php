<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Review Atasan
        </h1>

        <p class="mt-2 text-zinc-500">
            Monitor dan berikan penilaian training anggota tim.
        </p>
    </div>

    @if(
        session()->has(
            'success'
        )
    )

        <div class="rounded-xl bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif

    <div class="space-y-6">

        @forelse(
            $employees
            as $employee
        )

            @php
                $kpi =
                    $employee
                        ->kpiLedgers
                        ->sum('points');

                $total =
                    $employee
                        ->enrollments
                        ->count();

                $completed =
                    $employee
                        ->enrollments
                        ->where(
                            'status',
                            'completed'
                        )
                        ->count();
            @endphp

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                <div class="grid gap-8 xl:grid-cols-3">

                    <div>

                        <h2 class="text-xl font-bold">
                            {{ $employee->name }}
                        </h2>

                        <p class="text-zinc-500">
                            {{
                                $employee
                                    ->position
                                    ?->name
                                ?? '-'
                            }}
                            —
                            {{
                                $employee
                                    ->department
                                    ?->name
                                ?? '-'
                            }}
                        </p>

                        <div class="mt-5 grid grid-cols-2 gap-4">

                            <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800">

                                <p class="text-sm text-zinc-500">
                                    KPI
                                </p>

                                <p class="text-2xl font-bold">
                                    {{ $kpi }}
                                </p>

                            </div>

                            <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800">

                                <p class="text-sm text-zinc-500">
                                    Modul
                                </p>

                                <p class="text-2xl font-bold">
                                    {{ $completed }}/{{ $total }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <div>

                        <h3 class="font-semibold">
                            Progress Modul
                        </h3>

                        <div class="mt-4 space-y-3">

                            @forelse(
                                $employee->enrollments
                                as $enrollment
                            )

                                <div class="rounded-xl border border-zinc-200 p-3 dark:border-zinc-700">

                                    <p class="font-medium">
                                        {{
                                            $enrollment
                                                ->trainingModule
                                                ?->title
                                            ?? 'Training'
                                        }}
                                    </p>

                                    <p class="mt-1 text-sm text-zinc-500">
                                        {{
                                            number_format(
                                                (float)
                                                $enrollment
                                                    ->progress_percentage,
                                                0
                                            )
                                        }}%
                                        —
                                        {{
                                            ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $enrollment->status
                                                )
                                            )
                                        }}
                                    </p>

                                </div>

                            @empty

                                <p class="text-sm text-zinc-500">
                                    Belum ada training.
                                </p>

                            @endforelse

                        </div>

                    </div>

                    <form
                        wire:submit="saveReview({{ $employee->id }})"
                        class="space-y-4"
                    >

                        <div>

                            <label class="mb-2 block text-sm font-medium">
                                Score Review
                            </label>

                            <input
                                type="number"
                                min="0"
                                max="100"
                                wire:model="scores.{{ $employee->id }}"
                                class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                            >

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-medium">
                                Status
                            </label>

                            <select
                                wire:model="statuses.{{ $employee->id }}"
                                class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                            >

                                <option value="review">
                                    Review
                                </option>

                                <option value="lulus">
                                    Lulus
                                </option>

                                <option value="proses">
                                    Proses
                                </option>

                                <option value="perhatian">
                                    Perlu Perhatian
                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-medium">
                                Catatan Atasan
                            </label>

                            <textarea
                                rows="4"
                                wire:model="notes.{{ $employee->id }}"
                                class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                            ></textarea>

                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white"
                        >
                            Simpan Review
                        </button>

                    </form>

                </div>

            </article>

        @empty

            <p class="text-zinc-500">
                Belum ada anggota tim.
            </p>

        @endforelse

    </div>

</div>