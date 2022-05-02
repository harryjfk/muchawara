class Users {
    constructor(chat) {
        this.chat = chat;
        this.user = null;
        this.selected = null;
        this.displayInfoPanel = true;
        this.users = new UserList(this);
        this.tempUsers = new UserList(this);
        this.addEvents();
    }

    load(data, preLoad) {
        console.log(data);
        this.user = data.user;
        preLoad();
        this.user.img = chat.base_dir + this.user.img;
        for (var i in data.related) {
            var t = data.related[i];
            var n = new User(this.users, t["name"], t["fullname"], chat.base_dir + t["profile_picture"], t["messages"], t["state"]);
            this.users.add(t["name"], n);
        }

        var s = null;
        for (var i = 0; i < this.users.length(); i++)
            if (this.users.getFromIndex(i).data.name != this.user.name) {

                s = this.users.getFromIndex(i).data;
                break;

            }


        if (s != null)
            s.select();
    }

    searchAndUpdate(name) {
        if (this.searchingTemp)
            return;
        var t = "value=" + name;
        this.tempUsers.clear();
        this.searchingTemp = true;
        $.ajax({
            url: this.chat.app_path + "/messenger/search",
            /* method:method,*/
            data: t,
            type: "GET",
            /*  dataType : 'html',*/
            enctype: 'multipart/form-data',
            success: function (e) {

                $("#inner-user-panel .user").hide();
                var d = e;
                for (var i in d) {
                    var t = d[i];
                    var n = new User(chat.users.tempUsers, t["name"], t["fullname"], chat.base_dir + t["img"], t["messages"], t["state"]);
                    chat.users.tempUsers.add(t["name"], n);
                    $("#" + t["name"] + "_row .message-text").html("");
                }
                this.searchingTemp = false;

            },
            error: function () {
                this.searchingTemp = false;
                alert("Existen errores actualizando los datos")
            },
            processData: false,
            contentType: false

        });
        for (var i = 0; i < this.users.length(); i++) {
            var u = this.users.getFromIndex(i).data;
            var show = u.messages.getLastMessage() != null;
            if (show == true)
                show = u.name.toLowerCase().indexOf(name.toLowerCase()) !== -1 || u.fullname.toLowerCase().indexOf(name.toLowerCase()) !== -1;

            if (show)
                $("#" + u.name + "_row").show();
            else

                $("#" + u.name + "_row").hide();
        }

    }

    addEvents() {
        $("#search_user").on("change", function () {
            chat.users.searchAndUpdate($("#search_user").val());
        });
        this.chat.events.addEvent("user-added", function (e, data) {


            if (data.name !== chat.users.user.name) {

                var t = e.list.getChat().template.render("user-item", data);

                $("#contacts ul").append(t);
                $("#" + data.name + "_row").data("data-user", data);
                $("#" + data.name + "_row").on("click", function (e) {

                    var t = $(e.target);
                    while (!t.hasClass("contact"))
                        t = t.parent();
                    t.data("data-user").select();

                });
            }


        });
        this.chat.events.addEvent("user-changed", function (e, data) {

            $("#contacts .contact").removeClass("selected");
            $("#" + this.name + "_row").addClass("selected");
            data.messages.clearPanel();
            data.messages.reloadMessages(true);
            // var t = data.messages.getLastMessage();
            // if ( t!= null)
            // {
            //     t.respondToInnerReaded();
            //
            // }
            // data.messages.setImgSeen();
            //
            // // var last = data.messages.getLastMessage();
            // // if (last == null)
            // //     $("#" + data.name + "_row").hide();

            $(".contact-profile img").attr("src", data.img);
            //
            data.getStatus();
            $(".contact-profile #view_user_name").html(data.fullname);
        });

        // this.doClickInfo();
    }


}

class UserList extends List {
    constructor(parent) {
        super(parent);
    }

    add(name, data) {
        super.add(name, data);
        this.getChat().events.trigger("user-added", data);
    }

    clear() {
        for (var i = 0; i < this.length(); i++) {
            var t = this.getFromIndex(i);
            $("#" + t.name + "_row").remove();
        }
        super.clear();

    }


}

class User {
    constructor(list, name, full_name, img, messages, state) {
        this.list = list;
        this.name = name;
        this.fullname = full_name;
        this.img = img;

        this.messages = new Messages(this);
        this.messages.load(messages);
        var last = this.messages.getLastMessage();

        this.last_time = last == null ? "" : last.getDate();
        this.state = state;
        if (last == null)
            $("#" + this.name + "_row").hide();

    }

    setReadedAll() {
        if($("#" + this.name + "_row").hasClass("unread"))
        this.messages.messages.setReaded(true);
        // this.messages.setImgSeen();
        // document.title = "MuchaWara";
        $("#" + this.name + "_row").removeClass("unread");
    }

    select() {
        if (this.list.parent.selected == this)
            return;

        this.list.parent.selected = this;
        this.list.parent.chat.events.trigger("user-changed", this.list.parent.selected);
        $("#" + this.name + "_row").addClass("selected");
        this.setReadedAll(true);
        // setTimeout(function () {
        //     $(".message-view-container").scrollTop( $("#message-inner-container").height() );
        // },500)


    }


    updateStatus() {
        if (this.status == null || this.status == "paused")
            $(".typing").hide();
        else if (this.status == "composing")
            $(".typing").show();
        $("#" + this.name + "_row .contact-status").removeClass("online").removeClass("offline").addClass(this.state);

    }

    getStatus() {


        // var t = "user=" + this.name;
        //
        // $.ajax({
        //     url: chat.app_path+"/messenger/status",
        //     /* method:method,*/
        //     data: t,
        //     type: "GET",
        //     /*  dataType : 'html',*/
        //     enctype: 'multipart/form-data',
        //     success: function (e) {
        //
        //         var d = e;
        //         var u = d.user.substr(0, d.user.indexOf("@"));
        //          if(chat.users.tempUsers.length()>0)
        //              chat.users.tempUsers.get(u).data.state = d.state;
        //              else
        //         chat.users.users.get(u).data.state = d.state;
        //
        //
        //         if (chat.users.selected.name == u)
        //             $(".user-view .status div").html(chat.users.selected.state == false ? "Inactivo(a)" : "Activo(a)");
        //
        //     },
        //     error: function (e) {
        //         console.log(e);
        //         alert("Existen errores actualizando los datos")
        //     },
        //     processData: false,
        //     contentType: false
        //
        // });

    }


}