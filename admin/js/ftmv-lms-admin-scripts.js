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

    /*
        Logic for confirmation of programme deletion
    */
    if (document.getElementById("ftmv_delete_programme"))
    {
        let programmeDeleteForm = document.getElementById("ftmv_delete_programme");    
        programmeDeleteForm.addEventListener("submit", function(event) 
        {   
            event.preventDefault();
            if (confirm("Are you sure you want to delete this programme? \n\nDeleting a programme will delete all students and facilitators associated with the programme")) {            
                programmeDeleteForm.submit();
            }           
        });    
    }
    

    /*
        Logic for confirmation of course deletion
    */
    if (document.getElementById("ftmv_delete_course"))
    {
        let courseDeleteForm = document.getElementById("ftmv_delete_course");
    
        courseDeleteForm.addEventListener("submit", function(event) 
        {   
            event.preventDefault();
            if (confirm("Are you sure you want to delete this course?")) {            
                courseDeleteForm.submit();
            }           
        });
    }

    /* 
        Page / post admin panel functionality
    */
        if (document.querySelector('.ftmv-lms-admin-panel-select-container'))    
        {
            let adminPanelSelectContainer = document.querySelector('.ftmv-lms-admin-panel-select-container');

            /*  
            ftmv-lms-admin-panel-select-container-height */

            let adminPanelConfirm = document.querySelector('#ftmv-lms-admin-panel-confirm');
            adminPanelConfirm.addEventListener('change', function(){
                console.log('changing');
                adminPanelSelectContainer.classList.toggle('ftmv-lms-admin-panel-select-container-height');
            })
        }

    /* 
        Iframe Messaging Functionality
    */

    function sendMessageToIframe(section)
    {
        switch(section) {
            case 1:                                
                window.top.postMessage('first-message', '*');
                break;            
            case 2:                                
                window.top.postMessage('second-message', '*');
                console.log('second message sent');
                break;            
          }
        
    }

    if (document.querySelector('.iframe-step-one'))    
    {
        let firstStepButton = document.querySelector('.iframe-step-one');
        firstStepButton.addEventListener('click', () => {            
            sendMessageToIframe(1);
        })

    }

    if (document.querySelector('.iframe-step-two'))    
    {
        let secondStepButton = document.querySelector('.iframe-step-two');
        secondStepButton.addEventListener('click', () => {            
            sendMessageToIframe(2);
        })

    }
        

})();