class Events {
    constructor(chat) {
        this.chat = chat;
        this.list = new EventList(this);
        this.buildEvents();
    }

    buildEvents() {
        this.list.add("created", function (event, e) {
            var t = e.template.render("base");
            var selector = event.list.getChat().template.selector;
            $(selector).html(t);
            $('.submit').on("click",function (e) {
                chat.sendMessage(chat.users.user.name, chat.users.selected.name,$('.message-input input').val())
                $('.message-input input').val("");
            });
            $('.message-input input').keypress(function (e) {
                if (e.which == 13) {
                    chat.sendMessage(chat.users.user.name, chat.users.selected.name,$('.message-input input').val())
                    $('.message-input input').val("");
                }
                else
                {
                    chat.connection.driver.emitTyping(chat.users.user.name, chat.users.selected.name,true)
                }

            });
            $('.message-input input').on("blur",function(e){
                chat.connection.driver.emitTyping(chat.users.user.name, chat.users.selected.name,false)
            })
            // $('#msg_text , .message-view-container').on("click", function (e) {
            //
            //     chat.users.selected.setReadedAll(true);
            // });

            $("#profile-img").click(function () {
                $("#status-options").toggleClass("active");
            });

            $(".expand-button").click(function () {
                $("#profile").toggleClass("expanded");
                $("#contacts").toggleClass("expanded");
            });
            $("#status-options ul li").click(function () {
                $("#profile-img").removeClass();
                $("#status-online").removeClass("active");
                $("#status-away").removeClass("active");
                $("#status-busy").removeClass("active");
                $("#status-offline").removeClass("active");
                $(this).addClass("active");

                if ($("#status-online").hasClass("active")) {
                    $("#profile-img").addClass("online");
                } else if ($("#status-away").hasClass("active")) {
                    $("#profile-img").addClass("away");
                } else if ($("#status-busy").hasClass("active")) {
                    $("#profile-img").addClass("busy");
                } else if ($("#status-offline").hasClass("active")) {
                    $("#profile-img").addClass("offline");
                } else {
                    $("#profile-img").removeClass();
                }
                ;

                $("#status-options").removeClass("active");
            });
            String.prototype.width = function (font) {
                var f = font || '14px "Helvetica Neue",Helvetica,Arial,sans-serif',
                    o = $('<div></div>')
                        .text(this)
                        .css({
                            'position': 'absolute',
                            'float': 'left',
                            'white-space': 'nowrap',
                            'visibility': 'hidden',
                            'font': f
                        })
                        .appendTo($('body')),
                    w = o.width();

                o.remove();

                return w;
            };

            $("#cameraUpload").on("click", function () {
                var streaming = false,
                    video = document.querySelector('#video'),
                    canvas = document.querySelector('#canvas'),
                    photo = document.querySelector('#photo'),
                    startbutton = document.querySelector('#startbutton'),
                    width = 400,
                    height = 0;

                navigator.getMedia = ( navigator.getUserMedia ||
                    navigator.webkitGetUserMedia ||
                    navigator.mozGetUserMedia ||
                    navigator.msGetUserMedia);

                navigator.getMedia(
                    {
                        video: true,
                        audio: false
                    },
                    function (stream) {
                        window.localStream = stream;
                        if (navigator.mozGetUserMedia) {
                            video.mozSrcObject = stream;
                        } else {
                            var vendorURL = window.URL || window.webkitURL;
                            video.src = vendorURL.createObjectURL(stream);
                        }
                        setTimeout(function () {
                            video.play();
                        }, 100)

                    },
                    function (err) {
                        console.log("An error occured! " + err);
                    }
                );

                function takepicture() {
                    var width = 600;
                    var height = 450;
                    canvas.width = width;
                    canvas.height = height;
                    canvas.getContext('2d').drawImage(video, 0, 0, width, height);
                    var data = canvas.toDataURL('image/png');
                    photo.setAttribute('src', data);
                }

                var streaming = true;
                $(".modalVideo").dialog({
                    "height": 600, "width": 800, 'title': "Vista previa", buttons: [
                        {
                            text: "Tomar",
                            class: "btn btn-take",
                            click: function (e) {
                                takepicture();
                                streaming = false;
                                $("#video").hide();
                                $("#photo").show();
                                $(".btn-send").removeClass("hide").show();
                                $(".btn-take").hide();
                                // $("#video")[0].stop();

                                // chat.sendMessage(chat.users.user.name, chat.users.selected.name, "image_file");

                            }
                        },
                        {
                            text: "Enviar",
                            class: "btn btn-send hide",
                            click: function (e) {

                                chat.sendMessage(chat.users.user.name, chat.users.selected.name, "camera_file");
                                $(this).dialog("close");
                                window.localStream.getVideoTracks()[0].stop();
                                $("#video").show();
                                $("#photo").hide();
                            }
                        },
                        {
                            text: "Cancelar",
                            class: "btn btn-secundary",
                            click: function (e) {


                                if (streaming)
                                {
                                    $(this).dialog("close");
                                    window.localStream.getVideoTracks()[0].stop()
                                }

                                else {
                                    $("#video").show();
                                    $("#video")[0].play();
                                    $("#photo").hide();
                                    $(".btn-send").hide();
                                    $(".btn-take").show();
                                    streaming = true;
                                }

                            }
                        },
                    ]
                });
            });
            $("#picUpload").on("click", function () {
                $("#file_upload").click();
            });
            $("#file_upload").on("change", function (e) {

                var reader = new FileReader();
                reader.onload = function (event) {

                    $('.modalImage img')[0].src = event.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
                $(".modalImage").dialog({
                    "height": 600, "width": 800, 'title': "Vista previa", buttons: [
                        {
                            text: "Enviar",
                            class: "btn",
                            click: function (e) {

                                chat.sendMessage(chat.users.user.name, chat.users.selected.name, "image_file");
                                $(this).dialog("close");
                            }
                        },
                        {
                            text: "Cancelar",
                            class: "btn btn-secundary",
                            click: function (e) {

                                $(this).dialog("close");
                            }
                        },
                    ]
                });

            });
        });

    }

    addEvent(name, func) {
        this.list.add(name, func);
    }

    trigger(name, params) {
        var item = this.list.get(name);

        if (item != null)
            item.trigger(params);
    }
}


class EventList extends List {
    constructor(parent) {
        super(parent);
    }

    getItemClass() {
        return "Event";
    }


}

class Event extends listItem {

    trigger(e) {
        if (this.data != null)
            this.data(this, e);
    }
}