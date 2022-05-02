class Messages {
    constructor(user) {
        this.user = user;
        this.messages = new MessageList(this);
        this.addEvents();
        this.loadingPrevMessages = false;
    }

    load(data) {
        if (data != null) {

            for (var i in data) {
                var t = data[i];

                var m = new Message(this.user, t["text"], t["sended"], t["recieved"], t["readed"], false, t["id"], t["token"], t["date"]);

                this.messages.add(t["id"], m);
            }
            if (this.messages.length() > 0)
                this.user.last_message = this.messages.list[this.messages.length() - 1].data;

        }


    }

    getLastMessage() {
        if (this.messages.length() == 0)
            return null;
        var item = this.messages.getFromIndex(this.messages.length() - 1);
        return item.data;
    }

    addEvents() {
        $(".messages").on('wheel', function (e) {

            var delta = e.originalEvent.deltaY;

            if (delta > 0) {
            }
            else {
                if ($(".messages")[0].scrollTop == 0)
                    chat.users.selected.messages.loadPrevMessages();
            }

            // return false; // this line is only added so the whole page won't scroll in the demo
        });
        document.getHTML = function (who, deep) {
            if (!who || !who.tagName) return '';
            var txt, ax, el = document.createElement("div");
            el.appendChild(who.cloneNode(false));
            txt = el.innerHTML;
            if (deep) {
                ax = txt.indexOf('>') + 1;
                txt = txt.substring(0, ax) + who.innerHTML + txt.substring(ax);
            }
            el = null;
            return txt;
        }
        this.user.list.getChat().events.addEvent("message-loaded", function (e, data) {

            for (var i = 0; i < data.list.length; i++)
                data.getFromIndex(i).data.checkStatus();
        });
        this.user.list.getChat().events.addEvent("message-added", function (e, data) {
            //
            // if(data.text.indexOf("<readed>")!==-1)
            //     return;
            // var isLast = false;
            var users = e.list.getChat().users;
            if (users.selected != null)
                if (users.selected.name == data.user.name) {
                    var t = new Object();
                    t.message = data;
                    t.srcUser = chat.users.user;

                    var inserted = false;
                    var t = e.list.getChat().template.render("message-item", t);
                    //
                    // if(data.isText)
                    // {
                    //     // console.log("aa");
                    //     var f = t.indexOf('<p class="  hide_text">');
                    //     var c = "";
                    //     var temp = t.substr(f,6);
                    //     while(temp!=="</div>" && f<t.length)
                    //     {
                    //         c+=t[f];
                    //         f++;
                    //          temp = t.substr(f,6);
                    //     }
                    //     t= t.replace(c,"");
                    // }

                    var base = "";

                    var index = parseInt(data.index);
                    var divs = $(".messages li");

                    for (var i = 0; i < divs.length; i++) {
                        var idt = parseInt(divs[i].id.replace("message_", ""));

                        if (idt < index)
                            base += document.getHTML(divs[i], true);
                        else {
                            if (!inserted) {
                                base += t;
                                inserted = true;

                            }
                            base += document.getHTML(divs[i], true);
                        }

                    }
                    if (!inserted) {
                        base += t;
                        inserted = true;
                        // isLast = true;
                    }

                    $(".messages ul").html(base);

                }
            data.printState();

            if (!data.user.messages.loadingPrevMessages) {
            $("#" + data.user.name + "_row .preview").html(data.isText ? data.text : "Imagen");
            $(".messages").scrollTop($(document).height()+9000000);

            if(chat.users.selected!=null)
            if( data.user.name !=chat.users.selected.name)
            $("#" +  data.user.name + "_row").addClass("unread");
            }

        });

    }

    clearPanel() {
        $(".messages ul").html("");

    }


    loadPrevMessages() {

        if (this.loadingPrevMessages)
            return;
        $(".message-view-loading").show();
        this.loadingPrevMessages = true;
        var l = this.messages.length();
        var t = "user=" + this.user.name + "&offset=" + l + "&setReaded=true";

        $.ajax({
            url: chat.app_path + "/messenger/messages",
            /* method:method,*/
            data: t,
            type: "GET",
            /*  dataType : 'html',*/
            enctype: 'multipart/form-data',
            success: function (e) {
                var current = chat.users.user.name;
                var d = e.messages;
                for (var i in d) {
                    var t = d[i];

                    var from = t["fromUser"].substr(0, t["fromUser"].indexOf("@"));
                    var to = t["toUser"].substr(0, t["toUser"].indexOf("@"));

                    var childUser = null;
                    if (current == from)
                        childUser = chat.users["users"].get(to);
                    else
                        childUser = chat.users["users"].get(from);
                    childUser = childUser.data;


                    var m = new Message(childUser, t["text"], t["sended"], t["readed"], t["id"]);

                    childUser.messages.messages.add(t["id"], m);

                }
                if (childUser != null)
                    childUser.messages.loadingPrevMessages = false;
                $(".message-view-loading").hide();

            },
            error: function () {
                this.loadingPrevMessages = false;
                alert("Existen errores actualizando los datos")
            },
            processData: false,
            contentType: false

        });

    }

    reloadMessages(read = false) {
        this.messages.reloadMessages(read);
        this.user.list.getChat().events.trigger("message-loaded", this.messages);
        $(".messages").scrollTop($(document).height()+9000000);

    }
}

function guidGenerator() {
    var S4 = function () {
        return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    };
    return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
}

class MessageList extends List {
    constructor(parent) {
        super(parent);
    }

