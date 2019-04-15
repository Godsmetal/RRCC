function ajaxUpdate(userID,ele) {
    //this row is the parent of the parent of the parent (icon -> button -> td -> tr)
    var thisRow = $(ele).parent().parent().parent();
    //Use jquery to find the closest tr. Next is a spacer need to do it twice.
    var nextRow = $(thisRow).closest('tr').next('tr').next('tr');
    //Get the first name from the input in the top row
    var fName = $(thisRow).find("input[name='fName']").val();
    //Get the last name from the input in the top row
    var lName = $(thisRow).find("input[name='lName']").val();

    var email = $(nextRow).find("input[name='email']").val();

    var phone = $(nextRow).find("input[name='phone']").val();

    var activeYN = $(nextRow).find("select[name='activeYN'] option:selected").val();

    var deptID = $(nextRow).find("select[name='deptID'] option:selected").val();

    var authID = $(nextRow).find("select[name='authID'] option:selected").val();
    $.ajax({
        type: "POST",
        url: '../phpScripts/ajaxTarget.php',
        data: {
            userID:userID,
            fName: fName,
            lName: lName,
            email:email,
            phone:phone,
            activeYN: activeYN,
            deptID: deptID,
            authID: authID
        }
    }).done(function(){
        $(thisRow).find("input").attr("disabled", true);
        $(nextRow).find("input").attr("disabled", true);
        $(nextRow).find("select").attr("disabled", true);
        $(thisRow).find('#empEditButton').show();
        $(thisRow).find('#empSaveEditButton').hide();
    }).fail(function(data){
        console.log("fail");
    });
}

function ajaxDelete(searchID,ele,page) {
    if (page == 'csv'){
        data = {userID:searchID,delete:1,user:1};
    }
    if (page == 'news') {
        data = {notificationID:searchID,delete:1,noti:1};
    }
    $.ajax({
        type:"POST",
        url: '../phpScripts/ajaxTarget.php',
        data: data
    }).done(function(){
        if (page == 'csv'){
            //this row is the parent of the parent of the parent (button -> td -> tr)
            var thisRow = $(ele).parent().parent();
            //Use jquery to find the closest tr. Next is a spacer need to do it twice.
            var nextRow = $(thisRow).closest('tr').next('tr').next('tr');
            $(thisRow).hide();
            $(nextRow).hide();
        }
        if (page == 'news') {
            //find the container div that has this id and hide it
            $("#"+searchID).hide();
        }
    }).fail(function(){
        console.log("fail");
    });
}

function initSearch(event,ele,page){
    //this function is called on every key press.
    //only trigger search if button is enter
    if(event.which === 13){
        $(".searchBarDelete").remove();
        $.ajax({
            type: "GET",
            url: '../phpScripts/ajaxTarget.php',
            data: {
                search:$(ele).val(),
                page: page
            }
        }).done(function(data){
            $("#headerRow").after('<tr>'+data+'</tr>');
        });
    }
}
