class Chat {

    constructor(selector, base_dir, app_path, data) {
        this.interpreter = new Interpreter(this);
        this.events = new Events(this);
        this.base_dir = base_dir;
        this.app_path = app_path;
        this.template = new Template(this, selector);

        this.connection = new Connection(this, "openfire", null);
        this.users = new Users(this);
        if (data != null)
            this.load(data);

    }


    load(data) {
        this.connection.load(data.connection);
        this.template.buildTemplates();
        this.users.load(data.users,function () {
            chat.events.trigger("created", chat);
        });


        //


    }

    sendMessage(src, dest, text) {

        var sended = this.users.user.name == src;
        this.tempMessage = [src, dest, text,sended];
        if (sended) {
        //     if (this.users.tempUsers.length() > 0) {
        //
        //         var t = "user=" + dest;
        //         $.ajax({
        //             url: this.app_path + "/messenger/bindUsers",
        //
        //             data: t,
        //             type: "GET",
        //
        //             enctype: 'multipart/form-data',
        //             success: function (e) {
        //                 if (e.result == true) {
        //                     $("#search_user").val("");
        //                     var username = "adriel";
        //                     var s = chat.users.tempUsers.get(username);
        //                     chat.users.users.add(s.name, s.data);
        //                     chat.users.tempUsers.clear();
        //                     $("#inner-user-panel .user").show();
        //                     var m = new Message(chat.users.users.get(dest).data, text, true, false);
        //                     chat.users.users.get(chat.tempMessage[1]).data.messages.messages.add(-1, m);
        //                     chat.connection.driver.sendMessage(chat.users.user.name, chat.users.users.get(chat.tempMessage[1]).name, chat.tempMessage[2]);
        //
        //                     chat.tempMessage = null;
        //
        //
        //                 }
        //
        //
        //             },
        //             error: function () {
        //                 this.tempMessage = null;
        //                 alert("Existen errores actualizando los datos")
        //             },
        //             processData: false,
        //             contentType: false
        //
        //         });
        //
        //
        //     }
        //     else {
        //         if (text !== "image_file"&&text !== "camera_file") {
                var m = new Message(this.users.users.get(dest).data, text, true, false);
                this.users.users.get(dest).data.messages.messages.add(-1, m);
        // }
        //     }
        //
        // }
        // else { if (text !== "image_file" &&text !== "camera_file") {
        //     var m = new Message(this.users.users.get(src).data, text, false, false);
        //     this.users.users.get(src).data.messages.messages.add(-1, m);
        // }
        }
        // if (this.users.tempUsers.length() == 0) {
        //     if(text=="camera_file")
        //     {
        //         var data1 = $("#photo").attr("src");
        //         function b64ToUint8Array(b64Image) {
        //             var img = atob(b64Image.split(',')[1]);
        //             var img_buffer = [];
        //             var i = 0;
        //             while (i < img.length) {
        //                 img_buffer.push(img.charCodeAt(i));
        //                 i++;
        //             }
        //             return new Uint8Array(img_buffer);
        //         }
        //         var u8Image  = b64ToUint8Array(data1);
        //
        //         var formData = new FormData();
        //         formData.append("file_upload", new Blob([ u8Image ], {type: "image/png"}));
        //         $.ajax({
        //             url: this.app_path+"/messenger/upload-image",
        //             /* method:method,*/
        //             data: formData,
        //             type: "POST",
        //             /*  dataType : 'html',*/
        //             enctype: 'multipart/form-data',
        //             success: function (e) {
        //                 if (e.status == "success") {
        //                     var m = "<image>" + e.image + "</image>";
        //                     if(chat.tempMessage[3]==true)
        //                     {
        //
        //                         var m1 = new Message(chat.users.users.get(dest).data, m, true, false);
        //                         chat.users.users.get(dest).data.messages.messages.add(-1, m1);
        //                         setTimeout(function(){
        //                                 $(window).resize()
        //                             },
        //                             1000);
        //
        //                     }
        //                     else
        //                     {
        //                         var m1 = new Message(chat.users.users.get(src).data, m, false, false);
        //                         chat.users.users.get(src).data.messages.messages.add(-1, m1);
        //                         setTimeout(function(){
        //
        //                                 $(window).resize()
        //                             },
        //
        //                             1000);
        //                     }
        //
        //                     chat.connection.driver.sendMessage(chat.users.user.name, chat.users.users.get(chat.tempMessage[1]).name, m);
        //                     chat.tempMessage = null;
        //                 }
        //                 else
        //                     alert("Error al momento de subir las imagenes");
        //
        //
        //             },
        //             error: function (e) {
        //                 alert("Error al momento de subir las imagenes");
        //
        //             },
        //             processData: false,
        //             contentType: false
        //         });
        //     }
        //     else
        //     if (text == "image_file") {
        //         var data = new FormData(upload_form)
        //         $.ajax({
        //             url:  this.app_path+"/messenger/upload-image",
        //             /* method:method,*/
        //             data: data,
        //             type: "POST",
        //             /*  dataType : 'html',*/
        //             enctype: 'multipart/form-data',
        //             success: function (e) {
        //                 if (e.status == "success") {
        //                     var m = "<image>" + e.image + "</image>";
        //                     if(chat.tempMessage[3]==true)
        //                     {
        //
        //                     var m1 = new Message(chat.users.users.get(dest).data, m, true, false);
        //                         chat.users.users.get(dest).data.messages.messages.add(-1, m1);
        //                         setTimeout(function(){
        //                                 $(window).resize()
        //                             },
        //                             1000);
        //
        //                     }
        //                     else
        //                     {
        //                         var m1 = new Message(chat.users.users.get(src).data, m, false, false);
        //                         chat.users.users.get(src).data.messages.messages.add(-1, m1);
        //                         setTimeout(function(){
        //
        //                                 $(window).resize()
        //                             },
        //
        //                             1000);
        //                     }
        //
        //                     chat.connection.driver.sendMessage(chat.users.user.name, chat.users.users.get(chat.tempMessage[1]).name, m);
        //                     chat.tempMessage = null;
        //                 }
        //                 else
        //                     alert("Error al momento de subir las imagenes");
        //                 console.log(e);
        //
        //             },
        //             error: function (e) {
        //                 alert("Error al momento de subir las imagenes");
        //
        //             },
        //             processData: false,
        //             contentType: false
        //
        //         });
        //     }
        //     else
                this.connection.driver.sendMessage(this.users.user.name, this.users.users.get(dest).name, text,m.token);
        // }


    }


}


