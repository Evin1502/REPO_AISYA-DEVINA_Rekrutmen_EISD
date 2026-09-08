<script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (hamburger && sidebar && overlay) {
        const open = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            // Let the browser register `hidden` -> `block` before animating opacity,
            // otherwise the fade-in transition is skipped entirely.
            requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
            document.body.classList.add('overflow-hidden');
        };
        const close = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            document.body.classList.remove('overflow-hidden');
            window.setTimeout(() => overlay.classList.add('hidden'), 200);
        };

        hamburger.addEventListener('click', open);
        overlay.addEventListener('click', close);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !overlay.classList.contains('hidden')) {
                close();
            }
        });
    }
</script>
