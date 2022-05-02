

<div id="myModalVideoAudio" class="modal fade" role="dialog" >
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content user_block_modal_content encounterexceeds_modal user_videos_details" style="padding: 0;max-height: 450px;">
	    <video id="remoteVideo" autoplay style="width:100%;height: 100%;min-height: 350px;max-height: 450px;"></video>
      <div class="video_details">
<!--         	<img src="" height="30" width="30" id="usercall_details" /> -->
<!-- 			<span id="user_name"></span> -->
      </div>
      <div class="modal-body videochat_cnt" style="color: black">
	     
	      <div style="" class="">
            <video id="localVideo" autoplay muted ></video>
            
            
        </div>
        
     <!--
  <div>
        <label>Own SocketID</label>
        <input type="text" id="socket-id" value="" style="width:300px"/>
       </div>
-->
       
        <div class="video_calls_btns">
<!--         <input type="button" id="call" value="Call To"></input> -->
			
			<button type="button" id="call"  class="nocss" data-toggle="tooltip" title="{{trans('video_chat.call_the_user_tooltip')}}" ><img src="@plugin_asset('VideoChatPlugin/images/call.svg')" height="30" width="30" />
</button>
						
			
			
			<button type="button" id="stop" disabled data-toggle="tooltip"  title="{{trans('video_chat.stop_call_tooltip')}}" class="nocss"><img src="@plugin_asset('VideoChatPlugin/images/stop_call.svg')" height="30" width="30" />
</button>
						
			 <!--     <input type="button" id="stop-video"  value="Stop/start video"></input> -->
    
    <button type="button" id="stop-video" class="nocss" disabled data-toggle="tooltip" title="{{trans('video_chat.mute_video_tooltip')}}">
     <img src="@plugin_asset('VideoChatPlugin/images/video_yes.svg')" height="30" width="30" /></button>
   
    
    <button class="nocss" type="button" id="start-video"  disabled style="display: none" data-toggle="tooltip" title="{{trans('video_chat.unmute_video_tooltip')}}">
     <img src="@plugin_asset('VideoChatPlugin/images/video_no.svg')" height="30" width="30" />

    </button>
        
<!--         <input type="button" id="stop-audio"  value="Stop/start audio"></input> -->


<button type="button" class="nocss" id="stop-audio" disabled data-toggle="tooltip" title="{{trans('video_chat.mute_audio_tooltip')}}">
<img src="@plugin_asset('VideoChatPlugin/images/audio_yes.svg')" height="30" width="30" />
</button>
	
	
	<button type="button" class="nocss" id="start-audio"  disabled style="display: none"  data-toggle="tooltip" title="{{trans('video_chat.unmute_audio_tooltip')}}"><img src="@plugin_asset('VideoChatPlugin/images/audio_no.svg')" height="30" width="30" /></button>
	
        </div>
       <!--
 <div>
        <label>Other socket id</label>
        <input type="text" id="other-socket-id" value="" style="width:300px"></input>
        </div>
-->
        
        <div class="accept_reject_call" style="display: none">
	        
	       <div id="user_name_calling"></div>
<!--         <input type="button" id="accept" value="Accept"></input> -->
		<img src="@plugin_asset('VideoChatPlugin/images/accept_call.png')" height="50" width="50" id="accept" data-toggle="tooltip" title="{{trans('video_chat.accept_call_tooltip')}}"/>
<!--         <input type="button" id="reject"  value="Reject"></input> -->
		<img src="@plugin_asset('VideoChatPlugin/images/reject_call.png')" height="50" width="50" id="reject" data-toggle="tooltip" title="{{trans('video_chat.reject_call_tooltip')}}"/>
<!--         <input type="button" id="stop-call"  value="Stop call"></input> -->
       
       
       
   
        </div>
        
        <span id="call-status"></span>
        

			
        </div>
      <div class="" style="text-align: center">
       
        
             </div>
    </div>

  </div>
</div>



