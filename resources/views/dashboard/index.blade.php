@extends('layouts.dashboard')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- Header + Multi-select Filters --}}
<div class="rounded-xl border border-slate-200 bg-slate-900 p-4 shadow-sm">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-white/10 text-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 18v-7"/>
                    <path d="M11.119 2.205a2 2 0 0 1 1.762 0l7.84 3.846A.5.5 0 0 1 20.5 7h-17a.5.5 0 0 1-.22-.949z"/>
                    <path d="M14 18v-7"/>
                    <path d="M18 18v-7"/>
                    <path d="M3 22h18"/>
                    <path d="M6 18v-7"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-white">Dashboard Utang DBH &amp; Realisasi Pembayaran</h1>
                <p class="text-xs text-slate-400">Provinsi Bengkulu · Dana Bagi Hasil ke Kabupaten/Kota · Realisasi per September 2026</p>
            </div>
        </div>

        {{-- Multi-select filter group --}}
        @php
            $msFilters = [
                [
                    'id' => 'ms_tahun', 'label' => 'Tahun Anggaran', 'name' => 'tahun', 'allLabel' => 'Semua',
                    'options' => collect($tahun)->map(fn($t) => [(string)$t, (string)$t])->values(),
                    'selected' => collect($filter['tahun'])->map(fn($v) => (string)$v)->values(),
                ],
                [
                    'id' => 'ms_kabupaten', 'label' => 'Kabupaten/Kota', 'name' => 'kabupaten', 'allLabel' => 'Semua',
                    'options' => $kabupatens->map(fn($k) => [(string)$k->id, $k->nama])->values(),
                    'selected' => collect($filter['kabupaten'])->map(fn($v) => (string)$v)->values(),
                ],
                [
                    'id' => 'ms_jenis_pajak', 'label' => 'Jenis Pajak', 'name' => 'jenis_pajak', 'allLabel' => 'Semua',
                    'options' => $jenisPajak->map(fn($j) => [(string)$j->id, $j->nama])->values(),
                    'selected' => collect($filter['jenis_pajak'])->map(fn($v) => (string)$v)->values(),
                ],
                [
                    'id' => 'ms_triwulan', 'label' => 'Triwulan', 'name' => 'triwulan', 'allLabel' => 'Semua',
                    'options' => collect([1,2,3,4])->map(fn($i) => [(string)$i, "Triwulan $i"])->values(),
                    'selected' => collect($filter['triwulan'])->map(fn($v) => (string)$v)->values(),
                ],
            ];
        @endphp

        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
            @foreach($msFilters as $f)
                <div class="relative" data-ms-root="{{ $f['id'] }}" data-ms-name="{{ $f['name'] }}">
                    <span class="mb-1 block text-[10px] font-medium uppercase tracking-wider text-slate-400">{{ $f['label'] }}</span>

                    <button type="button" data-ms-trigger
                            class="flex w-full items-center justify-between gap-2 rounded-lg border border-slate-700 bg-white px-3 py-2 text-left text-sm font-medium text-slate-900 shadow-sm transition hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span data-ms-label class="truncate">
                            @if($f['selected']->count() === 0)
                                {{ $f['allLabel'] }}
                            @elseif($f['selected']->count() === 1)
                                {{ $f['selected']->first() }}
                            @else
                                {{ $f['selected']->count() }} dipilih
                            @endif
                        </span>
                        <svg data-ms-chevron xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-400 transition" style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>

                    {{-- Dropdown panel: white background, dark text (high contrast) --}}
                    <div data-ms-panel
                         class="absolute z-30 mt-1 hidden w-full min-w-[12rem] max-h-64 overflow-auto rounded-lg border border-slate-200 bg-white p-1.5 shadow-lg">
                        <button type="button" data-ms-select-all
                                class="mb-1 flex w-full items-center justify-between rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                            Pilih Semua
                            <svg class="shrink-0 text-blue-600" style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                        <div class="max-h-52 overflow-auto">
                            @foreach($f['options'] as $opt)
                                @php $isSelected = $f['selected']->contains($opt[0]); @endphp
                                <button type="button" data-ms-option data-value="{{ $opt[0] }}"
                                        class="ms-option flex w-full items-center gap-2 rounded-md px-2.5 py-1.5 text-left text-sm text-slate-700 transition hover:bg-slate-100 {{ $isSelected ? 'ms-checked' : '' }}">
                                    <span data-ms-checkbox
                                          class="flex size-4 shrink-0 items-center justify-center rounded border {{ $isSelected ? 'border-blue-600 bg-blue-600' : 'border-slate-300 bg-white' }}">
                                        @if($isSelected)
                                            <svg class="text-white" style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                        @endif
                                    </span>
                                    <span class="truncate">{{ $opt[1] }}</span>
                                </button>
                            @endforeach
                        </div>
                        <button type="button" data-ms-apply
                                class="mt-2 flex w-full items-center justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Terapkan
                        </button>
                    </div>

                    {{-- Hidden inputs: one per selected value + a sentinel so empty filter still registers --}}
                    @foreach($f['selected'] as $val)
                        <input type="hidden" name="{{ $f['name'] }}" value="{{ $val }}" data-ms-value>
                    @endforeach
                    <input type="hidden" name="{{ $f['name'] }}" value="" data-ms-empty>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Total Utang</span>
        <p class="mt-1 text-2xl font-bold text-blue-600">Rp {{ number_format($totalUtang, 0, ',', '.') }}</p>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-blue-500"></div>
    </div>
    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Total Pembayaran</span>
        <p class="mt-1 text-2xl font-bold text-emerald-600">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500"></div>
    </div>
    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Total Sisa Utang</span>
        <p class="mt-1 text-2xl font-bold text-red-600">Rp {{ number_format($totalSisa, 0, ',', '.') }}</p>
        <div class="absolute bottom-0 left-0 h-1 w-full bg-red-500"></div>
    </div>
    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Persentase Realisasi</span>
        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $persentase }}%</p>
        <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $persentase }}%"></div>
        </div>
        <p class="mt-1 text-[10px] text-slate-400">Pembayaran terhadap total kewajiban utang</p>
    </div>
