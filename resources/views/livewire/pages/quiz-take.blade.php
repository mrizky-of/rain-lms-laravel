<div class="mx-auto max-w-4xl space-y-8">

    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <section>

        <h1 class="text-3xl font-bold">
            {{ $quiz->title }}
        </h1>

        <p class="mt-2 text-zinc-500">

            {{
                $quiz
                    ->trainingModule
                    ?->title
            }}

        </p>

        <div class="mt-3 flex flex-wrap gap-3 text-sm text-zinc-500">

            <span>

                {{
                    $quiz
                        ->questions
                        ->count()
                }}

                soal

            </span>

            <span>•</span>

            <span>

                {{
                    $quiz
                        ->time_limit_minutes
                }}

                menit

            </span>

            <span>•</span>

            <span>

                Passing Score:

                {{
                    $quiz
                        ->trainingModule
                        ?->passing_score
                    ?? 75
                }}

            </span>

        </div>

    </section>

    {{-- ============================================================= --}}
    {{-- RESULT --}}
    {{-- ============================================================= --}}

    @if($submitted)

        {{-- ========================================================= --}}
        {{-- PENDING ESSAY REVIEW --}}
        {{-- ========================================================= --}}

        @if($pendingReview)

            <section
                class="
                    rounded-2xl
                    border
                    border-amber-200
                    bg-amber-50
                    p-8
                    text-amber-900
                    dark:border-amber-900
                    dark:bg-amber-950/30
                    dark:text-amber-200
                "
            >

                <h2 class="text-2xl font-bold">
                    Menunggu Review
                </h2>

                <p class="mt-3">

                    Quiz ini memiliki soal essay.

                    Jawaban essay perlu diperiksa
                    terlebih dahulu sebelum nilai
                    final diterbitkan.

                </p>

                <p class="mt-4">

                    Skor sementara Multiple Choice:

                    <strong>

                        {{
                            number_format(
                                $resultScore
                                ?? 0,
                                0
                            )
                        }}

                    </strong>

                </p>

            </section>

        @else

            {{-- ===================================================== --}}
            {{-- FINAL RESULT --}}
            {{-- ===================================================== --}}

            <section
                class="
                    rounded-2xl
                    border
                    p-8

                    {{
                        $passed
                            ? '
                                border-green-200
                                bg-green-50
                                text-green-900
                                dark:border-green-900
                                dark:bg-green-950/30
                                dark:text-green-200
                            '
                            : '
                                border-red-200
                                bg-red-50
                                text-red-900
                                dark:border-red-900
                                dark:bg-red-950/30
                                dark:text-red-200
                            '
                    }}
                "
            >

                <h2 class="text-2xl font-bold">

                    {{
                        $passed
                            ? 'Selamat, Anda Lulus!'
                            : 'Anda Belum Lulus'
                    }}

                </h2>

                <div class="mt-6">

                    <p class="text-sm opacity-70">
                        Nilai Akhir
                    </p>

                    <p class="mt-1 text-6xl font-bold">

                        {{
                            number_format(
                                $resultScore
                                ?? 0,
                                0
                            )
                        }}

                    </p>

                    <p class="mt-1">
                        / 100
                    </p>

                </div>

                @if($passed)

                    <p class="mt-6">

                        Modul berhasil diselesaikan.

                        Progress training Anda telah
                        diperbarui menjadi 100%.

                    </p>

                @else

                    <p class="mt-6">

                        Nilai Anda belum mencapai
                        passing score.

                        Anda dapat mencoba kembali
                        selama batas attempt masih tersedia.

                    </p>

                @endif

            </section>

        @endif

        {{-- ========================================================= --}}
        {{-- RESULT ACTION --}}
        {{-- ========================================================= --}}

        <div class="flex flex-wrap gap-3">

            <a
                href="{{
                    route(
                        'training.show',
                        $quiz->trainingModule
                    )
                }}"
                wire:navigate
                class="
                    rounded-xl
                    bg-blue-600
                    px-5
                    py-3
                    font-semibold
                    text-white
                "
            >
                Kembali ke Modul
            </a>

            <a
                href="{{
                    route(
                        'curriculum'
                    )
                }}"
                wire:navigate
                class="
                    rounded-xl
                    border
                    border-zinc-300
                    px-5
                    py-3
                    font-semibold
                    dark:border-zinc-700
                "
            >
                Kurikulum
            </a>

        </div>

    @else

        {{-- ========================================================= --}}
        {{-- VALIDATION SUMMARY --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <section
                class="
                    rounded-xl
                    border
                    border-red-300
                    bg-red-50
                    p-4
                    text-red-700
                    dark:border-red-900
                    dark:bg-red-950/30
                    dark:text-red-300
                "
            >

                <strong>
                    Masih ada soal yang belum dijawab.
                </strong>

                <p class="mt-1 text-sm">
                    Silakan lengkapi semua jawaban sebelum submit test.
                </p>

            </section>

        @endif

        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form
            wire:submit="submit"
            class="space-y-6"
        >

            @foreach(
                $quiz->questions
                as $index => $question
            )

                <article
                    id="question-{{ $question->id }}"
                    wire:key="quiz-question-{{ $question->id }}"
                    class="
                        rounded-2xl
                        border
                        border-zinc-200
                        bg-white
                        p-6
                        dark:border-zinc-700
                        dark:bg-zinc-900
                    "
                >

                    {{-- ================================================= --}}
                    {{-- QUESTION NUMBER --}}
                    {{-- ================================================= --}}

                    <p class="text-sm text-zinc-500">

                        Soal

                        {{ $index + 1 }}

                        dari

                        {{
                            $quiz
                                ->questions
                                ->count()
                        }}

                    </p>

                    {{-- ================================================= --}}
                    {{-- QUESTION TEXT --}}
                    {{-- ================================================= --}}

                    <h2 class="mt-3 text-lg font-semibold">

                        {{
                            $question
                                ->question_text
                        }}

                    </h2>

                    {{-- ================================================= --}}
                    {{-- MULTIPLE CHOICE --}}
                    {{-- ================================================= --}}

                    @if(
                        $question->type
                        ===
                        'multiple_choice'
                    )

                        <div class="mt-5 space-y-3">

                            @foreach(
                                $question->options
                                ?? []
                                as $key => $label
                            )

                                <label
                                    wire:key="question-{{ $question->id }}-option-{{ $key }}"
                                    class="
                                        flex
                                        cursor-pointer
                                        items-start
                                        gap-3
                                        rounded-xl
                                        border
                                        border-zinc-200
                                        p-4
                                        transition

                                        hover:border-blue-400
                                        hover:bg-blue-50/50

                                        dark:border-zinc-700
                                        dark:hover:border-blue-700
                                        dark:hover:bg-blue-950/20
                                    "
                                >

                                    {{-- ===================================== --}}
                                    {{-- IMPORTANT FIX --}}
                                    {{-- ===================================== --}}
                                    {{--

                                        Jangan tulis:

                                        wire:model="
                                            answers.2
                                        "

                                        Binding harus satu baris.

                                    --}}

                                    <input
                                        type="radio"
                                        name="question_{{ $question->id }}"
                                        value="{{ $key }}"
                                        wire:model.live="answers.{{ $question->id }}"
                                        class="mt-1"
                                    >

                                    <span class="font-bold">

                                        {{ $key }}.

                                    </span>

                                    <span>

                                        {{ $label }}

                                    </span>

                                </label>

                            @endforeach

                        </div>

                    {{-- ================================================= --}}
                    {{-- ESSAY --}}
                    {{-- ================================================= --}}

                    @elseif(
                        $question->type
                        === 'essay'
                    )

                        <textarea
                            wire:model.blur="answers.{{ $question->id }}"
                            rows="7"
                            placeholder="Tuliskan jawaban Anda..."
                            class="
                                mt-5
                                w-full
                                rounded-xl
                                border
                                border-zinc-300
                                bg-white
                                p-4

                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500/20

                                dark:border-zinc-700
                                dark:bg-zinc-900
                            "
                        ></textarea>

                    @endif

                    {{-- ================================================= --}}
                    {{-- ERROR --}}
                    {{-- ================================================= --}}

                    @error(
                        'answers.'
                        . $question->id
                    )

                        <p class="mt-3 text-sm font-medium text-red-600">

                            {{ $message }}

                        </p>

                    @enderror

                </article>

            @endforeach

            {{-- ========================================================= --}}
            {{-- SUBMIT --}}
            {{-- ========================================================= --}}

            <section
                class="
                    flex
                    flex-wrap
                    items-center
                    justify-between
                    gap-4
                    rounded-2xl
                    border
                    border-zinc-200
                    bg-white
                    p-5

                    dark:border-zinc-700
                    dark:bg-zinc-900
                "
            >

                <div>

                    <p class="font-semibold">
                        Sudah yakin dengan jawaban Anda?
                    </p>

                    <p class="mt-1 text-sm text-zinc-500">

                        Pastikan seluruh soal
                        telah dijawab sebelum submit.

                    </p>

                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                    class="
                        rounded-xl
                        bg-blue-600
                        px-6
                        py-3
                        font-semibold
                        text-white
                        transition

                        hover:bg-blue-700

                        disabled:cursor-not-allowed
                        disabled:opacity-50
                    "
                >

                    <span
                        wire:loading.remove
                        wire:target="submit"
                    >
                        Submit Test
                    </span>

                    <span
                        wire:loading
                        wire:target="submit"
                    >
                        Memproses...
                    </span>

                </button>

            </section>

        </form>

    @endif

</div>