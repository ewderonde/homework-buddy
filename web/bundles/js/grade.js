var GradeChart = (function() {
    var _new = function (el, data) {
        var grades = _calculateGrades(data);
        console.log(grades);
        // Morris Area Chart
        Morris.Area({
            element: el,
            data: grades,
            // The name of the data record attribute that contains x-values.
            xkey: 'grade',
            ymax: 10,
            stacked: true,
            // A list of names of data record attributes that contain y-values.
            ykeys: ['average'],
            // Labels for the ykeys -- will be displayed when you hover over the chart.
            labels: ['Gemiddelde'],

            lineWidth: 3,
            hideHover: 'auto',
            parseTime: false,
            lineColors: ["#81d5d9", "#a6e182"]
        });
    };


    var _calculateGrades = function (data) {
        var gradesRaw = JSON.parse(data);
        var grades = [];
        var gradeCount = 0;
        var gradeTotal = 0;

        for(var i = 0; i < gradesRaw.length; i++) {
            gradeTotal += gradesRaw[i];
            gradeCount++;

            // Round up decimals.
            var averageRaw = gradeTotal / gradeCount;
            var average = Math.round( averageRaw * 10 ) / 10;

            var grade = {
                average: average,
                grade: Math.round( gradesRaw[i] * 10 ) / 10
            };

            grades.push(grade);
        }
        return grades;
    };

    return {
        new: function(el, data) {
            _new(el, data);
        }
    };
})();