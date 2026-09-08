@props([
    'id' => 'error-modal',
    'title' => 'Terjadi Kesalahan',
    'buttonText' => 'Mengerti & Tutup',
])

@php
    $hasSessionError = session('error');
    $hasValidationErrors = isset($errors) && $errors->any();
    $autoShow = $hasSessionError || $hasValidationErrors;
@endphp

<div id="{{ $id }}"
     class="fixed inset-0 z-50 {{ $autoShow ? 'flex opacity-100 pointer-events-auto' : 'hidden opacity-0 pointer-events-none' }} items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all duration-300 ease-out"
     role="dialog"
     aria-modal="true"
     aria-labelledby="{{ $id }}-title">
    <div class="relative w-full max-w-md transform transition-all duration-300 {{ $autoShow ? 'scale-100' : 'scale-95' }} overflow-hidden rounded-3xl bg-white p-6 shadow-[0_25px_70px_-15px_rgba(220,38,38,0.25)] ring-1 ring-slate-200/80">
        <!-- Close Button -->
        <button type="button"
                onclick="closeErrorModal('{{ $id }}')"
                class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors"
                aria-label="Tutup popup kesalahan">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>

        <div class="flex flex-col items-center text-center pt-2">
            <!-- 3D Floating Elevated Red Error Badge -->
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 ring-4 ring-red-100 border border-red-200 shadow-lg shadow-red-500/20">
                <span class="material-symbols-outlined text-3xl">error_outline</span>
            </div>

            <!-- Title -->
            <h3 id="{{ $id }}-title" class="font-display text-xl font-extrabold text-slate-900 leading-snug error-modal-title">
                {{ $title }}
            </h3>

            <!-- Message / List of Causes -->
            <div id="{{ $id }}-body" class="mt-3 text-sm leading-relaxed text-slate-600 error-modal-body w-full text-left bg-red-50/60 border border-red-100 rounded-2xl p-4">
                @if ($hasSessionError)
                    <p class="font-semibold text-red-800">{{ session('error') }}</p>
                @endif

                @if ($hasValidationErrors)
                    <div class="{{ $hasSessionError ? 'mt-3 border-t border-red-200/70 pt-2' : '' }}">
                        <p class="font-bold text-red-900 mb-1.5 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base">warning</span>
                            Mohon perbaiki kendala berikut:
                        </p>
                        <ul class="space-y-1 pl-1 text-xs text-red-700">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start gap-1.5">
                                    <span class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <button type="button"
                    onclick="closeErrorModal('{{ $id }}')"
                    class="btn btn-danger w-full py-3 rounded-xl text-sm font-bold shadow-md shadow-red-600/20 hover:shadow-lg transition-all">
                {{ $buttonText }}
            </button>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        window.closeErrorModal = function(modalId = 'error-modal') {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            const card = modal.firstElementChild;
            card?.classList.remove('scale-100');
            card?.classList.add('scale-95');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 250);
        };

        window.showErrorModal = function(options = {}) {
            const modalId = options.id || 'error-modal';
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const titleEl = modal.querySelector('.error-modal-title');
            const bodyEl = modal.querySelector('.error-modal-body');

            if (options.title && titleEl) {
                titleEl.textContent = options.title;
            }

            if (options.message && bodyEl) {
                let html = `<p class="font-semibold text-red-800">${options.message}</p>`;
                if (Array.isArray(options.causes) && options.causes.length > 0) {
                    html += `
                        <div class="mt-3 border-t border-red-200/70 pt-2">
                            <p class="font-bold text-red-900 mb-1.5 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base">warning</span>
                                Detail kendala:
                            </p>
                            <ul class="space-y-1 pl-1 text-xs text-red-700">
                                ${options.causes.map(c => `
                                    <li class="flex items-start gap-1.5">
                                        <span class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>
                                        <span>${c}</span>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    `;
                }
                bodyEl.innerHTML = html;
            }

            modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            modal.classList.add('flex', 'opacity-100', 'pointer-events-auto');
            const card = modal.firstElementChild;
            setTimeout(() => {
                card?.classList.remove('scale-95');
                card?.classList.add('scale-100');
            }, 10);
        };
    </script>
    @endpush
@endonce