</div>

{{-- Charts Grid --}}
<div class="grid gap-3 xl:grid-cols-2">
    {{-- Chart 1: Horizontal Bar - Sisa Utang per Kabupaten --}}
    <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900">Peringkat Sisa Utang per Kabupaten/Kota</h3>
        <p class="text-[11px] text-slate-500">Diurutkan dari sisa utang tertinggi</p>
        <div class="mt-3 flex-1" style="min-height: 260px;">
            <canvas id="chartKab"></canvas>
        </div>
    </div>

    {{-- Chart 2: Stacked Bar - Realisasi vs Sisa per Jenis Pajak --}}
    <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900">Realisasi vs Sisa Utang per Jenis Pajak</h3>
        <p class="text-[11px] text-slate-500">Perbandingan pembayaran dan sisa (stacked)</p>
        <div class="mt-3 flex-1" style="min-height: 260px;">
            <canvas id="chartPajak"></canvas>
        </div>
    </div>

    {{-- Chart 3: Line/Bar - Tren Triwulan --}}
    <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900">Tren Pembayaran Utang antar Triwulan</h3>
        <p class="text-[11px] text-slate-500">Kewajiban utang vs realisasi pembayaran</p>
        <div class="mt-3 flex-1" style="min-height: 260px;">
            <canvas id="chartTriwulan"></canvas>
        </div>
    </div>

    {{-- Chart 4: Doughnut - Proporsi Sisa per Jenis Pajak --}}
    <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900">Proporsi Sisa Utang Berdasarkan Jenis Pajak</h3>
        <p class="text-[11px] text-slate-500">Distribusi kewajiban belum terbayar</p>
        <div class="mt-3 flex flex-col gap-4 lg:flex-row">
            <div class="flex-1" style="min-height: 200px;">
                <canvas id="chartDoughnut"></canvas>
            </div>
            <ul class="flex shrink-0 flex-col gap-1.5 lg:w-32">
                @php $colors = ['#2563EB', '#10B981', '#EF4444', '#F59E0B', '#8B5CF6', '#EC4899']; @endphp
                @foreach($proporsi as $i => $p)
                    <li class="flex items-center gap-2 text-xs">
                        <span class="size-2.5 shrink-0 rounded-sm" style="background-color: {{ $colors[$i % count($colors)] }}"></span>
                        <span class="font-medium text-slate-700">{{ $p['nama'] }}</span>
                        <span class="tabular-nums text-slate-400">{{ $p['pct'] }}%</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Key Insights --}}
<div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="text-sm font-semibold text-slate-900">Key Insights Eksekutif</h3>
            <p class="text-[11px] text-slate-500">Ringkasan otomatis dari filter aktif</p>
        </div>
        <a href="{{ route('admin.transaksi') }}" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-slate-700">Kelola Data →</a>
    </div>
    <div class="mt-4 grid gap-3 md:grid-cols-3">
        @foreach($insights as $k => $insight)
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">{{ $insight['label'] }}</span>
                <p class="mt-1 text-xs text-slate-700">{{ $insight['text'] }}</p>
            </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ===== Multi-select reactive filter (no "Terapkan" button — auto-submit on change) ===== */
