<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Kontributor Modul
        </h1>

        <p class="mt-2 text-zinc-500">
            Tim penyusun konten training RAIN Group LMS.
        </p>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

        @forelse(
            $contributors
            as $contributor
        )

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                <div class="flex items-center gap-4">

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 font-bold text-white">
                        {{
                            $contributor
                                ->user
                                ->initials()
                        }}
                    </div>

                    <div>

                        <h2 class="font-bold">
                            {{
                                $contributor
                                    ->user
                                    ->name
                            }}
                        </h2>

                        @if($contributor->nickname)

                            <p class="text-sm text-zinc-500">
                                "{{ $contributor->nickname }}"
                            </p>

                        @endif

                        <p class="text-sm text-zinc-500">
                            {{
                                $contributor
                                    ->user
                                    ->position
                                    ?->name
                                ?? '-'
                            }}
                        </p>

                    </div>

                </div>

                <div class="mt-5 flex flex-wrap gap-2">

                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs dark:bg-zinc-800">
                        {{
                            $contributor
                                ->user
                                ->department
                                ?->name
                            ?? '-'
                        }}
                    </span>

                    <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs text-yellow-700">
                        Rating:
                        {{ $contributor->rating }}
                    </span>

                </div>

                @if($contributor->bio)

                    <p class="mt-5 text-sm text-zinc-500">
                        {{ $contributor->bio }}
                    </p>

                @endif

                @if($contributor->expertise)

                    <p class="mt-4 text-sm">
                        <strong>
                            Keahlian:
                        </strong>
                        {{ $contributor->expertise }}
                    </p>

                @endif

                <div class="mt-6">

                    <h3 class="font-semibold">
                        Modul Disusun
                    </h3>

                    <ul class="mt-3 space-y-2 text-sm text-zinc-500">

                        @forelse(
                            $contributor->trainingModules
                            as $module
                        )

                            <li>
                                • {{ $module->title }}
                            </li>

                        @empty

                            <li>
                                Belum ada modul.
                            </li>

                        @endforelse

                    </ul>

                </div>

            </article>

        @empty

            <p class="text-zinc-500">
                Belum ada data kontributor.
            </p>

        @endforelse

    </div>

</div>