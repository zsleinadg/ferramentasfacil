document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
            var bsAlert = new bootstrap.Alert(alert);
            setTimeout(function () { bsAlert.close(); }, 5000);
        });
    }, 100);
});

document.querySelectorAll('input[name="phone"]').forEach(function (input) {
    input.addEventListener('input', function () {
        var value = this.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        if (value.length > 6) {
            this.value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7);
        } else if (value.length > 2) {
            this.value = '(' + value.slice(0, 2) + ') ' + value.slice(2);
        } else if (value.length > 0) {
            this.value = '(' + value;
        }
    });
});