    add(name, data) {
        if (name == -1) {
            if (name == -1) {
                if (data.index == null) {
                    var t = this.getFromIndex(this.length() - 1);
                    if (t == null)
                        data.index = 1;
                    else
                        data.index = parseInt(t.name) + 1;
                }
                name = data.index.toString();

            }

            if (data.token == null) {

                data.token = guidGenerator();
            }

        }


        if (this.length() > 0) {

            // var top = this.getFromIndex(0);
            // if (parseInt(top.data.index) <= parseInt(name)) {
            //     super.add(name, data);
            //     this.parent.user.list.getChat().events.trigger("message-added", data);
            // }
            //
            // else {
            this.insert(name, data);

            this.parent.user.list.getChat().events.trigger("message-added", data);
            // }

        }
        else {
            super.add(name, data);
            // data.updateIndex();
            this.parent.user.list.getChat().events.trigger("message-added", data);
        }

    }

    reloadMessages(read) {
        this.reloading = true;
        for (var i in this.list) {
            this.parent.user.list.getChat().events.trigger("message-added", this.list[i].data);
        }
        this.reloading = false;

    }

    getMessageFromToken(token) {

        for (var i = 0; i < this.list.length; i++)
            if (this.getFromIndex(i).data.token == token)
                return this.getFromIndex(i);
        return null;
    }

    getLast() {
        if (this.list.length == 0)
            return null;
        return this.getFromIndex(this.list.length - 1).data
    }
    setReaded()
    {
        for (var i in this.list) {
            this.list[i].data.sendReaded();
        }
    }


}


class Message {
    constructor(user, text, sended, recieved, readed, sendInformation, index, token, date) {
        this.user = user;
        this.text = text;
        this.sended = sended;
        this.readed = readed;
        this.sendInformation = sendInformation;
        this.recieved = recieved;
        this.index = index;
        this.token = token;

        this.date = date;
        if (text.indexOf("<image>") !== -1) {
            this.type = "image";
            this.text = chat.base_dir + "/uploads/chat/" + this.text.replace("<image>", "").replace("</image>", "");

        }

        else
            this.type = "text";
        this.isText = this.type == "text";
        this.isImage = this.type == "image";

        this.doSend = false;
    }


    formatTime(time) {


        if (time.toString().length == 1)
            return "0" + time;
        return time;
    }

    getDate() {


        var t = this.date == null ? new Date() : new Date(parseInt(this.date));
        var now = new Date();
        if (t.getDate() == now.getDate() && t.getMonth() == now.getMonth() && t.getYear() == now.getYear())
            t = this.formatTime(t.getHours()) + ":" + this.formatTime(t.getMinutes());
        else {
            var d = t.getDay();
            if (d == 1)
                return "Domingo";
            else if (d == 2)
                return "Lunes"; else if (d == 3)
                return "Martes";
            else if (d == 4)
                return "Miercoles";
            else if (d == 5)
                return "Jueves";
            else if (d == 6)
                return "Viernes";
            else return "Sabado";
        }
        return t;
    }

    sendRecieved() {
        var f = this.user.name;
        chat.connection.driver.strophe_connection.chatstates.sendRecieved(f + '@' + chat.connection.driver.serverDomain, "chat", this.token);
        var t = "user=" + f + "&action=recieved&msgtoken=" + this.token;

        $.ajax({
            url: chat.app_path + "/messenger/readMessages",
            /* method:method,*/
            data: t,
            type: "GET",
            /*  dataType : 'html',*/
            enctype: 'multipart/form-data',
            success: function () {
            },
            error: function () {
                alert("Existen errores actualizando los datos")
            },
            processData: false,
            contentType: false

        });

    }

    sendReaded() {
        var f = this.user.name;
        chat.connection.driver.strophe_connection.chatstates.sendReaded(f + '@' + chat.connection.driver.serverDomain, "chat", this.token);
        var t = "user=" + f + "&action=readed&msgtoken=" + this.token;
        $.ajax({
            url: chat.app_path + "/messenger/readMessages",
            /* method:method,*/
            data: t,
            type: "GET",
            /*  dataType : 'html',*/
            enctype: 'multipart/form-data',
            success: function () {
            },
            error: function () {
                alert("Existen errores actualizando los datos")
            },
            processData: false,
            contentType: false

        });
    }


    printState() {

        if (this.sendInformation) {
            if (this.sended == false || this.sended == "false") {
                this.sendRecieved();
                var f = this.user.name;
                if (chat.users.selected != null) {
                    if (chat.users.selected.name == f)
                        this.sendReaded();
                    this.doSend = false;
                }
                else if (this.doSend) {
                    this.sendReaded();
                    this.doSend = false;
                }


            }
            this.sendInformation = false;
        }

        if (this.recieved == true || this.recieved == "true")
            $("#message_" + this.index + " .recieved").show();
        if (this.readed == true || this.readed == "true")
            $("#message_" + this.index + " .readed").show();


    }

    checkStatus() {

        var last = this.user.messages.messages.getLast();
        var islast = last.index == this.index;
        if (islast)
            console.log("aaa");
        if (last.sended == true || last.sended == "true") {
            if (this.recieved == false || this.recieved == "false" || this.readed == false || this.readed == "false") {
                this.sendInformation = true;
                this.doSend = true;
                this.printState();
            }
        }
        else if ((last.recieved == true || last.recieved == "true")) {
            if (this.recieved == false || this.recieved == "false" || this.readed == false || this.readed == "false") {
                this.sendInformation = true;
                this.doSend = true;
                this.printState();
            }
        }
        else if (islast) {
            if (chat.users.selected != null) {
                var f = this.user.name;
                if (chat.users.selected.name == f) {
                    this.sendInformation = true;
                    this.doSend = true;
                    this.printState();
                }

            }
        }


    }

}