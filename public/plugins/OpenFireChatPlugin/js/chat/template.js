class Template
{

   constructor(chat,selector)
   {
       this.chat = chat;
       this.setInterpreterMethods();
       this.selector =selector;
       this.templates = new TemplateList(this);
       if(chat.users ==null)
           return;
       this.buildTemplates();
   }
   setInterpreterMethods()
   {
       this.chat.interpreter.method.add("render",function(e,params,data){

           if(data==null)
          data = new Object();
          data.current_user = e.chat.users.user;
         var template =   e.chat.template.render(params[0],data);

       return template;
       });

   }
   buildTemplates()
   {
     this.templates.add("base",
         '<div id="sidepanel" class="messenger" >\n'+

        ' {{render("user-top-panel")}}{{render("connection")}}{{render("search-form")}}'+
        '  {{render("user-panel"}}'+

         '        <div id="bottom-bar">\n' +
         // '            <button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span>\n' +
         // '            </button>\n' +
         // '            <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>\n' +
         '        </div>\n' +
         '    </div>'+  ' <div class="content">' +
         ' {{render("user-view"}}' +'' +
         '  <div class="messages">\n' +
         '                <div class="message-view-loading hide ">\n' +
         '                <img src="'+this.chat.base_dir+'plugins/OpenFireChatPlugin/images/loader.gif">\n' +
         '                </div>\n' +
         '            <ul></ul>'+
         '</div>' +

         ' {{render("message-form"}}' +'' +
         '</div>' );
       this.templates.add("message-form",    ' <div class="message-input">\n' +
           '            <div class="wrap">\n' +
           '                <input type="text" placeholder="Escribe un mensaje..."/>\n' +
           '                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>\n' +
           '                <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>\n' +
           '            </div>\n' +
           '        </div>'  );
       this.templates.add("user-view",' <div class="contact-profile">'+
           '   <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt=""/>'+
           '     <p id="view_user_name">Harvey Specter</p><span class="typing " style="display: none"> esta escribiendo</span>'+
           '   <div class="social-media">'+
           '     <i class="fa fa-facebook" aria-hidden="true"></i>'+
       '      <i class="fa fa-twitter" aria-hidden="true"></i>'+
           '    <i class="fa fa-instagram" aria-hidden="true"></i>'+
           '    </div>'+
       '    </div>');
   //     this.templates.add("message-title-bar",'<div class="row title-bar">\n' +
   // '            <div class="col-xs-6 col-sm-8 col-md-10 title">\n' +
   // '                \n' +
   // '            </div>\n' +
   // '            <div class="col-xs-6 col-sm-4 col-md-2">\n' +
   // '                <div class="row">\n' +
   // '                    <div class="col-xs-4 phone">\n' +
   // '                        <a href="#"><i class="fa fa-phone"></i></a>\n' +
   // '                    </div>\n' +
   // '                    <div class="col-xs-4 camera">\n' +
   // '                        <a href="#"><i class="fa fa-camera"></i></a>\n' +
   // '                    </div>\n' +
   // '                    <div class="col-xs-4 info" >\n' +
   // '                        <a href="#"><i class="fa fa-info"></i></a>\n' +
   // '                    </div>\n' +
   // '\n' +
   // '                </div>\n' +
   // '            </div>\n' +
   // '        </div>');
       this.templates.add("user-item",
           '                <li   id="{{name}}_row" class="contact">\n' +
           '                    <div class="wrap">\n' +
           '                        <span class="contact-status {{status}}"></span>\n' +
           '                        <img src="{{img}}" alt=""/>\n' +
           '                        <div class="meta">\n' +
           '                            <p class="name">{{fullname}}</p>\n' +
           '                            <p class="preview">{{last_message.text}}</p>\n' +
           '                        </div>\n' +
           '                    </div>\n' +
           '                </li>\n' );
       this.templates.add("user-top-panel",
       '        <div id="profile">\n' +
       '            <div class="wrap">\n' +
       '                <img id="profile-img" src="'+chat.base_dir+'{{current_user.profile_picture}}" class="online" alt=""/>\n' +
       '                <p>{{current_user.fullname}}</p>\n' +
       '                <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>\n' +
       '                <div id="status-options">\n' +
       '                    <ul>\n' +
       '                        <li id="status-online" class="active"><span class="status-circle"></span>\n' +
       '                            <p>Online</p></li>\n' +
       '                        <li id="status-away"><span class="status-circle"></span>\n' +
       '                            <p>Away</p></li>\n' +
       '                        <li id="status-busy"><span class="status-circle"></span>\n' +
       '                            <p>Busy</p></li>\n' +
       '                        <li id="status-offline"><span class="status-circle"></span>\n' +
       '                            <p>Offline</p></li>\n' +
       '                    </ul>\n' +
       '                </div>\n' +
       '                <div id="expanded">\n' +
       // '                    <label for="twitter"><i class="fa fa-facebook fa-fw" aria-hidden="true"></i></label>\n' +
       // '                    <input name="twitter" type="text" value="mikeross"/>\n' +
       // '                    <label for="twitter"><i class="fa fa-twitter fa-fw" aria-hidden="true"></i></label>\n' +
       // '                    <input name="twitter" type="text" value="ross81"/>\n' +
       // '                    <label for="twitter"><i class="fa fa-instagram fa-fw" aria-hidden="true"></i></label>\n' +
       // '                    <input name="twitter" type="text" value="mike.ross"/>\n' +
       '                </div>\n' +
       '            </div>\n' +
       '        </div>\n');
       this.templates.add("connection",'' +
           // '<div class="row conx-msg-row connected-conx">\n' +
           // '            <div class="col-xs-12 ">\n' +
           // '                Error en conexión con el servidor\n' +
           // '            </div>\n' +
           '\n' +
           // '        </div>' +
           '');
       this.templates.add("search-form",
           '        <div id="search">\n' +
           // '            <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>\n' +
           // '            <input type="text" placeholder="Search contacts..."/>\n' +
           '        </div>\n' );
       this.templates.add("user-panel",
           '        <div id="contacts">\n' +
           '            <ul>\n' +

       '            </ul>\n' +
       '        </div>\n' );
       this.templates.add("message-item",'  <li id="message_{{message.index}}" class="{{message.sended?sent:replies}}">\n' +

           '        <div>           <p>{{message.text}}<span  ><i style=" display: none;" class="fa fa-check recieved"></i><i style=" display: none;" class="fa fa-check readed"></i></span></p>\n' +
           ''+
           '              </div>   </li>\n' );

   }

   render(name,params)
   {
       var item = this.templates.get(name);
       if(item!=null)
       return item.render(params);
       return null;
   }

}

