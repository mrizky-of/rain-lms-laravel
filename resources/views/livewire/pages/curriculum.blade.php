<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Kurikulum Training
        </h1>

        <p class="mt-2 text-zinc-500">
            Modul training yang tersedia sesuai divisi Anda.
        </p>
    </div>

    <div class="flex flex-wrap gap-3">

        @foreach([
            'all' => 'Semua',
            'mandatory' => 'Mandatory',
            'basic_division' => 'Basic Division',
            'division_kpi' => 'Division KPI',
        ] as $value => $label)

            <button
                type="button"
                wire:click="$set('category', '{{ $value }}')"
                class="
                    rounded-xl px-4 py-2 text-sm font-medium
                    {{
                        $category === $value
                            ? 'bg-blue-600 text-white'
                            : 'border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900'
                    }}
                "
            >
                {{ $label }}
            </button>

        @endforeach

    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

        @forelse($modules as $module)

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{
                        strtoupper(
                            str_replace(
                                '_',
                                ' ',
                                $module->category
                            )
                        )
                    }}
                </span>

                <h2 class="mt-4 text-xl font-bold">
                    {{ $module->title }}
                </h2>

                <p class="mt-2 text-sm text-zinc-500">
                    {{ $module->description }}
                </p>

                <div class="mt-4 space-y-1 text-sm text-zinc-500">
                    <p>
                        Durasi:
                        {{ $module->duration_minutes }}
                        menit
                    </p>

                    <p>
                        Materi:
                        {{
                            count(
                                $module->material_files
                                ?? []
                            )
                        }}
                        file
                    </p>

                    <p>
                        Test:
                        {{ $module->quizzes->count() }}
                    </p>

                    <p>
                        Test:
                        {{ $module->quizzes->count() }}
                    </p>

                    <p class="font-semibold text-blue-600">
                        {{ $module->kpi_points }}
                        poin KPI
                    </p>
                </div>

                <a
                    href="{{ route('training.show', $module) }}"
                    wire:navigate
                    class="mt-6 inline-flex rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white"
                >
                    Mulai / Lanjutkan
                </a>

            </article>

        @empty

            <div class="col-span-full rounded-2xl border border-dashed border-zinc-300 p-12 text-center dark:border-zinc-700">

                <h2 class="text-lg font-semibold">
                    Belum ada modul
                </h2>

                <p class="mt-2 text-zinc-500">
                    Belum ada modul pada kategori ini.
                </p>

            </div>

        @endforelse

    </div>

</div>