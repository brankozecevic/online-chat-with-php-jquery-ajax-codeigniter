/** 
  * @desc This js document is used for creating ajax jQuery functionality for online chat
  * @author Branko Zecevic
*/
$(document).ready(function(){
        //Hiding elements that represents errors
        $('#error1').hide();
        $('#error2').hide();

        getMessages();
        getUsers();

        //Validation of event
        $('#message').keyup(function(){
            checkMessage();
        });
        $('#send-message').click(function(event){
            event.preventDefault();
            let message = $('#message').val();
            if(message == '') return false;
            let validation = checkMessage();
            if(validation === true) return false;
            //Ajax jQuery request
           $.post(base_url+'chat/add-message', {user_id: user_id, message: message}, function(data){
               if(data[0] === 'invalid') window.location.href = base_url+'chat';             
           }, 'json');

           $('#message').val('');
           getMessages();     
        });
        //checking length and allowed characters for the Message field
        function checkMessage(){
            let messageLength = $('#message').val().length;
            if(messageLength >200){
                $('#error1').html('You can send up to 200 characters in the Message field.');
                $('#error1').show();
                return true;
            }else {
                $('#error1').hide();
            }
            let pattern = /^[-a-zA-Z0-9\s.:,@]*$/;
            if(pattern.test($('#message').val())){
                $('#error2').hide();
            }else{
                $('#error2').html('Characters allowed in the Message field are: . , : , - , , , @,  spaces , letters and numbers.');
                $('#error2').show();
                return true;
            }
        }
        /** 
        * Generating ajax request for showing all chat messages
        */
        function getMessages(){
            $.post(base_url+'all-messages', function(data){
                let noMsg = '<p class="alert alert-warning">No new messages.<p>';
                if(data[0] === 'No new messages.'){
                    $('#chat').html(noMsg);
                    return false;
                }
                let allMsg = '';
                $.each(data, function(i) {
                    allMsg += '<p class="alert alert-success"><i>'+data[i][2]+'</i>&nbsp; <strong>'+data[i][0]+'</strong> '+data[i][1]+'</p>';
                });
                $('#chat').html(allMsg);
            }, 'json');
        }
        /** 
        * Generating ajax request for showing current users
        */
        function getUsers(){
            $.post(base_url+'all-users', function(data){
                if(data.length == 0){
                    window.location.href = base_url+'chat';
                    return false;
                }
                let allUsers = '';
                 $.each(data, function(i){
                    allUsers += '<li class="list-group-item">'+ data[i].username +'</li>';
                 });    
                 $('#users').html(allUsers);     
            }, 'json');
        }
        /** 
        * Generating ajax request for checking if username exists
        */
        function checkUser(){
            $.post(base_url+'check-user', {userid: user_id}, function(data){
                if(data[0] === 'invalid') window.location.href = base_url+'chat';
            }, 'json');
        }
        setInterval(getUsers, 60*1000);
        setInterval(getMessages, 6*1000);
        setInterval(checkUser, 10*1000);
    } 
);