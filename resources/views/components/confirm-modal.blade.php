@props([
    'id' => 'confirm-modal',
    'title' => 'Konfirmasi Tindakan',
    'message' => 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Tidak, Batalkan',
    'type' => 'danger', // danger, primary, admin, collector
    'icon' => 'help_outline',
])

@php
    $typeClasses = match ($type) {
        'danger' => [
            'iconBg' => 'bg-red-50 text-red-600 ring-red-100 border border-red-200 shadow-red-500/20',
            'btnConfirm' => 'btn-danger',
        ],
        'admin' => [
            'iconBg' => 'bg-indigo-50 text-indigo-600 ring-indigo-100 border border-indigo-200 shadow-indigo-500/20',
            'btnConfirm' => 'btn-admin',
        ],
        'collector' => [
            'iconBg' => 'bg-amber-50 text-amber-600 ring-amber-100 border border-amber-200 shadow-amber-500/20',
            'btnConfirm' => 'btn-collector',
        ],
        default => [
            'iconBg' => 'bg-brand-50 text-brand-600 ring-brand-100 border border-brand-200 shadow-brand-600/20',
            'btnConfirm' => 'btn-primary',
        ],
    };
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all duration-300 ease-out opacity-0 pointer-events-none">
    <div class="relative w-full max-w-md transform scale-95 overflow-hidden rounded-3xl bg-white p-6 shadow-[0_25px_70px_-15px_rgba(15,23,42,0.3)] ring-1 ring-slate-200/80 transition-all duration-300">
        <!-- Close Button -->
        <button type="button" data-modal-close="{{ $id }}" class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>

        <div class="flex flex-col items-center text-center pt-2">
            <!-- 3D Elevated Floating Icon Badge -->
            <div id="{{ $id }}-icon-container" class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl ring-4 shadow-lg {{ $typeClasses['iconBg'] }}">
                <span id="{{ $id }}-icon" class="material-symbols-outlined text-3xl">{{ $icon }}</span>
            </div>

            <h3 id="{{ $id }}-title" class="font-display text-xl font-extrabold text-slate-900 leading-snug modal-title">{{ $title }}</h3>
            <p id="{{ $id }}-message" class="mt-2 text-sm leading-relaxed text-slate-500 modal-message">{{ $message }}</p>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="button" data-modal-cancel="{{ $id }}" class="btn btn-secondary flex-1 py-3 text-sm rounded-xl font-bold">
                {{ $cancelText }}
            </button>
            <button type="button" data-modal-confirm="{{ $id }}" class="btn {{ $typeClasses['btnConfirm'] }} flex-1 py-3 text-sm rounded-xl font-bold">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        window.showConfirmModal = function(options = {}) {
            return new Promise((resolve) => {
                const modalId = options.id || 'confirm-modal';
                const modal = document.getElementById(modalId);
                if (!modal) {
                    resolve(false);
                    return;
                }

                const card = modal.firstElementChild;
                const titleEl = modal.querySelector('.modal-title');
                const messageEl = modal.querySelector('.modal-message');
                const confirmBtn = modal.querySelector('[data-modal-confirm]');
                const cancelBtn = modal.querySelector('[data-modal-cancel]');
                const closeBtn = modal.querySelector('[data-modal-close]');
                const iconEl = modal.querySelector('.material-symbols-outlined');

                if (options.title && titleEl) titleEl.textContent = options.title;
                if (options.message && messageEl) messageEl.textContent = options.message;
                if (options.confirmText && confirmBtn) confirmBtn.textContent = options.confirmText;
                if (options.cancelText && cancelBtn) cancelBtn.textContent = options.cancelText;
                if (options.icon && iconEl) iconEl.textContent = options.icon;

                // Adjust style classes dynamically if type passed
                if (options.type && confirmBtn) {
                    confirmBtn.className = 'btn flex-1 py-3 text-sm rounded-xl font-bold ';
                    if (options.type === 'danger') confirmBtn.classList.add('btn-danger');
                    else if (options.type === 'admin') confirmBtn.classList.add('btn-admin');
                    else if (options.type === 'collector') confirmBtn.classList.add('btn-collector');
                    else confirmBtn.classList.add('btn-primary');
                }

                const openModal = () => {
                    modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
                    modal.classList.add('flex', 'opacity-100', 'pointer-events-auto');
                    setTimeout(() => {
                        card?.classList.remove('scale-95');
                        card?.classList.add('scale-100');
                    }, 10);
                };

                const closeModal = (result) => {
                    card?.classList.remove('scale-100');
                    card?.classList.add('scale-95');
                    modal.classList.remove('opacity-100', 'pointer-events-auto');
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => {
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                        resolve(result);
                    }, 250);
                };

                const onConfirm = () => {
                    cleanup();
                    closeModal(true);
                };

                const onCancel = () => {
                    cleanup();
                    closeModal(false);
                };

                const cleanup = () => {
                    confirmBtn?.removeEventListener('click', onConfirm);
                    cancelBtn?.removeEventListener('click', onCancel);
                    closeBtn?.removeEventListener('click', onCancel);
                };

                confirmBtn?.addEventListener('click', onConfirm);
                cancelBtn?.addEventListener('click', onCancel);
                closeBtn?.addEventListener('click', onCancel);

                openModal();
            });
        };

        // Global Form Interceptor for data-confirm or legacy onsubmit="return confirm(...)"
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('submit', async (e) => {
                const form = e.target;
                if (!form || form.dataset.bypassedModal === 'true') return;

                let confirmMessage = form.dataset.confirm;
                let title = form.dataset.confirmTitle || 'Konfirmasi Kirim';
                let type = form.dataset.confirmType || 'primary';
                let icon = form.dataset.confirmIcon || 'send';
                let confirmText = form.dataset.confirmBtn || 'Ya, Kirim Sekarang';

                // Intercept legacy onsubmit confirm(...)
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (!confirmMessage && onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                    const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                    if (match && match[1]) {
                        confirmMessage = match[1];
                        if (confirmMessage.toLowerCase().includes('batal') || confirmMessage.toLowerCase().includes('hapus') || confirmMessage.toLowerCase().includes('tolak')) {
                            type = 'danger';
                            icon = 'warning';
                            title = 'Konfirmasi Tindakan';
                            confirmText = 'Ya, Lanjutkan';
                        }
                    }
                }

                if (confirmMessage) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    const confirmed = await window.showConfirmModal({
                        title: title,
                        message: confirmMessage,
                        confirmText: confirmText,
                        cancelText: 'Batal',
                        type: type,
                        icon: icon
                    });

                    if (confirmed) {
                        form.dataset.bypassedModal = 'true';
                        form.removeAttribute('onsubmit');
                        form.submit();
                    }
                }
            }, true);
        });
    </script>
    @endpush
@endonce

