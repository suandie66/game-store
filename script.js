document.addEventListener('DOMContentLoaded', function () {
    const priceInput = document.getElementById('price');
    if (!priceInput) return;

    const formatNumber = (value) => {
        value = value.replace(/\D/g, '');
        return value ? new Intl.NumberFormat('id-ID').format(value) : '';
    };

    // Format awal (edit page)
    priceInput.value = formatNumber(priceInput.value);

    priceInput.addEventListener('input', function () {
        const start = this.selectionStart;
        const beforeLength = this.value.length;

        this.value = formatNumber(this.value);

        const afterLength = this.value.length;
        const diff = afterLength - beforeLength;
        this.setSelectionRange(start + diff, start + diff);
    });

    // Bersihkan titik saat submit
    const form = priceInput.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            priceInput.value = priceInput.value.replace(/\./g, '');
        });
    }
});
