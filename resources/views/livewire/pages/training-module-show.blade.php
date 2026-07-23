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

        <div class="mt-4 flex flex-wrap gap-3 text-sm">

            <span class="rounded-full bg-zinc-100 px-3 py-1 dark:bg-zinc-800">
                {{ $module->duration_minutes }}
                menit
            </span>

            <span class="rounded-full bg-blue-50 px-3 py-1 text-blue-700">
                {{ $module->kpi_points }}
                KPI
            </span>

            <span class="rounded-full bg-green-50 px-3 py-1 text-green-700">
                Passing:
                {{ $module->passing_score }}
            </span>

        </div>

    </div>

    <div class="space-y-6">

        @forelse(
            $module->lessons
            as $lesson
        )

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                <div class="flex items-start justify-between gap-4">

                    <div>

                        <p class="text-xs font-semibold uppercase text-zinc-400">
                            {{ $lesson->type }}
                        </p>

                        <h2 class="mt-1 text-xl font-semibold">
                            {{ $lesson->title }}
                        </h2>

                        @if($lesson->description)

                            <p class="mt-2 text-zinc-500">
                                {{ $lesson->description }}
                            </p>

                        @endif

                    </div>

                    @if(
                        in_array(
                            $lesson->id,
                            $completedLessonIds,
                            true
                        )
                    )

                        <span class="rounded-full bg-green-50 px-3 py-1 text-sm font-semibold text-green-700">
                            ✓ Selesai
                        </span>

                    @endif

                </div>

                @if($lesson->video_url)

                    <div class="mt-6 aspect-video overflow-hidden rounded-xl bg-black">

                        <iframe
                            src="{{ $lesson->video_url }}"
                            class="h-full w-full"
                            allowfullscreen
                        ></iframe>

                    </div>

                @endif

                @if($lesson->content)

                    <div class="mt-6 whitespace-pre-line text-zinc-700 dark:text-zinc-300">
                        {{ $lesson->content }}
                    </div>

                @endif

                @unless(
                    in_array(
                        $lesson->id,
                        $completedLessonIds,
                        true
                    )
                )

                    <button
                        type="button"
                        wire:click="completeLesson({{ $lesson->id }})"
                        class="mt-6 rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white"
                    >
                        Tandai Selesai
                    </button>

                @endunless

            </article>

        @empty

            <p class="text-zinc-500">
                Belum ada materi pada modul ini.
            </p>

        @endforelse

    </div>

    @if(
        $module
            ->quizzes
            ->isNotEmpty()
    )

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="text-xl font-semibold">
                Test Modul
            </h2>

            <p class="mt-2 text-zinc-500">
                Selesaikan test untuk mendapatkan nilai KPI dan sertifikat.
            </p>

            <a
                href="{{
                    route(
                        'quiz.take',
                        $module
                            ->quizzes
                            ->first()
                    )
                }}"
                wire:navigate
                class="mt-5 inline-flex rounded-xl bg-green-600 px-5 py-3 font-semibold text-white"
            >
                Mulai Test
            </a>

        </div>

    @endif

</div>