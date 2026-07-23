<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Laporan KPI Training
        </h1>

        <p class="mt-2 text-zinc-500">
            Ringkasan capaian training karyawan.
        </p>
    </div>

    <div class="grid gap-6 md:grid-cols-3">

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <p class="text-sm text-zinc-500">
                Total Karyawan
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $users->count() }}
            </p>

        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <p class="text-sm text-zinc-500">
                Average Completion
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ number_format($averageCompletion, 1) }}%
            </p>

        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <p class="text-sm text-zinc-500">
                Average KPI
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ number_format($averageKpi, 1) }}
            </p>

        </div>

    </div>

    <div class="overflow-x-auto rounded-2xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

        <table class="min-w-full">

            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800">

                <tr>
                    <th class="px-5 py-4 text-left">
                        Nama
                    </th>

                    <th class="px-5 py-4 text-left">
                        Divisi
                    </th>

                    <th class="px-5 py-4 text-left">
                        Jabatan
                    </th>

                    <th class="px-5 py-4 text-left">
                        Selesai
                    </th>

                    <th class="px-5 py-4 text-left">
                        Completion
                    </th>

                    <th class="px-5 py-4 text-left">
                        KPI
                    </th>
                </tr>

            </thead>

            <tbody>

                @forelse(
                    $users
                    as $user
                )

                    @php
                        $completion =
                            $user->total_modules > 0
                                ? (
                                    $user->completed_modules
                                    / $user->total_modules
                                ) * 100
                                : 0;
                    @endphp

                    <tr class="border-b border-zinc-100 dark:border-zinc-800">

                        <td class="px-5 py-4 font-semibold">
                            {{ $user->name }}
                        </td>

                        <td class="px-5 py-4">
                            {{
                                $user
                                    ->department
                                    ?->name
                                ?? '-'
                            }}
                        </td>

                        <td class="px-5 py-4">
                            {{
                                $user
                                    ->position
                                    ?->name
                                ?? '-'
                            }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $user->completed_modules }}
                            /
                            {{ $user->total_modules }}
                        </td>

                        <td class="px-5 py-4">
                            {{
                                number_format(
                                    $completion,
                                    1
                                )
                            }}%
                        </td>

                        <td class="px-5 py-4 font-semibold">
                            {{ $user->kpi_total ?? 0 }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="px-5 py-12 text-center text-zinc-500"
                        >
                            Belum ada data KPI.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>