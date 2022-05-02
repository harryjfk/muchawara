class Connection {
    constructor(chat, driver, data) {
        this.chat = chat;
        this.tryToConnect = true;
        this.tryConnectTimeOut = 1000;
        var d = this.getDrivers()[driver];
        var myObject = eval(d);
        var t = new myObject(this, data);
        this.driver = t;
        this.buildEvents();
        if (data != null)
            this.driver.connect();

    }

    load(data) {
        this.driver.load(data);
        this.driver.connect();
    }

    buildEvents() {

        this.chat.events.addEvent("connecting", function (e, data) {

            $(".conx-msg-row").removeClass("error-conx").removeClass("connected-conx").addClass("progress-conx");
            $(".conx-msg-row div").html("Conectando...");
        });
        this.chat.events.addEvent("error", function (e, data) {

            $(".conx-msg-row").removeClass("progress-conx").removeClass("connected-conx").addClass("error-conx");
            $(".conx-msg-row div").html("Error mientras se intentaba conectar...");
        });
        this.chat.events.addEvent("connected", function (e, data) {
            $(".conx-msg-row div").html("Conectado");
            $(".conx-msg-row").removeClass("error-conx").removeClass("progress-conx").addClass("connected-conx").fadeOut("slow", function () {
                // Animation complete.
            });

        });
    }

    getDrivers() {
        return {openfire: "OpenFireConnectionDriver"};
    }

}

class ConnectionDriver {
    constructor(connection, data) {
        this.connection = connection;

    }

    disconnect() {

    }

    connect() {
        this.connection.chat.events.trigger("connecting", this);

        var t = this.doConnect();
        if (t)
            this.connection.chat.events.trigger("connected", this);
        else {
            this.connection.chat.events.trigger("error", this);

            if (this.connection.tryToConnect) {

                setTimeout(function () {
                    // console.log("aaa");
                    chat.connection.driver.connect();
                }, this.connection.tryConnectTimeOut);
            }

        }

    }

    sendMessage(src, dest, msg) {

    }

    sendReadedMessage(src, dest, msg) {

    }

    doConnect() {
        return false;

    }

    load(data) {

    }

    getUsers() {

    }

    getMessagesofUser(user) {

    }
}

function log(type, data) {
    // console.log(type + "-" + data);
}

class OpenFireConnectionDriver extends ConnectionDriver {
    constructor(connection, data) {
        super(connection, data);
        if (data == null) return;
        else this.load(data);


    }

    load(data) {

        this.service = data.service;
        this.serverDomain = data.serverDomain;
        this.user = data.user + "/Web";
        this.pass = data.password;
        this.strophe_connection = new Strophe.Connection(this.service);
        this.strophe_connection.rawInput = this.rawInput;
        this.strophe_connection.rawOutput = this.rawOutput;

        // this.strophe_connection.addHandler(this.onMessage, null, 'message', null, null,  null);

        // this.strophe_connection.addHandler(JabberFunctions.Version, Strophe.NS.VERSION, "iq");
        // this.strophe_connection.addHandler(JabberFunctions.Presence, null, "presence");
        // this.strophe_connection.addHandler(JabberFunctions.Message, null, "message");
        // this.strophe_connection.addHandler(JabberFunctions.Bookmarks, Strophe.NS.PRIVATE, "iq");
        // this.strophe_connection.addHandler(JabberFunctions.Room.Disco, Strophe.NS.DISCO_INFO, "iq", "result");
        // this.strophe_connection.addHandler(this.strophe_connection.disco._onDiscoInfo.bind(this.strophe_connection.disco), Strophe.NS.DISCO_INFO, "iq", "get");
        // this.strophe_connection.addHandler(this.strophe_connection.disco._onDiscoItems.bind(this.strophe_connection.disco), Strophe.NS.DISCO_ITEMS, "iq", "get");
        // this.strophe_connection.addHandler(this.strophe_connection.caps._delegateCapabilities.bind(this.strophe_connection.caps), Strophe.NS.CAPS);

    }


