/**
 * Chat state notifications (XEP 0085) plugin
 * @see http://xmpp.org/extensions/xep-0085.html
 */
Strophe.addConnectionPlugin('chatstates',
    {
        init: function (connection)
        {
            this._connection = connection;

            Strophe.addNamespace('CHATSTATES', 'http://jabber.org/protocol/chatstates');
        },

        statusChanged: function (status)
        {
            if (status === Strophe.Status.CONNECTED
                || status === Strophe.Status.ATTACHED)
            {
                this._connection.addHandler(this._notificationReceived.bind(this),
                    Strophe.NS.CHATSTATES, "message");
            }
        },

        addActive: function(message)
        {
            return message.c('active', {xmlns: Strophe.NS.CHATSTATES}).up();
        },

        _notificationReceived: function(message)
        {
            if ($(message).find('error').length > 0)
                return true;

            var composing = $(message).find('composing'),
                paused = $(message).find('paused'),
                active = $(message).find('active'),
                inactive = $(message).find('inactive'),
                gone = $(message).find('gone'),
                jid = $(message).attr('from');

            if (composing.length > 0)
            {
                $(document).trigger('composing.chatstates', jid);
            }

            if (paused.length > 0)
            {
                $(document).trigger('paused.chatstates', jid);
            }

            if (active.length > 0)
            {
                $(document).trigger('active.chatstates', jid);
            }

            if (inactive.length > 0)
            {
                $(document).trigger('inactive.chatstates', jid);
            }

            if (gone.length > 0)
            {
                $(document).trigger('gone.chatstates', jid);
            }

            return true;
        },

        sendActive: function(jid, type)
        {
            this._sendNotification(jid, type, 'active');
        },

        sendComposing: function(jid, type)
        {
            this._sendNotification(jid, type, 'composing');
        },

        sendPaused: function(jid, type)
        {
            this._sendNotification(jid, type, 'paused');
        },

        sendInactive: function(jid, type)
        {
            this._sendNotification(jid, type, 'inactive');
        },

        sendRecieved: function(jid, type,data)
        {
            this._sendNotification(jid, type, 'recieved',data);
        },
        sendReaded: function(jid, type,data)
        {
            this._sendNotification(jid, type, 'readed',data);
        },
        sendGone: function(jid, type)
        {
            this._sendNotification(jid, type, 'gone');
        },

        _sendNotification: function(jid, type, notification,data)
        {
            if (!type) type = 'chat';
           var m= $msg(
                {
                    to: jid,
                    type: type,

                })
                .c(notification, {xmlns: Strophe.NS.CHATSTATES});

            m.up().c("token", {"xmlns": 'urn:xmpp:token'}).c("value",data);

            this._connection.send(m);
        }
    });