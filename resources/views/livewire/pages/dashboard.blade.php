<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">
            Selamat datang, {{ auth()->user()->name }}
        </h1>

        <p class="mt-2 text-zinc-500 dark:text-zinc-400">
            Pantau progress training dan pencapaian KPI Anda.
        </p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">
                Total Modul
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $totalModules }}
            </p>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">
                Modul Selesai
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $completedModules }}
            </p>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">
                Skor KPI
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $kpiScore }}
            </p>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">
                Sertifikat
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $certificates }}
            </p>
        </div>

    </div>

    <div class="grid gap-6 xl:grid-cols-3">

        <section class="xl:col-span-2 rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-xl font-semibold">
                        Kurikulum Aktif
                    </h2>

                    <p class="text-sm text-zinc-500">
                        Training yang sedang Anda kerjakan.
                    </p>
                </div>

                <a
                    href="{{ route('curriculum') }}"
                    wire:navigate
                    class="text-sm font-semibold text-blue-600"
                >
                    Lihat Semua
                </a>

            </div>

            <div class="mt-6 space-y-4">

                @forelse(
                    $activeEnrollments
                    as $enrollment
                )

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">

                        <div class="flex justify-between gap-4">

                            <div class="min-w-0 flex-1">

                                <h3 class="font-semibold">
                                    {{
                                        $enrollment
                                            ->trainingModule
                                            ?->title
                                        ?? 'Modul'
                                    }}
                                </h3>

                                <p class="mt-1 text-sm text-zinc-500">
                                    Progress
                                    {{
                                        number_format(
                                            (float)
                                            $enrollment
                                                ->progress_percentage,
                                            0
                                        )
                                    }}%
                                </p>

                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">

                                    <div
                                        class="h-full rounded-full bg-blue-600"
                                        style="width: {{
                                            min(
                                                100,
                                                max(
                                                    0,
                                                    (float)
                                                    $enrollment
                                                        ->progress_percentage
                                                )
                                            )
                                        }}%"
                                    ></div>

                                </div>

                            </div>

                            @if(
                                $enrollment
                                    ->trainingModule
                            )

                                <a
                                    href="{{
                                        route(
                                            'training.show',
                                            $enrollment
                                                ->trainingModule
                                        )
                                    }}"
                                    wire:navigate
                                    class="self-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white"
                                >
                                    Lanjutkan
                                </a>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="rounded-xl border border-dashed border-zinc-300 p-8 text-center dark:border-zinc-700">

                        <p class="text-zinc-500">
                            Belum ada training aktif.
                        </p>

                        <a
                            href="{{ route('curriculum') }}"
                            wire:navigate
                            class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 text-white"
                        >
                            Lihat Kurikulum
                        </a>

                    </div>

                @endforelse

            </div>

        </section>

        <section class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="text-xl font-semibold">
                Deadline
            </h2>

            <div class="mt-6 space-y-4">

                @forelse(
                    $deadlines
                    as $assignment
                )

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">

                        <p class="font-medium">
                            {{
                                $assignment
                                    ->trainingModule
                                    ?->title
                                ?? 'Training'
                            }}
                        </p>

                        <p class="mt-2 text-sm text-zinc-500">
                            Deadline
                        </p>

                        <p class="font-semibold text-red-600">
                            {{
                                $assignment
                                    ->due_at
                                    ?->format(
                                        'd M Y H:i'
                                    )
                                ?? '-'
                            }}
                        </p>

                    </div>

                @empty

                    <p class="text-sm text-zinc-500">
                        Tidak ada deadline aktif.
                    </p>

                @endforelse

            </div>

        </section>

    </div>

</div>