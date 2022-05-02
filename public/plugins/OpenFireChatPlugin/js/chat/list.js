class List {
    constructor(parent) {
        this.list = Array();
        this.parent = parent;
    }
    getFromIndex(i) {

        return this.list[i];
    }
    get(name) {

        for (let i = 0; i < this.list.length; i++)
            if (this.list[i].name === name) {
                return this.list[i];
            }
        return null;
    }

    remove(index) {
        var s = new Array;
        for (let i = 0; i < this.list.length; i++)
            if (i != index)
                s.push(this.list[i]);
        this.list = s;

    }
    insert(name,data){

        var index = parseInt(name);
        var s = new Array;
        var inserted = false;
        for (let i = 0; i < this.list.length; i++)
            if (parseInt(this.getFromIndex(i).name)<index)
                s.push(this.list[i]);
        else
            {
                if(!inserted)
                {
                    var c = this.getObjectBuilded(name,data);
                    s.push(c);
                    inserted=true;
                }

                s.push(this.list[i]);

            }
        if(!inserted)
        {
            var c = this.getObjectBuilded(name,data);
            s.push(c);
            inserted=true;
        }
        this.list = s;
    }
    getChat()
    {
        return this.parent.chat;
    }

    clear() {
        this.list = new Array();
    }

    removeByname(name) {
        for (let i = 0; i < this.list.length; i++)
            if (this.list[i].name === name) {
                this.remove(i);
                break
            }
        return true;
    }
    getItemClass()
    {
        return "listItem";
    }
    length(){ return this.list.length;}
    getObjectBuilded(name,data)
    {

        var myObject = eval(this.getItemClass() );
        var c=  new myObject(this,name,data);
        return c;
    }
    add(name,data) {


        var c = this.get(name);

        if(c===null)
        {
            c = this.getObjectBuilded(name,data);
            this.list.push(c);
            //c = new className(name);
        }


    }

}

class listItem
{
    constructor(list,name,data)
    {
        this.list = list;
        this.name = name;
        this.data = data;
    }
}