(() => {
    const DASHBOARD_URL = @json(route('dashboard'));
    let debounceTimer = null;

    const roots = [...document.querySelectorAll('[data-ms-root]')];

    function closeAllPanels(exceptRoot) {
        roots.forEach(r => {
            if (r === exceptRoot) return;
            const panel = r.querySelector('[data-ms-panel]');
            const chevron = r.querySelector('[data-ms-chevron]');
            panel.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        });
    }

    function currentSelection(root) {
        // Read the checked state directly from the option buttons (authoritative source)
        return [...root.querySelectorAll('[data-ms-option].ms-checked')].map(o => o.getAttribute('data-value'));
    }

    function rebuildHiddenInputs(root) {
        const name = root.getAttribute('data-ms-name');
        const selectedVals = currentSelection(root);
        // Remove existing value inputs
        root.querySelectorAll('input[data-ms-value]').forEach(i => i.remove());
        // Add new ones for each checked option
        selectedVals.forEach(v => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = name;
            inp.value = v;
            inp.setAttribute('data-ms-value', '');
            root.appendChild(inp);
        });
        // Keep the empty sentinel
        let sentinel = root.querySelector('input[data-ms-empty]');
        if (!sentinel) {
            sentinel = document.createElement('input');
            sentinel.type = 'hidden';
            sentinel.name = name;
            sentinel.value = '';
            sentinel.setAttribute('data-ms-empty', '');
            root.appendChild(sentinel);
        }
        sentinel.name = name;
        sentinel.value = '';
    }

    function updateTriggerLabel(root) {
        const label = root.querySelector('[data-ms-label]');
        const vals = currentSelection(root);
        const allOptions = root.querySelectorAll('[data-ms-option]').length;
        if (vals.length === 0) {
            label.textContent = 'Semua';
        } else if (vals.length === 1) {
            // find the matching option button to get its display label
            const opt = root.querySelector(`[data-ms-option][data-value="${vals[0]}"]`);
            const textSpan = opt ? opt.querySelector('span:last-child') : null;
            label.textContent = textSpan ? textSpan.textContent.trim() : vals[0];
        } else if (vals.length === allOptions) {
            label.textContent = 'Semua';
        } else {
            label.textContent = vals.length + ' dipilih';
        }
    }

    function setOptionChecked(root, value, checked) {
        const opt = root.querySelector(`[data-ms-option][data-value="${value}"]`);
        if (!opt) return;
        const cb = opt.querySelector('[data-ms-checkbox]');
        const svg = `<svg class="text-white" style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>`;
        if (checked) {
            opt.classList.add('ms-checked');
            cb.classList.add('border-blue-600', 'bg-blue-600');
            cb.classList.remove('border-slate-300', 'bg-white');
            cb.innerHTML = svg;
        } else {
            opt.classList.remove('ms-checked');
            cb.classList.remove('border-blue-600', 'bg-blue-600');
            cb.classList.add('border-slate-300', 'bg-white');
            cb.innerHTML = '';
        }
    }

    function submitFilters() {
        // Build query string from all filter roots
        const params = new URLSearchParams();
        roots.forEach(root => {
            const name = root.getAttribute('data-ms-name');
            const vals = currentSelection(root);
            if (vals.length > 0) {
                // multiple values → repeated param
                vals.forEach(v => params.append(name, v));
            }
            // if empty, omit the param entirely (controller treats missing = Semua)
        });
        const qs = params.toString();
        window.location.href = DASHBOARD_URL + (qs ? '?' + qs : '');
    }

    roots.forEach(root => {
        const trigger = root.querySelector('[data-ms-trigger]');
        const panel = root.querySelector('[data-ms-panel]');
        const chevron = root.querySelector('[data-ms-chevron]');
        const selectAll = root.querySelector('[data-ms-select-all]');
        const options = [...root.querySelectorAll('[data-ms-option]')];

        options.forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.stopPropagation();
                const val = opt.getAttribute('data-value');
                const isNowChecked = !opt.classList.contains('ms-checked');
                // 1. Toggle visual checkbox
                setOptionChecked(root, val, isNowChecked);
                // 2. Rebuild hidden inputs based on new DOM state
                rebuildHiddenInputs(root);
                // 3. Update trigger label
                updateTriggerLabel(root);
                // 4. Keep panel OPEN — user can multi-check. Navigasi otomatis
                //    hanya terjadi saat panel ditutup (klik trigger / klik
                //    luar / tombol Terapkan).
            });
        });

        if (selectAll) {
            selectAll.addEventListener('click', (e) => {
                e.stopPropagation();
                const vals = options.map(o => o.getAttribute('data-value'));
                const allChecked = vals.every(v => root.querySelector(`[data-ms-option][data-value="${v}"]`).classList.contains('ms-checked'));
                if (allChecked) {
                    vals.forEach(v => setOptionChecked(root, v, false));
                } else {
                    vals.forEach(v => setOptionChecked(root, v, true));
                }
                rebuildHiddenInputs(root);
                updateTriggerLabel(root);
            });
        }

        // Eksplicit "Terapkan" button at bottom of panel
        const apply = root.querySelector('[data-ms-apply]');
        if (apply) {
            apply.addEventListener('click', (e) => {
                e.stopPropagation();
                panel.classList.add('hidden');
                chevron.classList.remove('rotate-180');
                closeAllPanels(root);
                clearTimeout(debounceTimer);
                submitFilters();
            });
        }

        // Navigasi otomatis saat panel ditutup oleh klik trigger (toggle close)
        const triggerOrigHandler = () => {
            const isOpen = !panel.classList.contains('hidden');
            closeAllPanels(root);
            if (isOpen) {
                panel.classList.add('hidden');
                chevron.classList.remove('rotate-180');
                // Panel baru ditutup → submit
                clearTimeout(debounceTimer);
                submitFilters();
            } else {
                panel.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            }
        };
        trigger.removeEventListener('click', trigger._bound);
        trigger._bound = triggerOrigHandler;
        trigger.addEventListener('click', triggerOrigHandler);

        // Navigasi otomatis saat klik di luar panel
        document.addEventListener('click', (e) => {
            // Don't close/submit when the click originated inside this panel
            // or on its trigger (the trigger handler already manages that).
            if (panel.contains(e.target) || trigger.contains(e.target)) return;
            if (!panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
                chevron.classList.remove('rotate-180');
                clearTimeout(debounceTimer);
                submitFilters();
            }
        });
    });

})();

