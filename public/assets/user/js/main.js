// Login Page
$(function () {
    $("#login_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#login_submit').attr('disabled', true); // disable the login button
                $('#login_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // clear the invalid credential error message if any input field is changed
                    $('#login_form input').on('change', function () {
                        $('#login_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 2) {
                    $('#login_error').text('Invalid login credentials. Please try again.');
                } else {
                    window.location.href = '/';

                }
            },
            error: function () {
                $('#login_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#login_submit').attr('disabled', false); // enable the login button
                $('#login_submit').html('LOG IN'); // remove spinner from button
            }
        });
    });
});

// Register Page
$(function () {
    $("#register_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#register_button').attr('disabled', true); // disable the login button
                $('#register_button').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // clear the invalid credential error message if any input field is changed
                    $('#register_form input').on('change', function () {
                        $('#register_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 2) {
                    $('#register_error').text('Invalid login credentials. Please try again.');
                } else {
                    window.location.href = '/';

                }
            },
            error: function () {
                $('#register_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#register_button').attr('disabled', false); // enable the login button
                $('#register_button').html('Sign Up'); // remove spinner from button
            }
        });
    });
});

// Update Account
$(function () {
    $("#update_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#update_button').attr('disabled', true); // disable the login button
                $('#update_button').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // clear the invalid credential error message if any input field is changed
                    $('#update_form input').on('change', function () {
                        $('#update_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Details updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
            error: function () {
                $('#update_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#update_button').attr('disabled', false); // enable the login button
                $('#update_button').html('Save Changes'); // remove spinner from button
            }
        });
    });
});

// Contact Form
$(function () {
    $("#contact_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#contact_submit').attr('disabled', true); // disable the login button
                $('#contact_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear all existing error messages
                    $('span.error-text').text('');

                    // Display validation errors
                    $.each(data.error, function (field, messages) {
                        $('span.' + field + '_error').text(messages[0]);
                    });

                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Details Sent Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
            error: function () {
                $('#contact_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#contact_submit').attr('disabled', false); // enable the login button
                $('#contact_submit').html('Send Message'); // remove spinner from button
            }
        });
    });
});

// Add to Watchlist
$(function () {
    $("#watchlist_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#watchlist_submit').attr('disabled', true); // disable the login button
                $('#watchlist_submit').html('<i class="fa fa-spinner fa-spin"></i>'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to watchlist Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Already Added to watchlist !!',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
            error: function () {
                $('#watchlist_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#watchlist_submit').attr('disabled', false); // enable the login button
                $('#watchlist_submit').html('<span><i class="fa-solid fa-plus"></i></span>');
                // remove spinner from button
            }
        });
    });
});

// Remove Watchlist
$(function () {
    $("#watchlist_remove_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#watchlist_remove_submit').attr('disabled', true); // disable the login button
                $('#watchlist_remove_submit').html('<i class="fa fa-spinner fa-spin"></i>'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Item removed from Watchlist',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: () => {
                            // Reload the page after the SweetAlert is closed
                            location.reload();
                        }
                    });
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Watchlist item not found',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
            error: function () {
                $('#watchlist_remove_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#watchlist_remove_submit').attr('disabled', false); // enable the login button
                $('#watchlist_remove_submit').html('<i class="fa-solid fa-trash"></i>');
                // remove spinner from button
            }
        });
    });
});