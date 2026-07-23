<div class="mx-auto max-w-4xl space-y-8">

    <div>

        <h1 class="text-3xl font-bold">
            {{ $quiz->title }}
        </h1>

        <p class="mt-2 text-zinc-500">
            Modul:
            {{ $quiz->trainingModule?->title }}
        </p>

        <p class="text-sm text-zinc-500">
            Batas waktu:
            {{ $quiz->time_limit_minutes }}
            menit
        </p>

    </div>

    @error('quiz')

        <div class="rounded-xl bg-red-50 p-4 text-red-700">
            {{ $message }}
        </div>

    @enderror

    @if(
        $resultScore !== null
    )

        <div
            class="
                rounded-2xl p-8
                {{
                    $passed
                        ? 'bg-green-50 text-green-900'
                        : 'bg-red-50 text-red-900'
                }}
            "
        >

            <h2 class="text-2xl font-bold">

                @if($passed)
                    Selamat, Anda Lulus!
                @else
                    Anda Belum Lulus
                @endif

            </h2>

            <p class="mt-3 text-4xl font-bold">
                {{ number_format($resultScore, 0) }}
            </p>

            <p class="mt-1">
                Passing score:
                {{ $quiz->trainingModule?->passing_score }}
            </p>

            <a
                href="{{
                    route(
                        'training.show',
                        $quiz->trainingModule
                    )
                }}"
                wire:navigate
                class="mt-6 inline-block rounded-xl bg-zinc-900 px-5 py-3 font-semibold text-white"
            >
                Kembali ke Modul
            </a>

        </div>

    @else

        <form
            wire:submit="submit"
            class="space-y-6"
        >

            @foreach(
                $quiz->questions
                as $index => $question
            )

                <article class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                    <p class="text-sm text-zinc-500">
                        Soal {{ $index + 1 }}
                    </p>

                    <h2 class="mt-2 text-lg font-semibold">
                        {{ $question->question_text }}
                    </h2>

                    <div class="mt-5 space-y-3">

                        @foreach(
                            $question->options
                            as $option
                        )

                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-zinc-200 p-4 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">

                                <input
                                    type="radio"
                                    wire:model="answers.{{ $question->id }}"
                                    value="{{ $option->id }}"
                                    class="mt-1"
                                >

                                <span>
                                    {{ $option->option_text }}
                                </span>

                            </label>

                        @endforeach

                    </div>

                    @error(
                        'answers.'
                        . $question->id
                    )

                        <p class="mt-3 text-sm text-red-600">
                            Pilih satu jawaban.
                        </p>

                    @enderror

                </article>

            @endforeach

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white"
            >
                Submit Test
            </button>

        </form>

    @endif

</div>