class TemplateList extends List
{

    constructor(parent)
    {
        super(parent);
    }
    getItemClass()
    {
        return "TemplateItem";
    }


}

class TemplateItem extends listItem
{
    render(params)
    {

       return this.list.getChat().interpreter.interpret(this.data,params);
        // return (this.getRenderedData());
    }

}



// '<div class="col-xs-5 col-md-4  panel-users">{{render("user-top-panel")}}{{render("connection")}}{{render("search-form")}}{{render("user-panel"}}</div>' +
// '<div class="col-xs-7 col-md-8 view-message">' +
// '{{render("message-title-bar")}}' +
// '<div class="row message ">          <div class="col-xs-8 message-div">\n' +
// '                <div class="row message-view-loading hide">\n' +
// '                <img src="'+this.chat.base_dir+'plugins/OpenFireChatPlugin/images/loader.gif">\n' +
// '                </div>\n' +
// '                <div class="row message-view-container">\n' +
// '                    <div class="col-xs-12" id="message-inner-container">' +
// '                    </div>\n' +
// '                </div>' +
// '{{render("message-form")}}  </div>'+
// '{{render("user-view")}}' +
//
//
//
// <div class="row user {{!last_message.readed &  !last_message.sended ?unread:}}" id="{{name}}_row">\n' +
// '                    <div class="col-xs-4 col-md-3 img-container">\n' +
// '                        <img src="{{img}}">\n' +
// '                    </div>\n' +
// '                    <div class="col-xs-8 col-md-9 user-data">\n' +
// '                        <div class="row">\n' +
// '                            <div class="col-xs-8 col-md-9 title">\n' +
// '                                {{fullname}}\n' +
// '                            </div>\n' +
// '                            <div class="col-xs-4 col-md-3 time">\n' +
// '                                {{last_time}}\n' +
// '                            </div>\n' +
// '                        </div>\n' +
// '                        <div class="row">\n' +
// '                            <div class="col-xs-8 col-md-9 message-text">\n' +
// '                               {{last_message.text}}\n' +
// '                            </div>\n' +
// '                            <div class="col-xs-4 col-md-3">\n' +
// '                                <a href="#" class="temp" onclick="$(\'.temp\').first().trigger(\'contextmenu\')"><i class="fa fa-gear"></i></a>\n' +
// '                                <ul style="display: none" id="context-140b47e523b65a39bcc2f0c1daba4467-3900e749fe79e4a7eb197cfa376251fe">\n' +
// '\n' +
// '                                    <li class="role role-moderator affiliation-owner" data-tooltip="Moderator">Moderador</li>\n' +
// '                                    <li data-type="ignore" class="ignore" data-tooltip="You ignore this user">Ignorar</li>\n' +
// '                                </ul>\n' +
// '                            </div>\n' +
//
// '                        </div>\n' +
// '                    </div>\n' +
// '                </div>
//


