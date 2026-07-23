<aside class="w-72 border-r bg-white p-6">

    <div class="mb-8">
        <h1 class="text-2xl font-bold">
            RAIN LMS
        </h1>

        <p class="text-sm text-gray-500">
            Learning Management System
        </p>
    </div>

    <nav class="space-y-2">

        <a
            href="{{ route('dashboard') }}"
            class="block rounded-xl px-4 py-3 hover:bg-gray-100"
        >
            Dashboard
        </a>

        <a
            href="{{ route('profile.index') }}"
            class="block rounded-xl px-4 py-3 hover:bg-gray-100"
        >
            Profil Saya
        </a>

        <hr class="my-4">

        <p class="px-4 text-xs font-semibold uppercase text-gray-400">
            Learning & Assessment
        </p>

        <a
            href="{{ route('curriculum') }}"
            class="block rounded-xl px-4 py-3 hover:bg-gray-100"
        >
            Kurikulum
        </a>

        <a
            href="{{ route('fun-test') }}"
            class="block rounded-xl px-4 py-3 hover:bg-gray-100"
        >
            Fun Test Zone
        </a>

        @if(
            auth()->user()->isManager()
            || auth()->user()->isHr()
            || auth()->user()->isSuperAdmin()
        )

            <a
                href="{{ route('assignments') }}"
                class="block rounded-xl px-4 py-3 hover:bg-gray-100"
            >
                Assignment
            </a>

        @endif

        <hr class="my-4">

        <p class="px-4 text-xs font-semibold uppercase text-gray-400">
            Informasi & Regulasi
        </p>

        <a
            href="{{ route('company-policies') }}"
            class="block rounded-xl px-4 py-3 hover:bg-gray-100"
        >
            Kebijakan Perusahaan
        </a>

        <a
            href="{{ route('sop') }}"
            class="block rounded-xl px-4 py-3 hover:bg-gray-100"
        >
            SOP
        </a>

        <hr class="my-4">

        <p class="px-4 text-xs font-semibold uppercase text-gray-400">
            Performa & Review
        </p>

        @if(
            auth()->user()->isManager()
            || auth()->user()->isHr()
            || auth()->user()->isSuperAdmin()
        )

            <a
                href="{{ route('kpi-report') }}"
                class="block rounded-xl px-4 py-3 hover:bg-gray-100"
            >
                Laporan KPI
            </a>

            <a
                href="{{ route('manager-review') }}"
                class="block rounded-xl px-4 py-3 hover:bg-gray-100"
            >
                Review Atasan
            </a>

        @endif

        <a
            href="{{ route('contributors') }}"
            class="block rounded-xl px-4 py-3 hover:bg-gray-100"
        >
            Kontributor
        </a>

        @if(
            auth()->user()->canAccessPanel(
                filament()->getPanel('admin')
            )
        )

            <hr class="my-4">

            <a
                href="/admin"
                class="block rounded-xl bg-gray-900 px-4 py-3 text-white"
            >
                Admin Panel
            </a>

        @endif

    </nav>

</aside>