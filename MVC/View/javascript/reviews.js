let selectedRating = 0;

document.querySelectorAll('.star-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        selectedRating = parseInt(this.dataset.value);
        document.getElementById('rating-input').value = selectedRating;
        document.querySelectorAll('.star-btn').forEach(function (b) {
            b.classList.toggle('lit', parseInt(b.dataset.value) <= selectedRating);
        });
    });
});

document.getElementById('submit-btn').closest('form').addEventListener('submit', function (e) {
    const prodID = document.getElementById('product-id').value;
    const body = document.getElementById('rev-body').value.trim();
    const errBox = document.getElementById('rev-err');

    if (!prodID || selectedRating === 0 || body.length < 3) {
        e.preventDefault();
        errBox.style.display = 'block';
        return;
    }

    errBox.style.display = 'none';
    document.getElementById('rating-input').value = selectedRating;
});