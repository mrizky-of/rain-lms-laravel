<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Standard Operating Procedures
        </h1>

        <p class="mt-2 text-zinc-500">
            SOP resmi RAIN Group.
        </p>
    </div>

    <div class="max-w-md">

        <label class="mb-2 block text-sm font-medium">
            Filter Divisi
        </label>

        <select
            wire:model.live="departmentId"
            class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
        >

            <option value="">
                Semua Divisi
            </option>

            @foreach(
                $departments
                as $department
            )

                <option value="{{ $department->id }}">
                    {{ $department->name }}
                </option>

            @endforeach

        </select>

    </div>

    <div class="space-y-4">

        @forelse(
            $sops
            as $sop
        )

            <details class="group rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                <summary class="cursor-pointer list-none">

                    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">

                        <div>

                            <p class="text-sm font-semibold text-blue-600">
                                {{ $sop->code }}
                            </p>

                            <h2 class="mt-1 text-lg font-bold">
                                {{ $sop->title }}
                            </h2>

                        </div>

                        <div class="text-sm text-zinc-500">

                            <p>
                                Versi:
                                {{ $sop->version }}
                            </p>

                            <p>
                                Divisi:
                                {{
                                    $sop
                                        ->department
                                        ?->name
                                    ?? 'Semua'
                                }}
                            </p>

                        </div>

                    </div>

                </summary>

                <div class="mt-6 border-t border-zinc-200 pt-6 dark:border-zinc-700">

                    <h3 class="font-semibold">
                        Tujuan
                    </h3>

                    <p class="mt-2 whitespace-pre-line text-zinc-600 dark:text-zinc-300">
                        {{ $sop->objective ?? '-' }}
                    </p>

                    <h3 class="mt-6 font-semibold">
                        Prosedur
                    </h3>

                    <p class="mt-2 whitespace-pre-line text-zinc-600 dark:text-zinc-300">
                        {{ $sop->procedure ?? '-' }}
                    </p>

                    <h3 class="mt-6 font-semibold">
                        Parameter / SLA / KPI
                    </h3>

                    <p class="mt-2 whitespace-pre-line text-zinc-600 dark:text-zinc-300">
                        {{ $sop->parameters ?? '-' }}
                    </p>

                    <div class="mt-6 text-sm text-zinc-500">

                        <p>
                            Owner:
                            {{ $sop->owner ?? '-' }}
                        </p>

                        <p>
                            Berlaku:
                            {{
                                $sop
                                    ->effective_date
                                    ?->format(
                                        'd M Y'
                                    )
                                ?? '-'
                            }}
                        </p>

                    </div>

                </div>

            </details>

        @empty

            <p class="text-zinc-500">
                Belum ada SOP pada filter ini.
            </p>

        @endforelse

    </div>

</div>