<div id="myModalIncomingCall" class="modal fade" role="dialog">
 <!--  <div class="modal-dialog" > -->

    <!-- Modal content-->
   <!--  <div class="modal-content user_block_modal_content encounterexceeds_modal" style="padding: 0;margin-top: 0px;background: none"> -->
           
      	<div class="login-form">
					<div class="head-info">
						<a href="#"><img src="" height="150" width="150" id="usr_img" style="object-fit:cover;border-radius: 50%;"/></a>
						<div class="head animated bounce"><a href="#"><img src="@plugin_asset('VideoChatPlugin/images/calling.svg')" height="40" weight="40" /></a>
					</div>
					</div>
						<div class="social-icons">
							<h2 id="usr_calling"></h2>
							<h3>Calling..</h3>
							<input id="from_user_id" type="hidden" />
							<ul class="bottom-buttons">
								<li>
									<a href="#/" class="accept_call">
										<i class="video"> </i>
										<p>Talk</p>
									</a>
								</li>
									<li>
									<a href="#/" class="top-close reject_call">
										<i class="close"> </i>
										<p>Later</p>
									</a>
								</li>
							</ul>
						</div>
			</div>
      
       
<!--     </div> -->

 <!--  </div> -->
</div>


@section('plugin-scripts')
@parent
<script type="text/javascript" src="@theme_asset('js/adapter-latest.js')"></script>

<link rel="stylesheet" href="@theme_asset('css/alertify.core.min.css')">

<link rel="stylesheet" href="@theme_asset('css/alertify.default.min.css')">

<script type="text/javascript" src="@theme_asset('js/alertify.min.js')"></script>

<style>
	.alertify.popup1 {
    background-image: url("@plugin_asset('VideoChatPlugin/images/calling.svg')");
	background-repeat: no-repeat;
	background-position: 8% 59%;
	background-size: 11%;
}
</style>	

<script>
App.filter('format_msg',function(){
	
	return function(msg)
	{
		
		if(msg.type==11)
		{
			
			//start call time
			var start_call_time = moment(JSON.parse(msg.meta).start_call).utc();
			
			if(JSON.parse(msg.meta).end_call)
				var end_call_time = moment(JSON.parse(msg.meta).end_call).utc();
			else
			    var end_call_time = moment(JSON.parse(msg.meta).start_call).utc();	
			
			console.log(end_call_time.diff(start_call_time, 'minutes'));
			
			
			var now  = end_call_time;
			var then = start_call_time;
			
			var ms = end_call_time.diff(start_call_time);
			var d = moment.duration(ms);
			var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
			
			
			
			var formattedText = "{{{trans('video_chat.call_duration')}}}"+' '+s;
			return formattedText;
			
		}
		
		else if( msg.type==10)
		{
			
			//var formattedText = msg.text+' '+msg.from_user;
			var scope = angular.element("#websocket_chat_modal").scope();
	        var user=scope.findUserByID(msg.from_user);
			
			if(msg.from_user == auth_user_id_chat)
				var formattedText =  "{{{trans('video_chat.missedcall_you')}}}";
			else
			   	var formattedText = "{{{trans('video_chat.missedcall_other')}}}"+' '+user.user.name;
			   	
			return formattedText;
			
		}
		
		else if(msg.type==12)
		{
			//var formattedText = msg.text+' '+msg.from_user;
			var scope = angular.element("#websocket_chat_modal").scope();
	        var user=scope.findUserByID(msg.to_user);
			
			if(msg.from_user == auth_user_id_chat)
				var formattedText = "{{{trans('video_chat.call_rejected_other')}}}"+' '+user.user.name;
			else
			   var formattedText = "{{{trans('video_chat.call_rejected_you')}}}";	
			   	
			   	

			return formattedText;
			
		}
		
	}
	
	
}) 

</script>

