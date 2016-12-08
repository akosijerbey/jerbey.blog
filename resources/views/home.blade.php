@extends('layouts.app')

@section('content')
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
<style type="text/css">

    #messages{

        border: 1px solid black;

        height: 300px;

        margin-bottom: 8px;

        overflow: scroll;

        padding: 5px;

    }

</style>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Chat-Lobby</div>
                <div class="panel-body">
                <div class="row">
                    <div class="col-lg-8" >
                      <div id="messages" ></div>
                      <span id="notifyUser"></span>
                    </div>
                    <div class="col-lg-4" >
                        <div class="form-group">
                            <div class="input-group">
                              <input type="text" class="form-control" placeholder="name of room" name="room">
                              <span class="input-group-btn">
                                <button class="btn btn-default create-room" type="button">Create room!</button>
                              </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <h4 class="list-group-item-heading">Room(s)</h4>
                            <ul class="list-group list-room">
                                <button type="button" class="list-group-item" data-room="room1">Room 1</button>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8" >
                        <form action="" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                            <input type="hidden" name="user" value="{{ Auth::user()->name }}" >
                            <textarea class="form-control msg"></textarea>
                            <br/>
                            <input type="button" value="Send" class="btn btn-success send-msg">
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var socket = io.connect('http://localhost:8890');
    socket.on('message', function (data) {
        data = jQuery.parseJSON(data);
        console.log(data.user);
        $( "#messages" ).append( "<strong>"+data.user+":</strong><p>"+data.message+"</p>" );
    });

    socket.on('room', function (data) {
        data = jQuery.parseJSON(data);
        $( ".list-room" ).append( '<button type="button" class="list-group-item" data-room="room1">Room 1</button>' );
    });

    $(".send-msg").click(function(e){
        e.preventDefault();
        var token = $("input[name='_token']").val();
        var user = $("input[name='user']").val();
        var msg = $(".msg").val();
        if(msg != ''){
            $.ajax({
                type: "POST",
                url: '{{URL::Route('sendMessage')}}',
                dataType: "json",
                data: {'_token':token,'message':msg,'user':user},
                success:function(data){
                    $(".msg").val('');
                }
            });
        }else{
            alert("Please Add Message.");
        }
    });

     $(".create-room").click(function(){
        var token = $("input[name='_token']").val();
        var user = $("input[name='user']").val();
        var room = $("input[name='room']").val();
        if(room != ''){
            $.ajax({
                type: "POST",
                url: '{{URL::Route('createRoom')}}',
                dataType: "json",
                data: {'_token':token,'room':room,'user':user},
                success:function(data){
                    $("input[name='room']").val('');
                }
            });
        }
        else{
            alert("Please add name of the room.");
        }
     });

    $(".msg").keydown(function(e) {
        if ($(this).is( ":focus" )) {
            if(e.which == 13)
            {
                //console.log('is send');
            }else{
                notifyTyping();
                 //$('#notifyUser').remove();
            }
        }
        else{

        }
    });

    function notifyTyping() { 
      var user = $("input[name='user']").val();
      socket.emit('notifyUser', user);
    }
    
    socket.on('notifyUser', function(user){
      var me = $("input[name='user']").val();;
      if(user != me) {
        $('#notifyUser').text(user + ' is typing ...');
      }
      setTimeout(function(){ $('#notifyUser').text(''); }, 10000);;
    });
</script>
@endsection
