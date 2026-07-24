<x-filament-panels::page>

    <style>

        .qb-wrap {
            display: grid;
            gap: 20px;
        }

        .qb-card {
            border: 1px solid rgba(120, 120, 130, .28);
            border-radius: 14px;
            padding: 20px;
        }

        .qb-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .qb-label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .qb-input,
        .qb-select,
        .qb-textarea {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #9ca3af;
            border-radius: 9px;
            background: transparent;
        }

        .qb-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .qb-two {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .qb-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .qb-error-text {
            margin-top: 5px;
            font-size: 13px;
            color: #dc2626;
        }

        .qb-error-box {
            padding: 16px;
            border: 1px solid #ef4444;
            border-radius: 12px;
            background: rgba(239, 68, 68, .08);
            color: #dc2626;
            word-break: break-word;
        }

        .qb-success-box {
            padding: 16px;
            border: 1px solid #22c55e;
            border-radius: 12px;
            background: rgba(34, 197, 94, .08);
            color: #15803d;
        }

        .qb-warning-box {
            padding: 16px;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            background: rgba(245, 158, 11, .08);
        }

        .qb-info-box {
            padding: 14px 16px;
            border: 1px solid #3b82f6;
            border-radius: 12px;
            background: rgba(59, 130, 246, .08);
        }

        .qb-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .qb-button {
            padding: 12px 20px;
            border: 0;
            border-radius: 9px;
            font-weight: 700;
            cursor: pointer;
        }

        .qb-button-primary {
            background: #16a34a;
            color: white;
        }

        .qb-button-secondary {
            background: #334155;
            color: white;
        }

        .qb-button:disabled {
            cursor: not-allowed;
            opacity: .55;
        }

        @media (max-width: 800px) {

            .qb-two,
            .qb-options {
                grid-template-columns: 1fr;
            }

        }

    </style>

    <div class="qb-wrap">

        {{-- ========================================================= --}}
        {{-- LIVEWIRE CONNECTION TEST --}}
        {{-- ========================================================= --}}

        <div class="qb-card">

            <div class="qb-header">

                <div>

                    <strong>
                        Status Livewire
                    </strong>

                    <p style="margin-top: 4px; opacity: .7;">
                        Gunakan tombol ini sekali untuk memastikan action
                        pada custom Filament Page bekerja.
                    </p>

                </div>

                <div class="qb-actions">

                    <button
                        type="button"
                        wire:click="testConnection"
                        class="qb-button qb-button-secondary"
                    >
                        Tes Koneksi Livewire
                    </button>

                    <span>
                        Count:
                        {{ $connectionTestCount }}
                    </span>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- SUCCESS --}}
        {{-- ========================================================= --}}

        @if($saveMessage)

            <div class="qb-success-box">

                <strong>
                    Berhasil
                </strong>

                <div style="margin-top: 5px;">
                    {{ $saveMessage }}
                </div>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- SAVE ERROR --}}
        {{-- ========================================================= --}}

        @if($saveError)

            <div class="qb-error-box">

                <strong>
                    Gagal menyimpan
                </strong>

                <div style="margin-top: 7px;">
                    {{ $saveError }}
                </div>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="qb-error-box">

                <strong>
                    Ada data yang belum valid:
                </strong>

                <ul
                    style="
                        margin-top: 10px;
                        padding-left: 20px;
                        list-style: disc;
                    "
                >

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- QUIZ SELECT --}}
        {{-- ========================================================= --}}

        <div class="qb-card">

            <label class="qb-label">
                Pilih Quiz
            </label>

            <select
                wire:model.live.number="quizId"
                class="qb-select"
            >

                <option value="">
                    -- Pilih Quiz --
                </option>

                @foreach($this->getQuizOptions() as $id => $label)

                    <option value="{{ $id }}">
                        {{ $label }}
                    </option>

                @endforeach

            </select>

            @error('quizId')

                <p class="qb-error-text">
                    {{ $message }}
                </p>

            @enderror

        </div>

        {{-- ========================================================= --}}
        {{-- QUIZ SELECT LOADING --}}
        {{-- ========================================================= --}}

        <div
            wire:loading
            wire:target="quizId"
            class="qb-info-box"
        >
            Memuat template soal...
        </div>

        {{-- ========================================================= --}}
        {{-- LOCKED --}}
        {{-- ========================================================= --}}

        @if($locked)

            <div class="qb-warning-box">

                <strong>
                    Quiz terkunci
                </strong>

                <p style="margin-top: 6px;">
                    Quiz ini sudah memiliki attempt dari user.
                    Soal tidak dapat diubah agar hasil test lama tetap konsisten.
                </p>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- TEMPLATE INFO --}}
        {{-- ========================================================= --}}

        @if($quizId && count($questions) > 0)

            <div class="qb-info-box">

                Template tersedia:

                <strong>
                    {{ count($questions) }} soal
                </strong>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- QUESTIONS --}}
        {{-- ========================================================= --}}

        @foreach($questions as $index => $question)

            <article
                class="qb-card"
                wire:key="question-{{ $quizId }}-{{ $index }}"
            >

                {{-- ================================================= --}}
                {{-- HEADER --}}
                {{-- ================================================= --}}

                <div class="qb-header">

                    <div>

                        <h3
                            style="
                                font-size: 18px;
                                font-weight: 700;
                            "
                        >
                            Soal {{ $index + 1 }}
                        </h3>

                        <small style="opacity: .65;">
                            {{ $index + 1 }}
                            /
                            {{ count($questions) }}
                        </small>

                    </div>

                </div>

                <br>

                {{-- ================================================= --}}
                {{-- TYPE + POINT --}}
                {{-- ================================================= --}}

                <div class="qb-two">

                    <div>

                        <label class="qb-label">
                            Tipe Soal
                        </label>

                        <select
                            wire:model.live="questions.{{ $index }}.type"
                            class="qb-select"
                            @disabled($locked)
                        >

                            <option value="multiple_choice">
                                Multiple Choice
                            </option>

                            <option value="essay">
                                Essay
                            </option>

                        </select>

                        @error("questions.{$index}.type")

                            <p class="qb-error-text">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    <div>

                        <label class="qb-label">
                            Bobot
                        </label>

                        <input
                            type="number"
                            min="0.1"
                            step="0.1"
                            wire:model.blur.number="questions.{{ $index }}.points"
                            class="qb-input"
                            @disabled($locked)
                        >

                        @error("questions.{$index}.points")

                            <p class="qb-error-text">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

                <br>

                {{-- ================================================= --}}
                {{-- QUESTION TEXT --}}
                {{-- ================================================= --}}

                <label class="qb-label">
                    Pertanyaan
                </label>

                <textarea
                    wire:model.blur="questions.{{ $index }}.question_text"
                    class="qb-textarea"
                    placeholder="Masukkan pertanyaan..."
                    @disabled($locked)
                ></textarea>

                @error("questions.{$index}.question_text")

                    <p class="qb-error-text">
                        {{ $message }}
                    </p>

                @enderror

                <br>

                {{-- ================================================= --}}
                {{-- MULTIPLE CHOICE --}}
                {{-- ================================================= --}}

                @if(
                    ($questions[$index]['type'] ?? null)
                    ===
                    'multiple_choice'
                )

                    <div class="qb-options">

                        {{-- A --}}

                        <div>

                            <label class="qb-label">
                                Pilihan A
                            </label>

                            <input
                                type="text"
                                wire:model.blur="questions.{{ $index }}.option_a"
                                class="qb-input"
                                @disabled($locked)
                            >

                            @error("questions.{$index}.option_a")

                                <p class="qb-error-text">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        {{-- B --}}

                        <div>

                            <label class="qb-label">
                                Pilihan B
                            </label>

                            <input
                                type="text"
                                wire:model.blur="questions.{{ $index }}.option_b"
                                class="qb-input"
                                @disabled($locked)
                            >

                            @error("questions.{$index}.option_b")

                                <p class="qb-error-text">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        {{-- C --}}

                        <div>

                            <label class="qb-label">
                                Pilihan C
                            </label>

                            <input
                                type="text"
                                wire:model.blur="questions.{{ $index }}.option_c"
                                class="qb-input"
                                @disabled($locked)
                            >

                            @error("questions.{$index}.option_c")

                                <p class="qb-error-text">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        {{-- D --}}

                        <div>

                            <label class="qb-label">
                                Pilihan D
                            </label>

                            <input
                                type="text"
                                wire:model.blur="questions.{{ $index }}.option_d"
                                class="qb-input"
                                @disabled($locked)
                            >

                            @error("questions.{$index}.option_d")

                                <p class="qb-error-text">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>

                    <br>

                    {{-- ================================================= --}}
                    {{-- CORRECT ANSWER --}}
                    {{-- ================================================= --}}

                    <label class="qb-label">
                        Jawaban Benar
                    </label>

                    <select
                        wire:model.change="questions.{{ $index }}.correct_answer"
                        class="qb-select"
                        @disabled($locked)
                    >

                        <option value="A">
                            A — Pilihan A
                        </option>

                        <option value="B">
                            B — Pilihan B
                        </option>

                        <option value="C">
                            C — Pilihan C
                        </option>

                        <option value="D">
                            D — Pilihan D
                        </option>

                    </select>

                    @error("questions.{$index}.correct_answer")

                        <p class="qb-error-text">
                            {{ $message }}
                        </p>

                    @enderror

                @else

                    {{-- ================================================= --}}
                    {{-- ESSAY --}}
                    {{-- ================================================= --}}

                    <label class="qb-label">
                        Kunci / Referensi Jawaban Essay
                    </label>

                    <textarea
                        wire:model.blur="questions.{{ $index }}.essay_answer_key"
                        class="qb-textarea"
                        placeholder="Masukkan jawaban ideal atau poin-poin jawaban..."
                        @disabled($locked)
                    ></textarea>

                    @error("questions.{$index}.essay_answer_key")

                        <p class="qb-error-text">
                            {{ $message }}
                        </p>

                    @enderror

                @endif

                <br>

                {{-- ================================================= --}}
                {{-- EXPLANATION --}}
                {{-- ================================================= --}}

                <label class="qb-label">
                    Pembahasan
                </label>

                <textarea
                    wire:model.blur="questions.{{ $index }}.explanation"
                    class="qb-textarea"
                    placeholder="Pembahasan setelah user menjawab..."
                    @disabled($locked)
                ></textarea>

                @error("questions.{$index}.explanation")

                    <p class="qb-error-text">
                        {{ $message }}
                    </p>

                @enderror

            </article>

        @endforeach

        {{-- ========================================================= --}}
        {{-- SAVE BUTTON --}}
        {{-- ========================================================= --}}

        @if(
            $quizId
            &&
            count($questions) > 0
            &&
            ! $locked
        )

            <div class="qb-card">

                <div class="qb-actions">

                    <button
                        type="button"
                        wire:click="saveQuestions"
                        wire:loading.attr="disabled"
                        wire:target="saveQuestions"
                        class="qb-button qb-button-primary"
                    >

                        <span
                            wire:loading.remove
                            wire:target="saveQuestions"
                        >
                            Simpan Semua Soal
                        </span>

                        <span
                            wire:loading
                            wire:target="saveQuestions"
                        >
                            Menyimpan...
                        </span>

                    </button>

                    <span
                        wire:loading
                        wire:target="saveQuestions"
                    >
                        Mohon tunggu...
                    </span>

                </div>

            </div>

        @endif

    </div>

</x-filament-panels::page>