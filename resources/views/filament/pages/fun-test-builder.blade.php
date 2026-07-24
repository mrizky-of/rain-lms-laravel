<x-filament-panels::page>

    <style>

        .ft-grid {
            display: grid;
            gap: 20px;
        }

        .ft-card {
            padding: 20px;
            border:
                1px solid
                rgba(130,130,140,.28);
            border-radius: 14px;
        }

        .ft-two {
            display: grid;
            grid-template-columns:
                repeat(
                    2,
                    minmax(0,1fr)
                );
            gap: 12px;
        }

        .ft-four {
            display: grid;
            grid-template-columns:
                repeat(
                    2,
                    minmax(0,1fr)
                );
            gap: 12px;
        }

        .ft-input,
        .ft-select,
        .ft-textarea {
            width: 100%;
            border:
                1px solid
                #9ca3af;
            border-radius: 8px;
            padding: 10px 12px;
            background: transparent;
        }

        .ft-textarea {
            min-height: 90px;
        }

        .ft-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .ft-btn {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .ft-primary {
            background: #16a34a;
            color: white;
        }

        .ft-danger {
            background: #dc2626;
            color: white;
        }

        .ft-secondary {
            background: #475569;
            color: white;
        }

        .ft-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .ft-list-row {
            display: grid;
            grid-template-columns:
                1.2fr
                1fr
                1fr
                auto;
            gap: 10px;
            padding: 14px 0;
            border-bottom:
                1px solid
                rgba(130,130,140,.2);
        }

        @media(
            max-width: 800px
        ) {

            .ft-two,
            .ft-four,
            .ft-list-row {
                grid-template-columns:
                    1fr;
            }

        }

    </style>

    <div class="ft-grid">

        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form
            wire:submit="saveFunTest"
            class="ft-card"
        >

            <h2 class="text-xl font-bold">

                {{
                    $editingId
                        ? 'Edit Fun Test'
                        : 'Tambah Fun Test'
                }}

            </h2>

            <br>

            {{-- ===================================================== --}}
            {{-- GENERAL --}}
            {{-- ===================================================== --}}

            <div class="ft-two">

                <div>

                    <label class="ft-label">
                        Department
                    </label>

                    <select
                        wire:model="departmentId"
                        class="ft-select"
                        @disabled(
                            ! auth()
                                ->user()
                                ->isSuperAdmin()
                        )
                    >

                        <option value="">
                            -- Pilih Department --
                        </option>

                        @foreach(
                            $this->getDepartments()
                            as $id => $name
                        )

                            <option
                                value="{{ $id }}"
                            >
                                {{ $name }}
                            </option>

                        @endforeach

                    </select>

                    @error('departmentId')

                        <p class="ft-error">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

                <div>

                    <label class="ft-label">
                        Mode
                    </label>

                    <select
                        wire:model.live="mode"
                        class="ft-select"
                    >

                        <option value="drag_drop">
                            Drag & Drop
                        </option>

                        <option value="match_it">
                            Match It
                        </option>

                        <option value="word_scramble">
                            Word Scramble
                        </option>

                        <option value="speed_quiz">
                            Speed Quiz
                        </option>

                        <option value="true_false">
                            True / False
                        </option>

                        <option value="fill_blank">
                            Fill the Blank
                        </option>

                    </select>

                    @error('mode')

                        <p class="ft-error">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

            <br>

            <label class="ft-label">
                Judul Fun Test
            </label>

            <input
                type="text"
                wire:model="funTestTitle"
                class="ft-input"
                placeholder="
                    Contoh:
                    Match It SCM Terms
                "
            >

            @error('funTestTitle')

                <p class="ft-error">
                    {{ $message }}
                </p>

            @enderror

            <br><br>

            <label class="ft-label">
                Prompt / Pertanyaan
            </label>

            <textarea
                wire:model="prompt"
                class="ft-textarea"
                placeholder="
                    Masukkan instruksi
                    atau pertanyaan...
                "
            ></textarea>

            @error('prompt')

                <p class="ft-error">
                    {{ $message }}
                </p>

            @enderror

            <br><br>

            {{-- ===================================================== --}}
            {{-- DRAG DROP --}}
            {{-- ===================================================== --}}

            @if(
                $mode === 'drag_drop'
            )

                <h3 class="font-bold">
                    Drag & Drop Items
                </h3>

                <p class="mb-4 text-sm opacity-70">
                    Masukkan istilah dan
                    kategori tujuan yang benar.
                </p>

                @foreach(
                    $items
                    as $index => $item
                )

                    <div
                        class="ft-card"
                        style="
                            margin-top:
                                12px;
                        "
                        wire:key="
                            drag-item-
                            {{ $index }}
                        "
                    >

                        <div class="ft-two">

                            <div>

                                <label class="ft-label">
                                    Category
                                </label>

                                <input
                                    type="text"
                                    wire:model="
                                        items.{{ $index }}.category
                                    "
                                    class="ft-input"
                                    placeholder="
                                        Contoh:
                                        SCM
                                    "
                                >

                                @error(
                                    "items.{$index}.category"
                                )

                                    <p class="ft-error">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                            <div>

                                <label class="ft-label">
                                    Term
                                </label>

                                <input
                                    type="text"
                                    wire:model="
                                        items.{{ $index }}.term
                                    "
                                    class="ft-input"
                                    placeholder="
                                        Contoh:
                                        OTIF
                                    "
                                >

                                @error(
                                    "items.{$index}.term"
                                )

                                    <p class="ft-error">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                        <br>

                        <button
                            type="button"
                            wire:click="
                                removeItem(
                                    {{ $index }}
                                )
                            "
                            class="
                                ft-btn
                                ft-danger
                            "
                        >
                            Hapus Item
                        </button>

                    </div>

                @endforeach

                <br>

                <button
                    type="button"
                    wire:click="addItem"
                    class="
                        ft-btn
                        ft-secondary
                    "
                >
                    + Tambah Item
                </button>

            @endif

            {{-- ===================================================== --}}
            {{-- MATCH IT --}}
            {{-- ===================================================== --}}

            @if(
                $mode === 'match_it'
            )

                <h3 class="font-bold">
                    Match It Pairs
                </h3>

                <p class="mb-4 text-sm opacity-70">
                    Masukkan pasangan kiri
                    dan kanan.
                </p>

                @foreach(
                    $items
                    as $index => $item
                )

                    <div
                        class="ft-card"
                        style="
                            margin-top:
                                12px;
                        "
                        wire:key="
                            match-item-
                            {{ $index }}
                        "
                    >

                        <div class="ft-two">

                            <div>

                                <label class="ft-label">
                                    Left
                                </label>

                                <input
                                    type="text"
                                    wire:model="
                                        items.{{ $index }}.left
                                    "
                                    class="ft-input"
                                    placeholder="OTIF"
                                >

                                @error(
                                    "items.{$index}.left"
                                )

                                    <p class="ft-error">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                            <div>

                                <label class="ft-label">
                                    Right
                                </label>

                                <input
                                    type="text"
                                    wire:model="
                                        items.{{ $index }}.right
                                    "
                                    class="ft-input"
                                    placeholder="
                                        On Time In Full
                                    "
                                >

                                @error(
                                    "items.{$index}.right"
                                )

                                    <p class="ft-error">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                        <br>

                        <button
                            type="button"
                            wire:click="
                                removeItem(
                                    {{ $index }}
                                )
                            "
                            class="
                                ft-btn
                                ft-danger
                            "
                        >
                            Hapus Item
                        </button>

                    </div>

                @endforeach

                <br>

                <button
                    type="button"
                    wire:click="addItem"
                    class="
                        ft-btn
                        ft-secondary
                    "
                >
                    + Tambah Pasangan
                </button>

            @endif

            {{-- ===================================================== --}}
            {{-- WORD SCRAMBLE --}}
            {{-- ===================================================== --}}

            @if(
                $mode
                === 'word_scramble'
            )

                <div class="ft-two">

                    <div>

                        <label class="ft-label">
                            Kata Benar
                        </label>

                        <input
                            wire:model="word"
                            class="ft-input"
                            placeholder="
                                PROCUREMENT
                            "
                        >

                        @error('word')

                            <p class="ft-error">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    <div>

                        <label class="ft-label">
                            Kata Acak
                        </label>

                        <input
                            wire:model="scrambled"
                            class="ft-input"
                            placeholder="
                                CUREPROTNEM
                            "
                        >

                        @error('scrambled')

                            <p class="ft-error">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

                <br>

                <label class="ft-label">
                    Hint
                </label>

                <input
                    wire:model="hint"
                    class="ft-input"
                >

            @endif

            {{-- ===================================================== --}}
            {{-- SPEED QUIZ --}}
            {{-- ===================================================== --}}

            @if(
                $mode
                === 'speed_quiz'
            )

                <div class="ft-four">

                    <input
                        wire:model="optionA"
                        class="ft-input"
                        placeholder="Pilihan A"
                    >

                    <input
                        wire:model="optionB"
                        class="ft-input"
                        placeholder="Pilihan B"
                    >

                    <input
                        wire:model="optionC"
                        class="ft-input"
                        placeholder="Pilihan C"
                    >

                    <input
                        wire:model="optionD"
                        class="ft-input"
                        placeholder="Pilihan D"
                    >

                </div>

                <br>

                <div class="ft-two">

                    <div>

                        <label class="ft-label">
                            Jawaban Benar
                        </label>

                        <select
                            wire:model="
                                correctAnswer
                            "
                            class="ft-select"
                        >

                            <option value="A">
                                A
                            </option>

                            <option value="B">
                                B
                            </option>

                            <option value="C">
                                C
                            </option>

                            <option value="D">
                                D
                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="ft-label">
                            Time Limit
                            (detik)
                        </label>

                        <input
                            type="number"
                            min="1"
                            wire:model="
                                timeLimitSeconds
                            "
                            class="ft-input"
                        >

                    </div>

                </div>

            @endif

            {{-- ===================================================== --}}
            {{-- TRUE FALSE --}}
            {{-- ===================================================== --}}

            @if(
                $mode
                === 'true_false'
            )

                <label class="ft-label">
                    Jawaban Benar
                </label>

                <select
                    wire:model="
                        booleanAnswer
                    "
                    class="ft-select"
                >

                    <option value="true">
                        Benar
                    </option>

                    <option value="false">
                        Salah
                    </option>

                </select>

            @endif

            {{-- ===================================================== --}}
            {{-- FILL BLANK --}}
            {{-- ===================================================== --}}

            @if(
                $mode
                === 'fill_blank'
            )

                <label class="ft-label">
                    Jawaban yang Benar
                </label>

                <input
                    wire:model="textAnswer"
                    class="ft-input"
                >

                @error('textAnswer')

                    <p class="ft-error">
                        {{ $message }}
                    </p>

                @enderror

            @endif

            <br><br>

            {{-- ===================================================== --}}
            {{-- EXPLANATION --}}
            {{-- ===================================================== --}}

            <label class="ft-label">
                Pembahasan
            </label>

            <textarea
                wire:model="explanation"
                class="ft-textarea"
            ></textarea>

            <br><br>

            <label>

                <input
                    type="checkbox"
                    wire:model="isActive"
                >

                Aktif

            </label>

            <br><br>

            {{-- ===================================================== --}}
            {{-- ACTIONS --}}
            {{-- ===================================================== --}}

            <div
                style="
                    display:flex;
                    gap:10px;
                "
            >

                <button
                    type="submit"
                    class="
                        ft-btn
                        ft-primary
                    "
                    wire:loading.attr="
                        disabled
                    "
                    wire:target="
                        saveFunTest
                    "
                >

                    <span
                        wire:loading.remove
                        wire:target="
                            saveFunTest
                        "
                    >

                        {{
                            $editingId
                                ? 'Update Fun Test'
                                : 'Simpan Fun Test'
                        }}

                    </span>

                    <span
                        wire:loading
                        wire:target="
                            saveFunTest
                        "
                    >
                        Menyimpan...
                    </span>

                </button>

                @if($editingId)

                    <button
                        type="button"
                        wire:click="
                            cancelEdit
                        "
                        class="
                            ft-btn
                            ft-secondary
                        "
                    >
                        Batal
                    </button>

                @endif

            </div>

        </form>

        {{-- ========================================================= --}}
        {{-- LIST --}}
        {{-- ========================================================= --}}

        <div class="ft-card">

            <h2 class="text-xl font-bold">
                Daftar Fun Test
            </h2>

            @forelse(
                $this->getRecords()
                as $record
            )

                <div class="ft-list-row">

                    <div>

                        <strong>

                            {{
                                $record->title
                                ??
                                $record->prompt
                            }}

                        </strong>

                        <br>

                        <small>

                            {{
                                $record
                                    ->creator
                                    ?->name
                                ?? '-'
                            }}

                        </small>

                    </div>

                    <div>

                        {{
                            strtoupper(
                                str_replace(
                                    '_',
                                    ' ',
                                    $record->mode
                                )
                            )
                        }}

                    </div>

                    <div>

                        {{
                            $record
                                ->department
                                ?->name
                            ?? '-'
                        }}

                    </div>

                    <div
                        style="
                            display:flex;
                            gap:6px;
                        "
                    >

                        <button
                            type="button"
                            wire:click="
                                edit(
                                    {{ $record->id }}
                                )
                            "
                            class="
                                ft-btn
                                ft-secondary
                            "
                        >
                            Edit
                        </button>

                        <button
                            type="button"
                            wire:click="
                                delete(
                                    {{ $record->id }}
                                )
                            "
                            wire:confirm="
                                Yakin hapus
                                Fun Test ini?
                            "
                            class="
                                ft-btn
                                ft-danger
                            "
                        >
                            Hapus
                        </button>

                    </div>

                </div>

            @empty

                <p
                    style="
                        margin-top:16px;
                    "
                >
                    Belum ada Fun Test.
                </p>

            @endforelse

        </div>

    </div>

</x-filament-panels::page>