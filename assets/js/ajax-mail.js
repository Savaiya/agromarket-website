$(function () {
    var form = $('#contact-form');
    if (!form.length) {
        return;
    }

    var formMessages = form.find('.form-message');
    var submitButton = form.find('button[type="submit"]');

    form.on('submit', function (event) {
        event.preventDefault();
        submitButton.prop('disabled', true);
        formMessages.removeClass('success error').text('Sending your message...');

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .done(function (response) {
            if (!response || !response.success) {
                formMessages.removeClass('success').addClass('error')
                    .text(response && response.message ? response.message : 'Your message could not be sent. Please try again.');
                return;
            }

            formMessages.removeClass('error').addClass('success')
                .text(response.message || 'Thank you. Your message has been sent successfully.');
            form[0].reset();
        })
        .fail(function (request) {
            var response = request.responseJSON;
            formMessages.removeClass('success').addClass('error')
                .text(response && response.message ? response.message : 'Your message could not be sent. Please try again later.');
        })
        .always(function () {
            submitButton.prop('disabled', false);
        });
    });
});