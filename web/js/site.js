$(document).ready(function() {
    let refreshButton = $('.refreshRates'),
        updateButton = $('.updateRates');
    updateButton.on('click', (e) => {
        e.preventDefault();
        e.target.disabled = 'disabled';
        let href = e.target.href;
        $.ajax({
            type: 'POST',
            cache: false,
            url: href,
            success: (res) => {
                refreshButton.click();
                alert('Успех');
            },
            error: (res) => {
                alert('Возникла ошибка');
            }
        });
    });
});
