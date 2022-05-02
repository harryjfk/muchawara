class Interpreter
{
    constructor(chat)
    {
        this.chat = chat;
        this.method = new List();
    }
    getInterpretableList(html)
    {
        var t= "";
        var c= "";
        var list = Array();
        var i=0;
        var copy = false;
        while(i<html.length)
        {
            var s =html.substr(i,2);
            if(s=="{{")
            {
                i+=2;
                copy =true;
            }

             else
            if(s=="}}")
            {
                list.push(c);
                c="";
                copy=false;
            }
            if(copy)
                c+=html[i];
            i++;
        }
      return list;
    }
    interpret(html,data)
    {
        var template = html;
      var l = this.getInterpretableList(html);
       for(var i in l)
       {
           var t = l[i];
           if(t.indexOf("(")!=-1)
           {
               var v= this.getMethodValue(t,data);
               template = template.replace("{{"+t+"}}",v);
           }
           else
               if(t.indexOf("?")!=-1)
               {
                   var v = this.getConditionValue(t,data);
                   if(v!=null)
                       template = template.replace("{{"+t+"}}",v);
               }
               else
           {
               var v = this.getPropertieValue(t,data);
               if(v!=null)
                   template = template.replace("{{"+t+"}}",v);
           }

       }

       return template;

    }
    getConditionValue(c,data)
    {
        // if(c.indexOf("isText")!=-1)
        //     console.log("aa");

        var t  = c.split("?");
        var cond = t[0].trim();
        if(cond.indexOf(".")!==-1)
        {
            var c= "";
            var i =0;
            var list = new Array();
            var copy = false;
            while(i<cond.length)
            {
                var s =cond.substr(i,1);
                if(s!==" " && s!=="=" &&s!=="!" && s!==">"  && s!=="&")
                {

                    copy =true;
                }

                else
                if(s===" " || s==="!"|| s==="=" || s===">" || s==="&")
                if(copy)
                {
                    list.push(c);
                    c="";
                    copy=false;
                }
                if(copy)
                    c+=cond[i];
                i++;
            }
            if(c!="")
                list.push(c);
          for(var i=0;i<list.length;i++)
          {

              var cond1 = list[i];
              if(cond1.indexOf(".")!==-1)
              {

                  var cv1 =  this.interpret("{{"+cond1+"}}",data);
                  cond = cond.replace(cond1,cv1);
              }

          }
        }
        var response = t[1].split(":");
        // if(cond.indexOf("&")!=-1)
        // {
        //     var cs = cond.split("&");
            // for(var i=0;i<cs.length;i++)
            // {
            //     var cond1 = cs[i].trim().replace("!","");
            //     var cv1 =  this.interpret("{{"+cond1+"}}",data);
            //    cond = cond.replace(cond1,cv1);
            // }
            try
            {
           var    cv = eval(cond).toString();
            }catch (e){}

        // }
        // else
        // {
        //     var cv =  this.interpret("{{"+cond+"}}",data);
        // }


        return cv==="true"|| cv==="1"? response[0]:response[1];

    }
    getMethodValue(m,data)
    {
        var t = m.split("(");
        var mName = t[0].trim();
        var tparams = t[1].split(")");
        var params = Array();
        for (var i in tparams)
            if(tparams[i].trim()!="")
                params.push(tparams[i].replace('"','').replace('"',''));
        var method = this.method.get(mName);
        return method.data(this,params,data);
    }
    getPropertieValue(prop,data)
    {
        var t  =prop.split(".");
        var obj = data;
        for (var i in t )
            if(obj!=null)
            obj = obj[t[i]];
        else
            {obj=""; break;}

       return obj;

        // data[t];
    }


}