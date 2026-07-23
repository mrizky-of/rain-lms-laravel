<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Kebijakan Perusahaan
        </h1>

        <p class="mt-2 text-zinc-500">
            Kebijakan resmi yang wajib diketahui dan dipatuhi seluruh karyawan.
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

    <div class="grid gap-6 lg:grid-cols-2">

        @forelse(
            $policies
            as $policy
        )

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                <div class="flex flex-wrap items-center gap-2">

                    @if($policy->category)

                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ strtoupper($policy->category) }}
                        </span>

                    @endif

                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs dark:bg-zinc-800">
                        Versi {{ $policy->version }}
                    </span>

                </div>

                <h2 class="mt-4 text-xl font-bold">
                    {{ $policy->title }}
                </h2>

                @if($policy->summary)

                    <p class="mt-3 text-zinc-500">
                        {{ $policy->summary }}
                    </p>

                @endif

                <div class="mt-5 whitespace-pre-line text-sm">
                    {{ $policy->content }}
                </div>

                @if($policy->legal_reference)

                    <p class="mt-5 text-sm text-zinc-500">
                        Referensi:
                        {{ $policy->legal_reference }}
                    </p>

                @endif

                @if(
                    $policy
                        ->acknowledgements
                        ->isNotEmpty()
                )

                    <div class="mt-6 rounded-xl bg-green-50 p-3 text-sm font-semibold text-green-700">
                        ✓ Sudah dipahami dan diakui
                    </div>

                @else

                    <button
                        type="button"
                        wire:click="acknowledge({{ $policy->id }})"
                        class="mt-6 rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white"
                    >
                        Saya Sudah Membaca & Memahami
                    </button>

                @endif

            </article>

        @empty

            <p class="text-zinc-500">
                Belum ada kebijakan aktif.
            </p>

        @endforelse

    </div>

</div>