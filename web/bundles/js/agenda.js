var Agenda = (function(){
    var _init = function () {
        $('.datepicker-toggle').click(function(event) {
            event.stopPropagation();
            $('.datepicker').toggleClass('hidden');

        });

        $(window).click(function() {
            //Hide the menus if visible
            $('.datepicker').addClass('hidden');
        });

        $('.datepicker').click(function(event){
            event.stopPropagation();
        });
    };

    var _initWeek = function (currentWeek, currentYear){
        var startDate;
        var endDate;

        var getDateOfISOWeek = function(w, y) {
            var simple = new Date(y, 0, 1 + (w - 1) * 7);
            var dow = simple.getDay();
            var ISOweekStart = simple;
            if (dow <= 4)
                ISOweekStart.setDate(simple.getDate() - simple.getDay() + 1);
            else
                ISOweekStart.setDate(simple.getDate() + 8 - simple.getDay());
            return ISOweekStart;
        };

        startDate = getDateOfISOWeek(currentWeek,currentYear);

        var month = ((startDate.getMonth()+1) <=9 )? '0' + (startDate.getMonth()+1) : (startDate.getMonth()+1);
        var formattedDate = startDate.getDate()+'-'+ month +'-'+startDate.getFullYear();

        var selectCurrentWeek = function(yearAndWeek) {
            window.setTimeout(function () {
                $('.datepicker').find('.ui-datepicker-current-day a').addClass('ui-state-active ui-state-hover');
            }, 1);

            // redirect if possible.
            if(yearAndWeek) {
                if(yearAndWeek[0] != undefined)
                    window.location = '?year='+yearAndWeek[0]+'&week='+yearAndWeek[1];
            }

        };

        $('.datepicker').datepicker( {
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd-mm-yy',
            defaultDate: formattedDate,
            firstDay: 1,
            onSelect: function(dateText, el) {
                var date = $(this).datepicker('getDate');
                startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - (date.getDay() - 1));
                endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
                var dateFormat = el.settings.dateFormat || $.datepicker._defaults.dateFormat;
                $('#startDate').text($.datepicker.formatDate( dateFormat, startDate, el.settings ));
                $('#endDate').text($.datepicker.formatDate( dateFormat, endDate, el.settings ));

                var yearAndWeek = getWeekNumber(startDate);

                selectCurrentWeek(yearAndWeek);
            },
            beforeShowDay: function(date) {
                var cssClass = '';
                if(date >= startDate && date <= endDate)
                    cssClass = 'ui-datepicker-current-day';
                return [true, cssClass];
            },
            onChangeMonthYear: function(year, month, inst) {
                setWeekRange();
            }
        });

        var setWeekRange = function () {
            var $datepicker = $('.datepicker');

            // Set current week.
            var $selectedDay = $datepicker.find('.ui-state-active');
            $selectedDay.removeClass('ui-state-hover');
            var $weekRow = $selectedDay.closest('tr');
            $weekRow.find('a').addClass('ui-state-active');


            // $(el.dpDiv).find('[data-year="'+year+'"][data-month="'+mon+'"]')
            var $elements = $datepicker.find('[data-year="'+currentYear+'"][data-month="'+month+'"]');
            console.log($elements);
        };


        var getWeekNumber = function(d) {
            // Copy date so don't modify original
            d = new Date(+d);
            d.setHours(0,0,0,0);
            // Set to nearest Thursday: current date + 4 - current day number
            // Make Sunday's day number 7
            d.setDate(d.getDate() + 4 - (d.getDay()||7));
            // Get first day of year
            var yearStart = new Date(d.getFullYear(),0,1);
            // Calculate full weeks to nearest Thursday
            var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
            // Return array of year and week number
            return [d.getFullYear(), weekNo];
        };

       $('.datepicker .ui-datepicker-calendar tr').on('mousemove', function() {
           $(this).find('td a').addClass('ui-state-hover');
       });
       $('.datepicker .ui-datepicker-calendar tr').on('mouseleave', function() {
           $(this).find('td a').removeClass('ui-state-hover');
       });

        // Set selected week on page load.
        setWeekRange();
    };



    var _initDay = function (date) {
        $('.datepicker').datepicker( {
            dateFormat: 'dd-mm-yy',
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            defaultDate: date,
            onSelect: function(dateText, inst) {
                var date = $(this).datepicker('getDate');

                window.location = '?day='+date.getDate() +'-'+(date.getMonth()+1)+'-'+2017
            }
        });
    };

    var _initMonth = function (month, year) {



        $('.datepicker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showToday: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            defaultDate: '1-'+month+"-"+year,
            onSelect: function(dateText, inst) {
                var date = $(this).datepicker('getDate');

                window.location = '?day='+date.getDate() +'-'+(date.getMonth()+1)+'-'+2017
            },
            onClose: function(dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });

        $('.datepicker').find('.ui-widget').empty();
        $('.datepicker').find('.ui-widget').css({'width': 480, 'padding': '15px'});
        $('.datepicker').css('z-index', 1000);

        var createMonthButtons = function() {
            // var button = $('<a></a>');
            // button.addClass('btn btn-primary btn-block');
            // button.css('color', '#FFFFFF');
            // button.html('Februari');

            var $buttons = $('.month-row-template').clone();
            $buttons.removeClass('hidden');
            $('.datepicker').find('.ui-widget').append($buttons);
            // 0099CC
        };

        createMonthButtons();
    };

    return {
        day: function(date) {
            _init();
            _initDay(date);
        },
        week: function(w, y) {
            _init();
            _initWeek(w, y);
        },
        month: function(m, y) {
            _init();
            _initMonth(m, y);
        }
    };
})();