    connect() {
        this.strophe_connection.connect(this.user,
            this.pass,
            this.onConnect);
    }

    disconnect() {
        this.strophe_connection.disconnect();
    }

    sendMessage(src, dest, msg, data) {

        log('CHAT: Send a message to ' + +': ' + msg);

        var m = $msg({
            to: dest + '@' + this.serverDomain,
            from: src + '@' + this.serverDomain,
            type: 'chat'
        }).c("body").t(msg);

        m.up().c("token", {"xmlns": 'urn:xmpp:token'}).c("value", data);

        chat.connection.driver.strophe_connection.send(m);
    }

    emitTyping(src, dest, isTyping) {
        if (chat.connection.driver.strophe_connection == null)
            return;
        if (isTyping)
            chat.connection.driver.strophe_connection.chatstates.sendComposing(dest + '@' + this.serverDomain, 'chat');
        else
            chat.connection.driver.strophe_connection.chatstates.sendPaused(dest + '@' + this.serverDomain, 'chat');

    }


    onConnect(status) {

        if (status == Strophe.Status.CONNECTING) {
            chat.events.trigger("connecting", this);
        } else if (status == Strophe.Status.CONNFAIL || status == Strophe.Status.AUTHFAIL) {
            chat.events.trigger("error", this);
            if (chat.connection.tryToConnect) {

                setTimeout(function () {

                    chat.connection.driver.connect();
                }, chat.connection.tryConnectTimeOut);
                chat.connection.driver.disconnect();
            }
        } else if (status == Strophe.Status.DISCONNECTING) {
            chat.events.trigger("disconnecting", this);
        } else if (status == Strophe.Status.DISCONNECTED) {
            chat.events.trigger("disconnected", this);

        } else if (status == Strophe.Status.CONNECTED) {
            chat.events.trigger("connected", this);

            // set presence
            chat.connection.driver.strophe_connection.send($pres());
            // set handlers
            chat.connection.driver.strophe_connection.addHandler(onMessage, null, 'message', null, null, null);
            chat.connection.driver.strophe_connection.addHandler(onSubscriptionRequest, null, "presence", "subscribe");
            chat.connection.driver.strophe_connection.addHandler(onPresence, null, "presence");

            // listRooms();

        }
    }


    rawInput(data) {
        log('RECV', data);
    }

    rawOutput(data) {
        log('SENT', data);
    }

}

function getTokenFromMsg(user, msg) {
    var token = msg.getAttribute("id");
    if (token == null)
        token = msg.getElementsByTagName('token')[0].children[0].innerHTML;
    var message = user.messages.messages.getMessageFromToken(token);
    if (message == null) {
        if (msg.getElementsByTagName('token').length > 0)
            token = msg.getElementsByTagName('token')[0].children[0].innerHTML;
        return token;
    }
    else
        return token;
}

