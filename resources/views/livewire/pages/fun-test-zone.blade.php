<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold">
            Fun Test Zone 🎮
        </h1>

        <p class="mt-2 text-zinc-500">
            Belajar sambil bermain — pilih mode test yang paling seru.
        </p>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

        @foreach(
            $modes
            as $value => $label
        )

            <button
                type="button"
                wire:click="$set('mode', '{{ $value }}')"
                class="
                    rounded-xl border p-4 text-left font-semibold
                    {{
                        $mode === $value
                            ? 'border-blue-600 bg-blue-50 text-blue-700'
                            : 'border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900'
                    }}
                "
            >
                {{ $label }}
            </button>

        @endforeach

    </div>

    @if(
        $score !== null
    )

        <div class="rounded-2xl bg-blue-50 p-6 text-blue-900">

            <p class="text-sm font-semibold">
                Hasil
            </p>

            <p class="mt-2 text-4xl font-bold">
                {{ $score }}
            </p>

        </div>

    @endif

    <div class="space-y-6">

        @forelse(
            $questions
            as $question
        )

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                <h2 class="font-semibold">
                    {{ $question->prompt }}
                </h2>

                @if(
                    $mode === 'true_false'
                )

                    <div class="mt-5 flex gap-4">

                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-zinc-200 px-5 py-3 dark:border-zinc-700">

                            <input
                                type="radio"
                                wire:model="answers.{{ $question->id }}"
                                value="true"
                            >

                            Benar

                        </label>

                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-zinc-200 px-5 py-3 dark:border-zinc-700">

                            <input
                                type="radio"
                                wire:model="answers.{{ $question->id }}"
                                value="false"
                            >

                            Salah

                        </label>

                    </div>

                @else

                    <input
                        type="text"
                        wire:model="answers.{{ $question->id }}"
                        placeholder="Masukkan jawaban"
                        class="mt-5 w-full rounded-xl border border-zinc-300 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900"
                    >

                @endif

            </article>

        @empty

            <div class="rounded-2xl border border-dashed border-zinc-300 p-12 text-center dark:border-zinc-700">

                <p class="text-zinc-500">
                    Belum ada pertanyaan untuk mode ini.
                </p>

            </div>

        @endforelse

    </div>

    @if(
        $questions->isNotEmpty()
    )

        <div class="flex gap-3">

            <button
                type="button"
                wire:click="check"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white"
            >
                Periksa Jawaban
            </button>

            <button
                type="button"
                wire:click="resetTest"
                class="rounded-xl border border-zinc-300 px-5 py-3 font-semibold dark:border-zinc-700"
            >
                Reset
            </button>

        </div>

    @endif

</div>