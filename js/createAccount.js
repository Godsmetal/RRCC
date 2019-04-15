function confirmPassword(){
    var passwordRegex = /^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;
    var correctPasswordComplexity = passwordRegex.test($("#password").val());
    if (($('#password').val() == $('#passwordConfirm').val()) && correctPasswordComplexity) {
        $(".pwIcon").css('color', 'green');
        return true;
    }
    else{
        $('.pwIcon').css('color', 'red');
        return false;
    }
}

function nextStep(num) {
    ///First step of form is being submitted.
    //check phone and pass
    if (num == 1) {
        //first check to make sure phone number passes regex
        //regex= xxx-xxx-xxxx
        var phoneRegex = /^\d{3}-\d{3}-\d{4}$/;
        var correctPhone = phoneRegex.test($("#phoneNumber").val());

        //if it passes the regex check against the DB to make sure its unique
        if (correctPhone) {
            $.ajax({
                type: "GET",
                url: '../phpScripts/createAccountAjax.php',
                data: {
                    phone:$("#phoneNumber").val()
                },
                async: false
            }).done(function(numTaken){
                if (parseInt(numTaken)){
                    correctPhone = false;
                }
            });
        }
        //set the color of the icon based on phone correct or incorrect
        phoneColor = (correctPhone) ? ('green') : ('red');
        $('.fa-phone').css('color', phoneColor);
        //call function to check if passwords match
        var passwordConfirmTF = confirmPassword();

        //if phone and passwords are correct go to the next page
        if (passwordConfirmTF && correctPhone){
            $("#formStep1").hide(1000);
            $("#formStep2").show(1000);
            //Hide current circle-dot, show next
            $("#circle1").hide();
            $("#circle2").show();
            $("#dot1").show();
            $("#dot2").hide();
        }

    }

    //second step of form is being submitted. check names
    if (num==2){
        //check to make sure names pass regex
        //regex= only letters and between 2-30 chars
        var nameRegex = /^[a-zA-Z]{2,30}$/;
        var fNameCorrect = nameRegex.test($("#fName").val());
        var lNameCorrect = nameRegex.test($("#lName").val());

        //set the first name  to red or green based on true

        fNameColor = (fNameCorrect) ? ('green') : ('red');
        $($(".fa-address-card")[0]).css('color',fNameColor);

        lNameColor = (lNameCorrect) ? ('green') : ('red');
        $($(".fa-address-card")[1]).css('color',lNameColor);

        if (fNameCorrect && lNameCorrect){
            //check to make sure names are valid
            $("#formStep2").hide(1000);
            $("#formStep3").show(1000);

            //Hide current circle-dot, show next
            $("#circle2").hide();
            $("#circle3").show();
            $("#dot2").show();
            $("#dot3").hide();
        }
    }
}

function validateLastStep(){
    //check to make sure email passes regex
    var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    var emailCorrect = emailRegex.test($("#email").val());
    var deptID = $("select[name='deptID'] option:selected").val();
    var deptIDCorrect = (deptID != "");

    //email made it past regex check. Check against db
    if (emailCorrect){
        $.ajax({
            type: "GET",
            url: '../phpScripts/createAccountAjax.php',
            data: {
                email:$("#email").val()
            },
            async: false
        }).done(function(emailTaken){
            if (parseInt(emailTaken)){
                emailCorrect = false;
            }
        });
    }

    emailColor = (emailCorrect) ? ('green') : ('red');
    $(".fa-user").css('color',emailColor);

    deptColor = (deptIDCorrect) ? ('green') : ('red');
    $(".fa-building").css('color',deptColor);

    if (emailCorrect && deptIDCorrect) {
        submitForm();
    }
}

function submitForm(){
    //DONT FORGET TO REMOVE HYPHENS FROM PHONE NUMBER
    var subPhone = $("#phoneNumber").val();
    subPhone = subPhone.replace(/-/g,'');
    var subPassword = $('#password').val();
    var subFName = $("#fName").val();
    var subLName = $("#lName").val();
    var subEmail = $("#email").val();
    var subDeptID = $("select[name='deptID'] option:selected").val();

    $.ajax({
        type: "POST",
        url: '../phpScripts/createAccountAjax.php',
        data: {
            phoneNumber:subPhone,
            password:subPassword,
            fName: subFName,
            lName: subLName,
            email:subEmail,
            deptID: subDeptID
        }
    }).done(function(){
        window.location.href = "../index.php"
    }).fail(function(data){
        console.log("fail");
    });
}
