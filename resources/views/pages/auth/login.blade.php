<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Login - RAIN LMS
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

</head>

<body
    class="
        min-h-screen
        overflow-x-hidden
        bg-[#dff3ff]
        text-slate-900
        antialiased
    "
>

    {{-- ========================================================= --}}
    {{-- BACKGROUND --}}
    {{-- ========================================================= --}}

    <div
        class="
            relative
            flex
            min-h-screen
            w-full
            items-center
            justify-center
            overflow-hidden
            px-4
            py-10
            sm:px-6
        "
        style="
            background:
                radial-gradient(
                    circle at center,
                    #f8fcff 0%,
                    #e9f8ff 30%,
                    #d7effc 58%,
                    #b9ddf3 100%
                );
        "
    >

        {{-- Decorative soft glow kiri --}}
        <div
            class="
                pointer-events-none
                absolute
                -left-40
                top-1/2
                h-[450px]
                w-[450px]
                -translate-y-1/2
                rounded-full
                bg-white/40
                blur-3xl
            "
        ></div>

        {{-- Decorative soft glow kanan --}}
        <div
            class="
                pointer-events-none
                absolute
                -right-40
                top-1/3
                h-[500px]
                w-[500px]
                rounded-full
                bg-sky-200/30
                blur-3xl
            "
        ></div>

        {{-- Decorative sparkle --}}
        <div
            class="
                pointer-events-none
                absolute
                bottom-[12%]
                right-[7%]
                hidden
                text-7xl
                text-white/50
                lg:block
            "
        >
            ✦
        </div>

        {{-- ========================================================= --}}
        {{-- LOGIN WRAPPER --}}
        {{-- ========================================================= --}}

        <main
            class="
                relative
                z-10
                flex
                w-full
                max-w-[540px]
                flex-col
                items-center
            "
        >

            {{-- ===================================================== --}}
            {{-- LOGO --}}
            {{-- ===================================================== --}}

            <div
                class="
                    mb-8
                    flex
                    w-full
                    justify-center
                    sm:mb-9
                "
            >

                <img
                    src="{{ asset('images/rain-group-logo.png') }}"
                    alt="RAIN GROUP"
                    class="
                        h-auto
                        w-[150px]
                        max-w-[50vw]
                        object-contain
                        sm:w-[150px]
                    "
                >

            </div>

            {{-- ===================================================== --}}
            {{-- LOGIN CARD --}}
            {{-- ===================================================== --}}

            <section
                class="
                    w-full
                    rounded-[18px]
                    border
                    border-white/80
                    bg-white/95
                    px-6
                    py-8
                    shadow-[0_12px_35px_rgba(15,50,70,0.18)]
                    backdrop-blur-sm
                    sm:px-9
                    sm:py-9
                "
            >

                {{-- Header --}}

                <div class="mb-7 text-center">

                    <h1
                        class="
                            text-[26px]
                            font-bold
                            tracking-tight
                            text-[#12391e]
                            sm:text-[30px]
                        "
                    >
                        Log in to your account
                    </h1>

                    <p
                        class="
                            mt-2
                            text-sm
                            text-[#314837]
                            sm:text-base
                        "
                    >
                        Enter your email and password below to log in
                    </p>

                </div>

                {{-- ================================================= --}}
                {{-- SESSION STATUS --}}
                {{-- ================================================= --}}

                @if (session('status'))

                    <div
                        class="
                            mb-5
                            rounded-xl
                            border
                            border-green-200
                            bg-green-50
                            px-4
                            py-3
                            text-sm
                            text-green-700
                        "
                    >
                        {{ session('status') }}
                    </div>

                @endif

                {{-- ================================================= --}}
                {{-- GLOBAL ERROR --}}
                {{-- ================================================= --}}

                @if ($errors->any())

                    <div
                        class="
                            mb-5
                            rounded-xl
                            border
                            border-red-200
                            bg-red-50
                            px-4
                            py-3
                            text-sm
                            text-red-700
                        "
                    >

                        <p class="font-semibold">
                            Login gagal
                        </p>

                        <p class="mt-1">
                            {{ $errors->first() }}
                        </p>

                    </div>

                @endif

                {{-- ================================================= --}}
                {{-- LOGIN FORM --}}
                {{-- ================================================= --}}

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    class="space-y-5"
                >

                    @csrf

                    {{-- ================================================= --}}
                    {{-- EMAIL --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="email"
                            class="
                                mb-2
                                block
                                text-[15px]
                                font-medium
                                text-[#568c51]
                                sm:text-base
                            "
                        >
                            Email address
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="email@example.com"
                            class="
                                block
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                bg-slate-50/80
                                px-4
                                py-3.5
                                text-base
                                text-slate-800
                                outline-none
                                transition
                                placeholder:text-slate-400
                                hover:border-[#8aba84]
                                focus:border-[#69a263]
                                focus:bg-white
                                focus:ring-4
                                focus:ring-[#69a263]/20
                            "
                        >

                        @error('email')

                            <p
                                class="
                                    mt-2
                                    text-sm
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    {{-- ================================================= --}}
                    {{-- PASSWORD --}}
                    {{-- ================================================= --}}

                    <div>

                        <div
                            class="
                                mb-2
                                flex
                                items-center
                                justify-between
                                gap-4
                            "
                        >

                            <label
                                for="password"
                                class="
                                    text-[15px]
                                    font-medium
                                    text-[#568c51]
                                    sm:text-base
                                "
                            >
                                Password
                            </label>

                            @if (
                                Route::has(
                                    'password.request'
                                )
                            )

                            @endif

                        </div>

                        <div class="relative">

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="Password"
                                class="
                                    block
                                    w-full
                                    rounded-xl
                                    border
                                    border-slate-300
                                    bg-slate-50/80
                                    px-4
                                    py-3.5
                                    pr-12
                                    text-base
                                    text-slate-800
                                    outline-none
                                    transition
                                    placeholder:text-slate-400
                                    hover:border-[#8aba84]
                                    focus:border-[#69a263]
                                    focus:bg-white
                                    focus:ring-4
                                    focus:ring-[#69a263]/20
                                "
                            >

                            {{-- Toggle password --}}

                            <button
                                id="toggle-password"
                                type="button"
                                aria-label="Tampilkan password"
                                class="
                                    absolute
                                    right-3
                                    top-1/2
                                    -translate-y-1/2
                                    rounded-lg
                                    p-2
                                    text-[#5a984e]
                                    transition
                                    hover:bg-green-50
                                    hover:text-[#39703a]
                                    focus:outline-none
                                "
                            >

                                {{-- Eye Open --}}

                                <svg
                                    id="eye-open"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="
                                            M2.25 12
                                            s3.75-6.75
                                            9.75-6.75
                                            S21.75 12
                                            21.75 12
                                            18 18.75
                                            12 18.75
                                            2.25 12
                                            2.25 12Z
                                        "
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="
                                            M15 12
                                            a3 3 0 1 1-6 0
                                            3 3 0 0 1 6 0Z
                                        "
                                    />
                                </svg>

                                {{-- Eye Closed --}}

                                <svg
                                    id="eye-closed"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="hidden h-5 w-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="
                                            M3 3
                                            l18 18
                                            M10.6 10.6
                                            a2 2 0 0 0
                                            2.8 2.8
                                            M9.9 4.5
                                            A10.6 10.6 0 0 1
                                            12 4.25
                                            c6 0 9.75 7.75
                                            9.75 7.75
                                            a17.3 17.3 0 0 1-2.3 3.2
                                            M6.2 6.2
                                            C3.6 8
                                            2.25 12
                                            2.25 12
                                            S6 19.75
                                            12 19.75
                                            c1.6 0
                                            3-.4
                                            4.2-1
                                        "
                                    />
                                </svg>

                            </button>

                        </div>

                        @error('password')

                            <p
                                class="
                                    mt-2
                                    text-sm
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    {{-- ================================================= --}}
                    {{-- REMEMBER ME --}}
                    {{-- ================================================= --}}

                    <label
                        class="
                            flex
                            cursor-pointer
                            items-center
                            gap-3
                            text-[15px]
                            text-[#568c51]
                            sm:text-base
                        "
                    >

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            @checked(
                                old('remember')
                            )
                            class="
                                h-5
                                w-5
                                rounded
                                border-2
                                border-[#77a872]
                                text-[#57a947]
                                accent-[#57a947]
                                focus:ring-[#57a947]
                            "
                        >

                        <span>
                            Remember me
                        </span>

                    </label>

                    {{-- ================================================= --}}
                    {{-- LOGIN BUTTON --}}
                    {{-- ================================================= --}}

                    <button
                        type="submit"
                        class="
                            relative
                            mt-2
                            flex
                            w-full
                            items-center
                            justify-center
                            overflow-hidden
                            rounded-xl
                            border
                            border-[#77a432]
                            bg-gradient-to-r
                            from-[#55bd43]
                            via-[#4fba3d]
                            to-[#57b93f]
                            px-5
                            py-3.5
                            text-base
                            font-medium
                            text-white
                            shadow-[0_5px_0_#c6a92d,0_8px_16px_rgba(48,100,40,0.24)]
                            transition
                            duration-200
                            hover:brightness-105
                            active:translate-y-[3px]
                            active:shadow-[0_2px_0_#c6a92d]
                        "
                    >
                        Log in
                    </button>

                </form>

            </section>

            {{-- ===================================================== --}}
            {{-- FOOTER --}}
            {{-- ===================================================== --}}

            <p
                class="
                    mt-6
                    text-center
                    text-xs
                    text-slate-500/80
                "
            >
                © {{ date('Y') }} RAIN GROUP.
                Learning Management System.
            </p>

        </main>

    </div>

    {{-- ============================================================= --}}
    {{-- PASSWORD TOGGLE SCRIPT --}}
    {{-- ============================================================= --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput =
                document.getElementById('password');

            const toggleButton =
                document.getElementById('toggle-password');

            const eyeOpen =
                document.getElementById('eye-open');

            const eyeClosed =
                document.getElementById('eye-closed');

            if (
                !passwordInput ||
                !toggleButton ||
                !eyeOpen ||
                !eyeClosed
            ) {
                return;
            }

            toggleButton.addEventListener(
                'click',
                () => {
                    const isPassword =
                        passwordInput.type === 'password';

                    passwordInput.type =
                        isPassword
                            ? 'text'
                            : 'password';

                    eyeOpen.classList.toggle(
                        'hidden',
                        isPassword
                    );

                    eyeClosed.classList.toggle(
                        'hidden',
                        !isPassword
                    );

                    toggleButton.setAttribute(
                        'aria-label',
                        isPassword
                            ? 'Sembunyikan password'
                            : 'Tampilkan password'
                    );
                }
            );
        });
    </script>

</body>

</html>