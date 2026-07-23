<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Assignment Management
        </h1>

        <p class="mt-2 text-zinc-500">
            Berikan training kepada anggota tim dan monitor status assignment.
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

    <div class="grid gap-8 xl:grid-cols-3">

        <section class="xl:col-span-2 rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="text-xl font-semibold">
                Berikan Assignment
            </h2>

            <form
                wire:submit="assign"
                class="mt-6 space-y-5"
            >

                <div>

                    <label class="mb-2 block text-sm font-medium">
                        Karyawan
                    </label>

                    <select
                        wire:model="employeeId"
                        class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                    >

                        <option value="">
                            Pilih karyawan
                        </option>

                        @foreach(
                            $employees
                            as $employee
                        )

                            <option value="{{ $employee->id }}">
                                {{ $employee->name }}
                                —
                                {{
                                    $employee
                                        ->department
                                        ?->name
                                    ?? '-'
                                }}
                            </option>

                        @endforeach

                    </select>

                    @error('employeeId')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="mb-2 block text-sm font-medium">
                        Modul Training
                    </label>

                    <select
                        wire:model="moduleId"
                        class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                    >

                        <option value="">
                            Pilih modul
                        </option>

                        @foreach(
                            $modules
                            as $module
                        )

                            <option value="{{ $module->id }}">
                                {{ $module->title }}
                            </option>

                        @endforeach

                    </select>

                    @error('moduleId')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="mb-2 block text-sm font-medium">
                        Deadline
                    </label>

                    <input
                        type="datetime-local"
                        wire:model="dueAt"
                        class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                    >

                    @error('dueAt')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="mb-2 block text-sm font-medium">
                        Catatan
                    </label>

                    <textarea
                        wire:model="notes"
                        rows="4"
                        class="w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                    ></textarea>

                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white"
                >
                    Assign Training
                </button>

            </form>

        </section>

        <aside class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="text-xl font-semibold">
                Assignment Aktif
            </h2>

            <div class="mt-6 space-y-4">

                @forelse(
                    $assignments
                    as $assignment
                )

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">

                        <p class="font-semibold">
                            {{ $assignment->user?->name }}
                        </p>

                        <p class="mt-1 text-sm text-zinc-500">
                            {{
                                $assignment
                                    ->trainingModule
                                    ?->title
                            }}
                        </p>

                        <p class="mt-2 text-sm">
                            Status:
                            <strong>
                                {{
                                    ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $assignment->status
                                        )
                                    )
                                }}
                            </strong>
                        </p>

                        @if($assignment->due_at)

                            <p class="mt-1 text-sm text-red-600">
                                Deadline:
                                {{
                                    $assignment
                                        ->due_at
                                        ->format(
                                            'd M Y H:i'
                                        )
                                }}
                            </p>

                        @endif

                    </div>

                @empty

                    <p class="text-zinc-500">
                        Belum ada assignment.
                    </p>

                @endforelse

            </div>

        </aside>

    </div>

</div>