function onMessage(msg) {

    var to = msg.getAttribute('to');
    var from = msg.getAttribute('from');
    var type = msg.getAttribute('type');
    var elems = msg.getElementsByTagName('body');


    if (type == "chat") {

        if (elems.length == 0) {

            var s = "";
            if ($(msg).find("composing").length > 0)
                s = "composing";
            if ($(msg).find("paused").length > 0)
                s = "paused"
            if ($(msg).find("composing").length > 0 || $(msg).find("paused").length > 0) {
                var f = msg.getAttribute("from").substring(0, msg.getAttribute("from").indexOf("@"));
                var fr_user = chat.users.users.get(f);
                if (fr_user.name == chat.users.selected.name) {
                    fr_user.data.status = s;

                    fr_user.data.updateStatus();
                }

            }
            else

            if (s == "") {
                if ($(msg).find("recieved").length > 0 || $(msg).find("readed").length > 0) {
                    var tst = $(msg).find("recieved").length > 0 ? "recieved" : "readed";
                    var f = msg.getAttribute("from").substring(0, msg.getAttribute("from").indexOf("@"));
                    var fr_user = chat.users.users.get(f).data;
                    var token = getTokenFromMsg(fr_user, msg);
                    var message = fr_user.messages.messages.getMessageFromToken(token).data;

                    if (tst == "recieved")
                        message.recieved = true;
                    else
                        message.readed = true;
                    message.printState();

                }

            }


        }
        else {



            var body = elems[0];

            var f = from.substr(0, from.indexOf("@"));
            var b = Strophe.getText(body);
            b = b.replace("&lt;", "<").replace("&lt;", "<").replace("&gt;", ">").replace("&gt;", ">");
            var fr_user = chat.users.users.get(f).data;
            var token = getTokenFromMsg(fr_user, msg);

            fr_user.status = "paused";
            fr_user.updateStatus();
            var s = chat.users.users.get(f).data.messages.messages.getMessageFromToken(token);
            if (s == null) {
                var m = new Message(chat.users.users.get(f).data, b, false, true, true, true, null, token);
                chat.users.users.get(f).data.messages.messages.add(-1, m);
            }
            else
                m.checkStatus();


            log('CHAT: I got a message from ' + from + ': ' + Strophe.getText(body));

        }

    }

    // we must return true to keep the handler alive.
    // returning false would remove it after it finishes.
    return true;
}

function setStatus(s) {
    log('setStatus: ' + s);
    var status = $pres().c('show').t(s);
    chat.connection.driver.strophe_connection.send(status);
}

function subscribePresence(jid) {
    log('subscribePresence: ' + jid);
    chat.connection.driver.strophe_connection.send($pres({
        to: jid,
        type: "subscribe"
    }));
}

function getPresence(jid) {
    log('getPresence: ' + jid);
    var check = $pres({
        type: 'probe',
        to: jid
    });
    chat.connection.driver.strophe_connection.send(check);
}

function getRoster() {
    log('getRoster');
    var iq = $iq({
        type: 'get'
    }).c('query', {
        xmlns: 'jabber:iq:roster'
    });
    chat.connection.driver.strophe_connection.sendIQ(iq, rosterCallback);
}

function rosterCallback(iq) {
    log('rosterCallback:');
    $(iq).find('item').each(function () {
        var jid = $(this).attr('jid'); // The jabber_id of your contact
        // You can probably put them in a unordered list and and use their jids as ids.
        log(' >' + jid);
    });
}

function onSubscriptionRequest(stanza) {
    console.log(stanza);
    if (stanza.getAttribute("type") == "subscribe") {
        var from = $(stanza).attr('from');
        log('onSubscriptionRequest: from=' + from);
        // Send a 'subscribed' notification back to accept the incoming
        // subscription request
        connection.send($pres({
            to: from,
            type: "subscribed"
        }));
    }
    return true;
}

function onPresence(presence) {

    var presence_type = $(presence).attr('type');
    // var from = $(presence).attr('from');
    // if (!presence_type) presence_type = "online";
    // // console.log(' >' + from + ' --> ' + presence_type);
    // if (presence_type != 'error') {
    //     if (presence_type === 'unavailable') {
    //         var f = from.substr(0, from.indexOf("@"));
    //         chat.users.users.get(f).data.state = presence_type;
    //         chat.users.users.get(f).data.updateStatus();
    //     } else {
    //         var show = $(presence).find("show").text();
    //         if (show === 'chat' || show === '') {
    //             var f = from.substr(0, from.indexOf("@"));
    //             chat.users.users.get(f).data.state = presence_type;
    //             chat.users.users.get(f).data.updateStatus();
    //             // console.log(from);
    //             // Making contact as online
    //         } else {
    //             // etc...
    //         }
    //     }
    // }
    return true;
}