<script type="text/javascript">
	
	
	
	 
	 $(document).ready(function(){
		 
		 
		 
		 
		 
		 
		 
		
		 
		 localStream={};
		 remoteStream={};
		 
		 var audio = new Audio("@plugin_asset('VideoChatPlugin/audio/Incoming_Call.mp3')");
		 
		 
		 $('.chat_video_audio').on('click',function(){
			 
			 $('#websocket_chat_modal').modal('hide');
	
		     var scope = angular.element("#websocket_chat_modal").scope();
		     
		     getChatUsers(scope.current_to_user_id,function(user){
			     
			     
			     
			     var user = user;
			 
				 if(user.user.can_init_chat)
				 {
					$('#myModalVideoAudio').modal('show');
					 
					 
					 
					 
					 
					 $('#user_name').text(user.user.name);
					 
					 //$('#usercall_details').attr('src',user.user.profile_picture);
					 
					 //$('.usercall_details').attr('src',user.user.profile_picture);
					 
					 $('.user_videos_details').css("background", "url("+base_url+"/uploads/others/original/"+user.user.profile_pic_url+") no-repeat center");
					 
					 $('.user_videos_details').css("background-size", "cover");
					 
					 
				 }
				 else
				 {
					   if(user.user.init_chat_error_type === 'CHAT_INIT_HOURS_EXPIRED')
				        {
					         
					        $('#myModalExceedsChatHourExpired').modal('show');
				        }
				        else if(user.user.init_chat_error_type === 'CHAT_LIMIT_OF_DAY')
				        {
					        $('#myModalExceedsChatLimit').modal('show');
				        }
	
				 }
					
					
				
			     
			     
		     });
		     
		     
		     
				
			 
		})
		 
		 // Taken from http://stackoverflow.com/a/105074/515584
            // Strictly speaking, it's not a real UUID, but it gets the job done here
            function createUuid() {
                function s4() {
                return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
                }
            
                return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
            } 
            
            
            var calling_status= 0;
			
			var call_init_obj={};
			
			 var caller = false;
			
			 var localVideo;
            var remoteVideo;
            var peerConnection;
            var uuid;
            
            
            
            var peerConnectionConfig = {
                'iceServers': [
                    {'urls': 'stun:stun.services.mozilla.com'},
                    {'urls': 'stun:stun.l.google.com:19302'},
                ]
            };
            
                 
            
            $("#stop-video").on("click", function(){


                localStream.getVideoTracks()[0].enabled =false;
                
                 $("#start-video").show();
                 $(this).hide();

            });
            
            $("#start-video").on("click", function(){


                localStream.getVideoTracks()[0].enabled = true;
                
                $("#stop-video").show();
                $(this).hide();

            });

            $("#stop-audio").on("click", function(){


                localStream.getAudioTracks()[0].enabled = false;
                
                $("#start-audio").show();
                $(this).hide();

            });
            
             $("#start-audio").on("click", function(){


                localStream.getAudioTracks()[0].enabled = true;
                
                $("#stop-audio").show();
                
                $(this).hide();

            });




            $("#stop-call").on("click", function(){

                if(peerConnection) {
                    peerConnection.close();
                    peerConnection = null;
                }
                
                if(!$.isEmptyObject(localStream))
                {
	                localStream.getVideoTracks()[0].stop();
	                localStream.getAudioTracks()[0].stop();
	                //localStream.stop();
	                $("#call-status").text("Call stopped");
                }

            });
            
            
            function stopCalling()
            {
	            if(peerConnection) {
                    peerConnection.close();
                    peerConnection = null;
                   
                    
                    
                }
                
                caller = false;
                
                 calling_status = 0;
                    
                    call_init_obj ={};
                
                if(!$.isEmptyObject(localStream))
                {
	                localStream.getVideoTracks()[0].stop();
	                localStream.getAudioTracks()[0].stop();
	                //localStream.stop();
	               
	                
	                $('#call').prop('disabled', false);
	                $('#stop').prop('disabled', true);
	                $('#stop-video').prop('disabled', true);
	                $('#stop-audio').prop('disabled', true);
	                $('#start-audio').prop('disabled', true);
	                $('#start-video').prop('disabled', true);
	                
	                remoteVideo.src="";
					localVideo.src="";
                }
                
                 $("#call-status").text("Call stopped");
	                
	                $('.accept_reject_call').hide();
                	audio.pause();
                	
                	
                	//$('#alertify-cover').addClass('alertify-cover-hidden');
                	
                	//$('#alertify').remove();
                	
                	$('#myModalIncomingCall').modal('hide');
                	
                	
                
                
            }

				
				var scope = angular.element("#websocket_chat_modal").scope();
				
				serverConnection=scope.getSocketObj();

            //serverConnection = io('http://' + document.domain + ':24532');
            //serverConnection = new WebSocket('ws://' + document.domain + ':8888');

            serverConnection.on('connected', function(data){
                //$("#socket-id").val(data.socket_id);
                //serverConnection = io('http://' + document.domain + ':'+data.server_port);
                //console.log('video chat connected');
            });


				
			
            
            serverConnection.on('message', gotMessageFromServer); 
            
            uuid = createUuid();
            
            remoteVideo = document.getElementById('remoteVideo');

            $("#call").on("click", function(){
	            
	            
	            startLocalMedia();
	            
	            $('#call').prop('disabled', true);
                $('#stop').prop('disabled', false);
                $('#stop-video').prop('disabled', false);
                $('#stop-audio').prop('disabled', false);
                $('#start-audio').prop('disabled', false);
                $('#start-video').prop('disabled', false);              
              

            });
            
            
            
            function startLocalMedia()
            {
	            
	             localVideo = document.getElementById('localVideo');
	             
	             var constraints = {
                    video: true,
                    audio: true,
                	};
            
                if(navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia(constraints).then(getUserMediaSuccess).catch(errorHandler);
                    return true;
                } else {
                    alert('Your browser does not support getUserMedia API');
                    return false;
                }
	            
            }
            
            function getUserMediaSuccess(stream) {
	            
                localStream = stream;
                localVideo.src = window.URL.createObjectURL(stream);
                
                
                
                var to_user = angular.element("#websocket_chat_modal").scope().current_to_user_id.toString();
				
				caller = true;
				
				var msg_obj = {
		            from_user : auth_user_id_chat.toString(),
		            to_user : to_user
		            
		        };

				

			
                serverConnection.emit('start_call', msg_obj); 
                
                
	                $('#call').attr('disabled','disabled');
	                
	   
	                
	                $('#stop-audio').show();
	                $('#stop-video').show();
	                
	                $('#start-video').hide();
	                $('#start-audio').hide();
	                
	                
	
	
	             }
            
            
            
            
            serverConnection.on("call_started", function(data){
                
                calling_status= 1;
                
                console.log(data);
                
                call_init_obj = data;
                
                
              
               $('#myModalIncomingCall').modal('hide');
                
                
            });
            

            serverConnection.on("incoming_call", function(data){
                
                //caller = data.from_user;
                //callee = data.to_user;
                
                if(calling_status==0)
                {
	                
	                
	               
	               // var user=getUser(data.from_user);
	               
	               
	              getChatUsers(data.from_user, function(user){
		              
		              
		              $('#from_user_id').val(data.from_user);
	                
	                if(!$('#myModalVideoAudio').hasClass('in')){
		                
		               // toastr.success('You are getting a call from'+' '+user.user.name);
		                // confirm dialog
		                
		                
						audio.play();
						
						
						audio.onended = function() {
							
							audio.play();
							
						}
						
						
						$('#myModalIncomingCall').modal('show');
						
						$('#usr_calling').text(user.user.name);
						
						$('#usr_img').attr('src',user.user.profile_picture);
						
						
		                
	                }
	                
	                
                	calling_status = 1;
                
	                $('.accept_reject_call').show();
	                
	                $('#user_name_calling').text(user.user.name+' '+'calling..');
	                
	                $("#call-status").text(user.user.name + " calling..");
	              
					call_init_obj = data; 
		              
		              
	              })
	                
	                
	                 
	             } 
	             else
	             {
		             
		             serverConnection.emit("busy", { "to_user" : data.from_user});
	             }  
                
                
                
                
                
                
            });
            
            
            
            serverConnection.on("busy",function(){
	            
	            
	            toastr.error("{{{trans('video_chat.call_busy')}}}");
	            
	            stopCalling();
	            
            })
            
            
            
            $('.accept_call').on('click',function(){
	            
	            
	            
	            				getChatUsers($('#from_user_id').val(), function(user){
		            				
		            				
		            				angular.element("#websocket_chat_modal").scope().current_to_user_id = user.user.id;
	             				
	             				
	             				
						        
						        $('#myModalVideoAudio').modal('show');
						        
						         $('#user_name').text(user.user.name);
				 
								 //$('#usercall_details').attr('src',user.user.profile_picture);
								 
								 //$('.usercall_details').attr('src',user.user.profile_picture);
								 $('.user_videos_details').css("background", "url("+base_url+"/uploads/others/original/"+user.user.profile_pic_url+") no-repeat center");
				 
				 $('.user_videos_details').css("background-size", "cover");
								
								 $('#websocket_chat_modal').modal('hide');
								 
								 $("#accept").trigger('click');
								 
								 $('.accept_reject_call').hide();
								 
								 audio.pause();
								 
								  $('#myModalIncomingCall').modal('hide');
		            				
		            				
	            				})
	            
	            				
	            
	             				
	            
            })
            
            $('.reject_call').on('click',function(){
	            
	            				$("#reject").trigger('click');
						        $('.accept_reject_call').hide();
						        audio.pause();
						        
						        caller = false;
	            
            })
            
            
            function getChatUsers(user_id, callback)
            {
	            $.ajax({
				  type: "POST",
				  url: chat_user,
				  data: {user_id:user_id},
				  success: function(data){
					  
					  chatUsers = data.chat_user;
					  
					  callback(data.chat_user);
					  
				  }
				 
				 
				});  
            }
            
            
           function  getUser(user_id)
           {
	           
	           
	           
	           
	            if (chatUsers.length < 1)
		            return null;
		
		        return chatUsers.find(function(item){
		            if(item.user.id == user_id) return true;
		        });

           }


            $("#stop").on("click", function(data){
	            
	            var to_user = angular.element("#websocket_chat_modal").scope().current_to_user_id;

                //send stop calling event
                //clearInterval(callingTimer);
                serverConnection.emit("call_stop", { "message_id" : call_init_obj.id});
                $("#call-status").text("You stopped calling..");

            });

            serverConnection.on("call_stopped", function(data){
                console.log(data);
                if(data.id == call_init_obj.id)
                {
                	$("#call-status").text("socket_id : " + data.from_user + " stopped calling..");
                	
                	stopCalling();
                }	
            });



            $("#accept").on("click", function(){
	            
	            //var to_user = angular.element("#websocket_chat_modal").scope().current_to_user_id;
	            
                
                
                
                
				
				//initiateCall();
               
               
                
                
                
                startLocalMedia_other();


            });
            
            
               $("#reject").on("click", function(){
	            
	            //var to_user = angular.element("#websocket_chat_modal").scope().current_to_user_id;
	            
                caller = false;
                
                
                
				
				//initiateCall();
               
               
                
                
                
                //startLocalMedia_other();
                
                serverConnection.emit("call_reject", {"message_id" : call_init_obj.id});
                
                
                 $("#call-status").text("Call rejected");
                
                 $('.accept_reject_call').hide();


            });
            
            
             serverConnection.on("call_rejected", function(data){
                //callee = data.data.to_user;
                //caller = data.data.from_user;
                //initiateCall();
                
                console.log(data);
                
                $("#call-status").text("Call rejected");
                
                stopCalling();
                
                
               // start();
                
                
/*
                if(caller)
                {
	               $(this).attr('disabled','disabled');
                }
*/
                
                callonprogress = false;
                
               

            });


            
            
            
            
            
            
            
            function startLocalMedia_other()
            {
	            
	            localVideo = document.getElementById('localVideo');
	             
	             var constraints = {
                    video: true,
                    audio: true,
                	};
            
                if(navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia(constraints).then(getUserMediaSuccess_other).catch(errorHandler);
                    return true;
                } else {
                    alert('Your browser does not support getUserMedia API');
                    return false;
                }
	            
            }
            
            
            
            function getUserMediaSuccess_other(stream)
            {
	            
	             localStream = stream;
                localVideo.src = window.URL.createObjectURL(stream);
                
                
                serverConnection.emit("call_accept", {"message_id" : call_init_obj.id});
                
                
                //console.log('call accepted from user',call_init_obj.id);
                
                callonprogress = true;
                
                $('.accept_reject_call').hide();
	            
            }
            
            
             serverConnection.on("call_disconnected", function(data){
     
                
               //console.log('call_disconnected',data);
                
                
                
				if(call_init_obj.from_user == data.user_id.toString()  || call_init_obj.to_user == data.user_id.toString())
					stopCalling();

            });



           
            
            


            serverConnection.on("call_accepted", function(data){
                //callee = data.data.to_user;
                //caller = data.data.from_user;
                //initiateCall();
                
                //console.log('call_accepted',data);
                
                
                start();
                
                
/*
                if(caller)
                {
	               $(this).attr('disabled','disabled');
                }
*/
                
                callonprogress = true;
                
               
            });





            //serverConnection.on('message', gotMessageFromServer); 

            
            
           
            
            function initiateCall() {
/*
                uuid = createUuid();
            
                localVideo = document.getElementById('localVideo');
                remoteVideo = document.getElementById('remoteVideo');
            
                serverConnection.on('message', gotMessageFromServer); 
            
                var constraints = {
                    video: true,
                    audio: true,
                };
            
                if(navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia(constraints).then(getUserMediaSuccess).catch(errorHandler);
                    return true;
                } else {
                    alert('Your browser does not support getUserMedia API');
                    return false;
                }
*/
            }


            
            
            
            function start() {
	            
	           
	            
	            
                peerConnection = new RTCPeerConnection(peerConnectionConfig ,{optional: [{RtpDataChannels: true},{
       DtlsSrtpKeyAgreement: true}]});
                peerConnection.onicecandidate = gotIceCandidate;
                peerConnection.onaddstream = gotRemoteStream;
                peerConnection.oniceconnectionstatechange = stopRemoteStream;                 
                peerConnection.addStream(localStream);
                
               

                peerConnection.onconnectionstatechange = function(){
                    alert('asdf');
                }

                if(caller) {
                    peerConnection.createOffer(constraints).then(createDescriptionForCaller).catch(errorHandler);
                }
            }
            
            
             function stopRemoteStream(event) {
                   console.log(event);
                   if(event.type == "iceconnectionstatechange" && (event.target.iceConnectionState == 'disconnected' || event.target.iceConnectionState == 'closed' || event.target.iceConnectionState =='failed')) {
                       localStream.getVideoTracks()[0].stop();
                       localStream.getAudioTracks()[0].stop();
                       //localStream.stop();
                       
                       caller= false;
                       $("#call-status").text("call stopped");
                   }
               }

			   
			   var constraints = {
							    mandatory: {
							        "offerToReceiveAudio": false,
							        "offerToReceiveVideo": false
							    }
							};
            
            
            function gotMessageFromServer(message) {

                console.log('message',message);
                
                 console.log('caller',caller);

                if(!peerConnection) start();
            
                var signal = message;
            
                // Ignore messages from ourself
                if(signal.uuid == uuid) return;
                
                if(signal.sdp) {
            
                    peerConnection.setRemoteDescription(new RTCSessionDescription(signal.sdp)).then(function() {
                        // Only create answers in response to offers
                        if(signal.sdp.type == 'offer') {
                            console.log(signal);
                            
                            
               
                            peerConnection.createAnswer(constraints).then(createDescriptionForCallee).catch(errorHandler);
                
                
                        }
            
                    }).catch(errorHandler);

                } else if(signal.ice) {
                    console.log("ice candidate");
                    peerConnection.addIceCandidate(new RTCIceCandidate(signal.ice)).catch(errorHandler);
                }

            }
            
            
            
            
            
            function gotIceCandidate(event) {
                if(event.candidate != null) {
console.log('ice candidate got');
                   
                    
                    
                    if(caller)
                    {
	                    serverConnection.emit('message', {"to_user" : call_init_obj.to_user.toString(), "message": {'ice': event.candidate, 'uuid': uuid} } );
                    }
                    else{
	                    
	                    serverConnection.emit('message', {"to_user" : call_init_obj.from_user.toString(), "message": {'ice': event.candidate, 'uuid': uuid} } );
	                    
                    }

                    
                }
            }
            
            $('#myModalVideoAudio').on('hidden.bs.modal', function () {
    
		    	stopCalling();
			})
            

            function createDescriptionForCallee(description) {
                
                
                
                
            
                peerConnection.setLocalDescription(description).then(function() {
	                console.log('got description to callee',call_init_obj.from_user);
                    serverConnection.emit('message', {"to_user" : call_init_obj.from_user.toString(), "message" : {'sdp': peerConnection.localDescription, 'uuid': uuid} } );
                }).catch(errorHandler);
            }



            function createDescriptionForCaller(description) {
               
            
                peerConnection.setLocalDescription(description).then(function() {
	                
	                
	                 console.log('got description to caller',call_init_obj.to_user);
                    serverConnection.emit('message',{"to_user" : call_init_obj.to_user.toString(), "message" : {'sdp': peerConnection.localDescription, 'uuid': uuid} } );
                }).catch(errorHandler);
            }



            
            function gotRemoteStream(event) {
                console.log('got remote stream');
                remoteStream = event.stream;
                remoteVideo.src = window.URL.createObjectURL(remoteStream);
                $("#call-status").text("Call connected..");
                
               $('#call').prop('disabled', true);
                $('#stop').prop('disabled', false);
                $('#stop-video').prop('disabled', false);
               
                $('#stop-audio').prop('disabled', false);
                $('#start-audio').prop('disabled', false);
                 $('#start-video').prop('disabled', false);

                
                
            }
            
            function errorHandler(error) {
                console.log(error);
                
               toastr.error(error.name+' '+error.message);
                
               
                
                stopCalling();
                
                $('#call').prop('disabled', false);
                $('#stop').prop('disabled', true);
                $('#stop-video').prop('disabled', true);
                $('#stop-audio').prop('disabled', true);
                $('#start-audio').prop('disabled', true);
                $('#start-video').prop('disabled', true);  
            }

		 
	 })

                        
            
            
        </script>		
        
        @endsection