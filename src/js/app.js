document.addEventListener('DOMContentLoaded', () => {

    // --- Confirm delete ---
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Удалить запись?')) {
                e.preventDefault();
            }
        });
    });

    // --- Confirm clear all ---
    document.querySelectorAll('.clear-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Очистить всю историю?')) {
                e.preventDefault();
            }
        });
    });

});