//   this.templates.add("message-item",'<div id="message_{{message.index}}" class="row message-item {{message.sended?out:inbox}}">\n' +
// '                            <div class="col-xs-1 img-container {{message.sended?hide:}} ">\n' +
// '                                <img src="{{srcUser.img}}" class=" {{message.sended?hide:read-status}}">\n' +
// '                            </div>\n' +
// '                            <div class="col-xs-10 col-sm-{{message.sended?11:10}} text  {{message.isText==true?:image-msg}}">\n' +
// '                               <{{message.text.length > 40?p:span}} class="{{message.isText==true?show_text:hide_text}}">{{message.text}}</{{message.text.length > 40?p:span}}>\n' +
//       '                               <p class="  {{message.isImage==true?show_text:hide_text}}"><img class="image_text" src="{{message.text}}"/></p>\n' +
//
// '                            </div>\n' +
// '                            <div class="col-xs-2 col-sm-{{message.sended?1:1}} img-container-mini {{message.sended?read-status:hide}} hide">\n' +
// '                                <img src="{{message.user.img}}">\n' +
// '                            </div>\n' +
// '                        </div>');


// '<div class="row message-sender">\n' +
//         '                    <div class="col-xs-12" style="padding: 0">\n' +
//         '                        <div class="row">\n' +
//         '                            <div class="col-xs-12">\n' +
//         '                                <input name="msg_text" id="msg_text" type="text" placeholder="Escribe un mensaje">\n' +
//         '                                \n' +
//         '                            </div>\n' +
//         '                        </div>\n' +
//         '                        <div class="row actions-row">\n' +
//         '                            <div class="col-xs-3 col-sm-1">\n' +
//         '                                <a href="#" id="picUpload"><i class="fa fa-picture-o"></i></a>\n' +
//         '                            </div>\n' +
//         '                            <div class="col-xs-3 col-sm-1">\n' +
//         '                                <a href="#" id="cameraUpload"><i class="fa fa-camera"></i></a>\n' +
//         '                            </div>\n' +
//         '                        </div>\n' +
//         '                    </div>\n' +
//         '                </div>'