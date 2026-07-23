<!DOCTYPE html>

<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="dark"
>

<head>

    @include('partials.head')

</head>

<body
    class="
        min-h-screen
        bg-white
        dark:bg-zinc-800
    "
>

    {{-- ============================================================= --}}
    {{-- SIDEBAR --}}
    {{-- ============================================================= --}}

    <flux:sidebar
        sticky
        collapsible="mobile"
        class="
            border-e
            border-zinc-200
            bg-zinc-50
            dark:border-zinc-700
            dark:bg-zinc-900
        "
    >

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <flux:sidebar.header>

            <x-app-logo
                :sidebar="true"
                href="{{ route('dashboard') }}"
                wire:navigate
            />

            <flux:sidebar.collapse
                class="lg:hidden"
            />

        </flux:sidebar.header>

        {{-- ========================================================= --}}
        {{-- NAVIGATION --}}
        {{-- ========================================================= --}}

        <flux:sidebar.nav>

            {{-- ===================================================== --}}
            {{-- UTAMA --}}
            {{-- ===================================================== --}}

            <flux:sidebar.group
                heading="Utama"
                class="grid"
            >

                <flux:sidebar.item
                    :href="route('dashboard')"
                    :current="
                        request()
                            ->routeIs(
                                'dashboard'
                            )
                    "
                    wire:navigate
                >
                    Dashboard
                </flux:sidebar.item>

                <flux:sidebar.item
                    :href="
                        route(
                            'profile.index'
                        )
                    "
                    :current="
                        request()
                            ->routeIs(
                                'profile.index'
                            )
                    "
                    wire:navigate
                >
                    Profil Saya
                </flux:sidebar.item>

            </flux:sidebar.group>

            {{-- ===================================================== --}}
            {{-- LEARNING & ASSESSMENT --}}
            {{-- ===================================================== --}}

            <flux:sidebar.group
                heading="Learning & Assessment"
                class="grid"
            >

                <flux:sidebar.item
                    :href="
                        route(
                            'curriculum'
                        )
                    "
                    :current="
                        request()
                            ->routeIs(
                                'curriculum'
                            )
                        ||
                        request()
                            ->routeIs(
                                'training.show'
                            )
                        ||
                        request()
                            ->routeIs(
                                'quiz.take'
                            )
                    "
                    wire:navigate
                >
                    Kurikulum
                </flux:sidebar.item>

                @if(
                    auth()->user()->isManager()
                    ||
                    auth()->user()->isHr()
                )

                    <flux:sidebar.item
                        :href="
                            route(
                                'assignments'
                            )
                        "
                        :current="
                            request()
                                ->routeIs(
                                    'assignments'
                                )
                        "
                        wire:navigate
                    >
                        Assignment
                    </flux:sidebar.item>

                @endif

                <flux:sidebar.item
                    :href="
                        route(
                            'fun-test'
                        )
                    "
                    :current="
                        request()
                            ->routeIs(
                                'fun-test'
                            )
                    "
                    wire:navigate
                >
                    Fun Test Zone
                </flux:sidebar.item>

            </flux:sidebar.group>

            {{-- ===================================================== --}}
            {{-- INFORMASI & REGULASI --}}
            {{-- ===================================================== --}}

            <flux:sidebar.group
                heading="Informasi & Regulasi"
                class="grid"
            >

                <flux:sidebar.item
                    :href="
                        route(
                            'company-policies'
                        )
                    "
                    :current="
                        request()
                            ->routeIs(
                                'company-policies'
                            )
                    "
                    wire:navigate
                >
                    Kebijakan Perusahaan
                </flux:sidebar.item>

                <flux:sidebar.item
                    :href="
                        route(
                            'sop'
                        )
                    "
                    :current="
                        request()
                            ->routeIs(
                                'sop'
                            )
                    "
                    wire:navigate
                >
                    SOP
                </flux:sidebar.item>

            </flux:sidebar.group>

            {{-- ===================================================== --}}
            {{-- PERFORMA & REVIEW --}}
            {{-- ===================================================== --}}

            <flux:sidebar.group
                heading="Performa & Review"
                class="grid"
            >

                @if(
                    auth()->user()->isManager()
                    ||
                    auth()->user()->isHr()
                )

                    <flux:sidebar.item
                        :href="
                            route(
                                'kpi-report'
                            )
                        "
                        :current="
                            request()
                                ->routeIs(
                                    'kpi-report'
                                )
                        "
                        wire:navigate
                    >
                        Laporan KPI
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        :href="
                            route(
                                'manager-review'
                            )
                        "
                        :current="
                            request()
                                ->routeIs(
                                    'manager-review'
                                )
                        "
                        wire:navigate
                    >
                        Review Atasan
                    </flux:sidebar.item>

                @endif

                <flux:sidebar.item
                    :href="
                        route(
                            'contributors'
                        )
                    "
                    :current="
                        request()
                            ->routeIs(
                                'contributors'
                            )
                    "
                    wire:navigate
                >
                    Kontributor
                </flux:sidebar.item>

            </flux:sidebar.group>

        </flux:sidebar.nav>

        <flux:spacer />

        {{-- ========================================================= --}}
        {{-- DESKTOP USER MENU --}}
        {{-- ========================================================= --}}

        <div
            class="
                hidden
                border-t
                border-zinc-200
                p-4
                lg:block
                dark:border-zinc-700
            "
        >

            <div class="mb-4">

                <p
                    class="
                        truncate
                        text-sm
                        font-semibold
                        text-zinc-900
                        dark:text-white
                    "
                >
                    {{ auth()->user()->name }}
                </p>

                <p
                    class="
                        truncate
                        text-xs
                        text-zinc-500
                    "
                >
                    {{ auth()->user()->email }}
                </p>

            </div>

            <div class="space-y-1">

                <a
                    href="{{ route('profile.edit') }}"
                    wire:navigate
                    class="
                        block
                        rounded-lg
                        px-3
                        py-2
                        text-sm
                        text-zinc-700
                        transition
                        hover:bg-zinc-200
                        dark:text-zinc-300
                        dark:hover:bg-zinc-800
                    "
                >
                    Settings
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="
                            block
                            w-full
                            rounded-lg
                            px-3
                            py-2
                            text-left
                            text-sm
                            text-red-600
                            transition
                            hover:bg-red-50
                            dark:hover:bg-red-950/30
                        "
                    >
                        Log out
                    </button>

                </form>

            </div>

        </div>

    </flux:sidebar>

    {{-- ============================================================= --}}
    {{-- MOBILE HEADER --}}
    {{-- ============================================================= --}}

    <flux:header
        class="lg:hidden"
    >

        <flux:sidebar.toggle
            class="lg:hidden"
            inset="left"
        />

        <flux:spacer />

        <div
            class="
                flex
                items-center
                gap-3
            "
        >

            <div
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-full
                    bg-green-600
                    text-sm
                    font-bold
                    text-white
                "
            >
                {{
                    auth()
                        ->user()
                        ->initials()
                }}
            </div>

            <div class="hidden sm:block">

                <p
                    class="
                        text-sm
                        font-semibold
                    "
                >
                    {{ auth()->user()->name }}
                </p>

            </div>

        </div>

    </flux:header>

    {{-- ============================================================= --}}
    {{-- PAGE CONTENT --}}
    {{-- ============================================================= --}}

    {{ $slot }}

    {{-- ============================================================= --}}
    {{-- TOAST --}}
    {{-- ============================================================= --}}

    @persist('toast')

        <flux:toast.group>

            <flux:toast />

        </flux:toast.group>

    @endpersist

    @fluxScripts

</body>

</html>