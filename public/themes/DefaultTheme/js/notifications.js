var Notification={};Notification.count=0;Notification.get_notifications=function(){var data={};data._token=App.csrf_token;$.get(App.urls.get_notifications,data,function(data){Notification.count=data.length;console.log('get notification',Notification.count);_.each(data,function(item){if(item.type=="payment"&&item.status=="unseen"){toastr.success('Your mobile payment is successfully done.');$('#processing-modal').modal('hide');$('#processed_modal').modal();}})});};Notification.get_notifications();