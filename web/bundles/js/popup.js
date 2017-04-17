var Popup = (function(){
    var $popup = $('#popup-template');

    var _init = function() {
        $('[data-popup-open]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('data-popup-open');
            $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

            e.preventDefault();

            $popup.find('.form-content').html('<p>Gegevens ophalen ...</p>')
        });

        //----- CLOSE
        $('[data-popup-close]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('data-popup-close');
            $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

            e.preventDefault();

            // Hide all content.
            $popup.find('.header-content').addClass('hidden');
            $popup.find('.submit-task').addClass('hidden');
            $popup.find('.confirm-delete').addClass('hidden');
        });
    };

    var _create = function () {
        // Set click events();
        $('.create-button').on('click', function(){
            setData($(this));
        });

        var setData = function($button) {
            // Set title
            $popup.find('.popup-title').html($button.data("popup-title"));

            // Retrieve form
            $.ajax({
                type: "GET",
                url: $button.data('action')
            }).done(function (data) {
                console.log(data);
                $popup.find('.form-content').html(data);

                $popup.find('.time-start').timepicker({
                    minuteStep: 1,
                    showMeridian: false,
                    defaultTime: false
                });

                $('#popup-form').find('.date').val($button.data('date'));

                // Show the right content.
                $popup.find('.header-content').removeClass('hidden');
                $popup.find('.submit-task').removeClass('hidden');
                $popup.find('.confirm-delete').addClass('hidden');


                // Add submit event
                $popup.find('.submit-button').click(function() {
                    submitForm($button.data('action'));
                })
            })
        };

        var submitForm = function (url) {
            console.log('submitting');
            // Submit data.
            $.ajax({
                type: "POST",
                url: url,
                data: $('#popup-form').serialize()
            }).done(function (data) {
                if(data.hasOwnProperty('redirect')) {
                    window.location = data.redirect;
                }
            })
        }
    };

    var _edit = function () {
        // Set click events();
        $('.edit-button').on('click', function(){
            setData($(this));
        });

        var setData = function($button) {
            // Set title
            $popup.find('.popup-title').html('Taak wijzigen');
            console.log($button.data('action'));

            // Retrieve form
            $.ajax({
                type: "GET",
                url: $button.data('action')
            }).done(function (data) {
                console.log(data);
                $popup.find('.form-content').html(data);

                $popup.find('.time-start').timepicker({
                    minuteStep: 1,
                    showMeridian: false,
                    defaultTime: false
                });

                $('#popup-form').find('.date').val($button.data('date'));


                // Show the right content.
                $popup.find('.header-content').removeClass('hidden');
                $popup.find('.submit-task').removeClass('hidden');
                $popup.find('.confirm-delete').addClass('hidden');

                // Add submit event
                $popup.find('.submit-button').click(function() {
                    submitForm($button.data('action'));
                })
            })
        };

        var submitForm = function (url) {
            console.log('submitting');
            // Submit data.
            $.ajax({
                type: "POST",
                url: url,
                data: $('#popup-form').serialize()
            }).done(function (data) {
                if(data.hasOwnProperty('redirect')) {
                    window.location = data.redirect;
                }
            })
        }
    };

    var _delete = function () {
        // Set click events();
        $('.delete-button').on('click', function(){
            setData($(this));
        });

        var setData = function($button) {
            // Set title
            $popup.find('.popup-title').html($button.data('popup-title'));

            $popup.find('.form-content').html(
                $button.data("popup-description")
            );


            // Show the right content.
            $popup.find('.header-content').removeClass('hidden');
            $popup.find('.submit-task').addClass('hidden');
            $popup.find('.confirm-delete').removeClass('hidden');

            // Add submit event
            $popup.find('.submit-button').click(function() {
                submitForm($button.data('action'));
            })
        };

        var submitForm = function (url) {
            // Submit data.
            $.ajax({
                type: "GET",
                url: url,
            }).done(function (data) {
                if(data.hasOwnProperty('redirect')) {
                    window.location = data.redirect;
                }
            })
        }
    };

    var _profileInvite = function () {
        $form = $('#profile-invite-form').clone();
        // Set click events();


        $('.invite-button').on('click', function(){
            setData($(this));
        });


        var setData = function ($button) {

            // Add submit event
            $('#profile-invite').find('.submit-button').click(function() {
                submitForm($button.data('action')+ '?emailaddress='+ $('#emailaddress').val());
            })
        };


        var submitForm = function (url) {
            console.log(url);

            // Submit data.
            $.ajax({
                type: "GET",
                url: url
            }).done(function (data) {
                console.log(data);
            })
        }
    };

    return {
        delete: function() {
            _init();
            _delete();
        },
        create: function() {
            _init();
            _create();
        },
        edit: function(m, y) {
            _init();
            _edit();
        },
        profileInvite: function(){
            _profileInvite();
        }
    };
})();
