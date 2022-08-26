(function () {
    console.log('this works');

    let courseStartDate = document.getElementById("course-start-date");
        let courseEndDate = document.getElementById("course-end-date");
        let courseNameInput = document.getElementById("course-name");

    if ( document.getElementById("course-start-date") && document.getElementById("course-end-date"))    
    {

        console.log('the datepicker is present - validate it');
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        courseStartDate.setAttribute('min', today);
        courseStartDate.addEventListener('change', () => {
            courseEndDate.setAttribute('min', courseStartDate.value);
        });
    }

})();