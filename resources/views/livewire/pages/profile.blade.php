<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Profil Saya
        </h1>

        <p class="mt-2 text-zinc-500">
            Data training dan pencapaian KPI Anda.
        </p>
    </div>

    <section class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

        <div class="flex items-center gap-4">

            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-xl font-bold text-white">
                {{ $user->initials() }}
            </div>

            <div>

                <h2 class="text-2xl font-bold">
                    {{ $user->name }}
                </h2>

                <p class="text-zinc-500">
                    {{
                        $user
                            ->position
                            ?->name
                        ?? '-'
                    }}
                    —
                    {{
                        $user
                            ->department
                            ?->name
                        ?? '-'
                    }}
                </p>

            </div>

        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <p class="text-sm text-zinc-500">
                    NIK
                </p>

                <p class="font-semibold">
                    {{ $user->nik ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-zinc-500">
                    Email
                </p>

                <p class="font-semibold">
                    {{ $user->email }}
                </p>
            </div>

            <div>
                <p class="text-sm text-zinc-500">
                    Semester
                </p>

                <p class="font-semibold">
                    {{ $user->semester }}
                </p>
            </div>

            <div>
                <p class="text-sm text-zinc-500">
                    Status
                </p>

                <p class="font-semibold">
                    {{ ucfirst($user->status) }}
                </p>
            </div>

        </div>

    </section>

    <div class="grid gap-6 md:grid-cols-3">

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-zinc-500">
                Total KPI
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $kpi }}
            </p>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-zinc-500">
                Modul Selesai
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $completed }}
            </p>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-zinc-500">
                Sertifikat
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $certificates->count() }}
            </p>
        </div>

    </div>

    <section class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

        <h2 class="text-xl font-semibold">
            Sertifikat & Pencapaian
        </h2>

        <div class="mt-6 space-y-4">

            @forelse(
                $certificates
                as $certificate
            )

                <div class="flex flex-col justify-between gap-3 rounded-xl border border-zinc-200 p-4 sm:flex-row sm:items-center dark:border-zinc-700">

                    <div>
                        <p class="font-semibold">
                            {{
                                $certificate
                                    ->trainingModule
                                    ?->title
                                ?? 'Training'
                            }}
                        </p>

                        <p class="text-sm text-zinc-500">
                            {{
                                $certificate
                                    ->certificate_number
                            }}
                        </p>
                    </div>

                    <p class="text-sm text-zinc-500">
                        {{
                            $certificate
                                ->issued_at
                                ?->format(
                                    'd M Y'
                                )
                        }}
                    </p>

                </div>

            @empty

                <p class="text-zinc-500">
                    Belum ada sertifikat.
                </p>

            @endforelse

        </div>

    </section>

</div>