/* ===== Charts ===== */
const rupiah = (v) => 'Rp ' + (v / 1e9).toFixed(1) + ' M';
const rupiahFull = (v) => 'Rp ' + Math.round(v / 1e6).toLocaleString('en-US', {maximumFractionDigits: 1}) + ' M';

const chartKabLabels = @json(collect($perKabupaten)->pluck('kabupaten')->all());
const chartKabData = @json(collect($perKabupaten)->map(fn($x) => $x['sisa'])->all());
new Chart(document.getElementById('chartKab'), {
    type: 'bar',
    data: {
        labels: chartKabLabels,
        datasets: [{
            label: 'Sisa Utang',
            data: chartKabData,
            backgroundColor: '#EF4444',
            borderRadius: 3,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { callback: (v) => rupiah(v) }, grid: { color: '#f1f5f9' } },
            y: { grid: { display: false } }
        }
    }
});

const pajakLabels = @json(collect($perJenisPajak)->pluck('nama')->all());
const pajakDibayar = @json(collect($perJenisPajak)->map(fn($x) => $x['dibayar'])->all());
const pajakSisa = @json(collect($perJenisPajak)->map(fn($x) => $x['sisa'])->all());
new Chart(document.getElementById('chartPajak'), {
    type: 'bar',
    data: {
        labels: pajakLabels,
        datasets: [
            { label: 'Dibayar', data: pajakDibayar, backgroundColor: '#10B981', borderRadius: 4 },
            { label: 'Sisa', data: pajakSisa, backgroundColor: '#EF4444', borderRadius: 4 },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } } },
        scales: {
            x: { stacked: true, grid: { display: false } },
            y: { stacked: true, ticks: { callback: (v) => rupiah(v) }, grid: { color: '#f1f5f9' } }
        }
    }
});

const twLabels = @json(collect($perTriwulan)->pluck('label')->all());
const twUtang = @json(collect($perTriwulan)->map(fn($x) => $x['utang'])->all());
const twBayar = @json(collect($perTriwulan)->map(fn($x) => $x['pembayaran'])->all());
new Chart(document.getElementById('chartTriwulan'), {
    type: 'line',
    data: {
        labels: twLabels,
        datasets: [
            { label: 'Nilai Utang', data: twUtang, borderColor: '#2563EB', backgroundColor: '#2563EB20', tension: 0.3, fill: true },
            { label: 'Dibayar', data: twBayar, borderColor: '#10B981', backgroundColor: '#10B98120', tension: 0.3, fill: true },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } } },
        scales: {
            y: { ticks: { callback: (v) => rupiah(v) }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});

const doughnutLabels = @json(collect($proporsi)->pluck('nama')->all());
const doughnutData = @json(collect($proporsi)->map(fn($x) => $x['sisa'])->all());
const doughnutColors = @json($doughnutColors);
new Chart(document.getElementById('chartDoughnut'), {
    type: 'doughnut',
    data: {
        labels: doughnutLabels,
        datasets: [{ data: doughnutData, backgroundColor: doughnutColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '62%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
