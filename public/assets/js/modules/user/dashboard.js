var auth_token;
function showTab( tabId ){
    $('.tab-pane').each(function(index, currentElement) {
        $(currentElement).removeClass( 'active' );
    });
    $('#' + tabId ).addClass( 'active' );
}
function listUsers(){
    getAPIToken().then(function(APITokenResult) {
        getUserList().then(function(usersListData) {
            // console.log( usersListData.users );
            $('#userListTableContent').html( buildUsersTableBody( usersListData.users ) );
            showTab( 'userlist' );
        });
    });
}
function getDetails( userId ){
    $('#updateUserId').val( userId );
    $('#updateUserName').val( $('#name' + userId).html() );
    $('#updateUserUsername').val( $('#username' + userId).html() );
    $('#updateUserPassword').val( $('#password' + userId).html() );
    showTab( 'updateUser' );
}
function loginUserTab( userId ){
    $('#loginUsername').val( $('#username' + userId).html() );
    $('#loginPassword').val( $('#password' + userId).html() );
    showTab( 'loginUser' );
}
function getAPIToken(){
    return $.get( getAPITokenURL, function( response ) {
        if( response.status ){
            auth_token = response.data.auth_token;
            $.ajaxSetup({
                headers:{
                    'X-AUTH-TOKEN': auth_token
                }
            } );
        }else{
            alert( 'Debug Point' );
        }
    });
}
function getUserList(){
    return $.get( getUsersURL, function( response ) {
        // if( response.status ){
        //     auth_token = response.data.auth_token;
        // }else{
        //     alert( 'Debug Point' );
        // }
    });
}
function getUserDetail( userId ){
    userDetailRoute = getUserURL.replace("{user_id}", userId);
    return $.get( userDetailRoute, function( response ) {
        if( response.status ){
            auth_token = response.data.auth_token;
        }else{
            alert( 'Debug Point' );
        }
    });
}
function createUserAction(){
    createUser().then(function(APICreateResult) {
        listUsers();
    });
}
function updateUserAction(){
    updateUser().then(function(APIUpdateResult) {
        listUsers();
    });
}
function loginUserAction(){
    loginUser().then(function(APILoginResult) {
        if( APILoginResult.authorized ){
            alert('Information is correct');
        }else{
            alert('Information is false');
        }
    });
}
function createUser(){
    return $.post( createUserURL, $("#createForm").serialize(), function( response ) {
        if( response.status ){
            auth_token = response.data.auth_token;
        }else{
            alert( 'Debug Point' );
        }
    });
}
function updateUser(){
    userUpdateRoute = updateUserURL.replace("{user_id}", $('#updateUserId').val());
    return $.ajax({
        url: userUpdateRoute,
        type: 'PUT',
        data: $("#updateForm").serialize(),
        success: function(data) {
            // alert('Load was performed.');
        }
    });
    // $.put( userUpdateRoute, $("#updateForm").serialize(), function( response ) {
    //     if( response.status ){
    //         auth_token = response.data.auth_token;
    //     }else{
    //         alert( 'Debug Point' );
    //     }
    // });
}
function loginUser(){
    return $.post( loginUserURL, $("#loginForm").serialize(), function( response ) {
        if( response.status ){
            auth_token = response.data.auth_token;
        }else{
            alert( 'Debug Point' );
        }
    });
}
function buildUsersTableBody( users ){
    tableContent = "";
    $.each( users,function( index, value ) {
        tableContent += "<tr>";
        tableContent += "<td id='name"+ value.id +"'>";
        tableContent += value.name;
        tableContent += "</td>";
        tableContent += "<td id='username"+ value.id +"'>";
        tableContent += value.username;
        tableContent += "</td>";
        tableContent += "<td id='password"+ value.id +"'>";
        tableContent += value.password;
        tableContent += "</td>";
        tableContent += "<td>";
        tableContent += value.created_at;
        tableContent += "</td>";
        tableContent += "<td>";
        tableContent += value.updated_at;
        tableContent += "</td>";
        tableContent += "<td>";
        tableContent += '<a class="btn btn-sm btn-primary" onClick="getDetails(' + value.id + ')">Details</a><a class="btn btn-sm btn-primary" onClick="loginUserTab(' + value.id + ')">Login</a>';
        tableContent += "</td>";
        tableContent += "</tr>";
    } );
    console.log( tableContent );
    return tableContent;
}
$( document ).ready(function() {
    listUsers();
});