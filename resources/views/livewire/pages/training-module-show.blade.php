<div class="space-y-8">

    <div>

        <a
            href="{{ route('curriculum') }}"
            wire:navigate
            class="text-sm font-semibold text-blue-600"
        >
            ← Kembali ke Kurikulum
        </a>

        <h1 class="mt-4 text-3xl font-bold">
            {{ $module->title }}
        </h1>

        <p class="mt-2 text-zinc-500">
            {{ $module->description }}
        </p>

        <div class="mt-4 flex flex-wrap gap-3">

            <span class="rounded-full bg-zinc-100 px-3 py-1 text-sm">
                {{
                    $module
                        ->duration_minutes
                }}
                menit
            </span>

            <span class="rounded-full bg-blue-50 px-3 py-1 text-sm text-blue-700">
                {{
                    $module
                        ->kpi_points
                }}
                KPI
            </span>

            <span class="rounded-full bg-green-50 px-3 py-1 text-sm text-green-700">
                Passing:
                {{
                    $module
                        ->passing_score
                }}
            </span>

        </div>

    </div>

    <section class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-xl font-semibold">
                    Materi Training
                </h2>

                <p class="mt-1 text-sm text-zinc-500">
                    Pelajari seluruh materi sebelum mengerjakan test.
                </p>

            </div>

            <span class="font-semibold">
                {{
                    number_format(
                        (float)
                        $enrollment
                            ->progress_percentage,
                        0
                    )
                }}%
            </span>

        </div>

        <div class="mt-6 space-y-6">

            @forelse(
                $module->material_files ?? []
                as $index => $path
            )

                @php

                    $extension =
                        strtolower(
                            pathinfo(
                                $path,
                                PATHINFO_EXTENSION
                            )
                        );

                    $names =
                        $module
                            ->material_file_names
                        ?? [];

                    $displayName =
                        $names[$index]
                        ?? basename($path);

                @endphp

                <article class="rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">

                    <h3 class="font-semibold">
                        {{ $displayName }}
                    </h3>

                    @if(
                        in_array(
                            $extension,
                            [
                                'mp4',
                                'webm',
                                'mov',
                            ],
                            true
                        )
                    )

                        <video
                            controls
                            class="mt-4 w-full rounded-xl bg-black"
                        >
                            <source
                                src="{{
                                    asset(
                                        'storage/'
                                        . $path
                                    )
                                }}"
                            >
                        </video>

                    @elseif(
                        $extension === 'pdf'
                    )

                        <a
                            href="{{
                                asset(
                                    'storage/'
                                    . $path
                                )
                            }}"
                            target="_blank"
                            class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white"
                        >
                            Buka PDF
                        </a>

                    @else

                        <a
                            href="{{
                                asset(
                                    'storage/'
                                    . $path
                                )
                            }}"
                            target="_blank"
                            download
                            class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white"
                        >
                            Download Materi
                        </a>

                    @endif

                </article>

            @empty

                <div class="rounded-xl border border-dashed p-8 text-center text-zinc-500">
                    Belum ada file materi.
                </div>

            @endforelse

        </div>

        @if(
            ! $enrollment
                ->material_completed_at
            &&
            $enrollment
                ->status
            !== 'completed'
        )

            <button
                type="button"
                wire:click="completeMaterial"
                class="mt-6 rounded-xl bg-green-600 px-5 py-3 font-semibold text-white"
            >
                ✓ Saya Sudah Menyelesaikan Materi
            </button>

        @else

            <div class="mt-6 rounded-xl bg-green-50 p-4 font-semibold text-green-700">
                ✓ Materi sudah selesai
            </div>

        @endif

    </section>

    @if(
        $module
            ->quizzes
            ->isNotEmpty()
    )

        <section class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="text-xl font-semibold">
                Test
            </h2>

            <p class="mt-2 text-zinc-500">
                Test dapat dikerjakan setelah materi selesai.
            </p>

            @if(
                $enrollment
                    ->material_completed_at
                ||
                $enrollment
                    ->status
                === 'completed'
            )

                @foreach(
                    $module->quizzes
                    as $quiz
                )

                    <a
                        href="{{
                            route(
                                'quiz.take',
                                $quiz
                            )
                        }}"
                        wire:navigate
                        class="mt-5 mr-3 inline-flex rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white"
                    >
                        {{ $quiz->title }}
                    </a>

                @endforeach

            @else

                <div class="mt-5 rounded-xl bg-amber-50 p-4 text-amber-700">
                    Selesaikan materi terlebih dahulu.
                </div>

            @endif

        </section>

    @endif

</div>