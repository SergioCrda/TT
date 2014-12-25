if(!window["MUI"]) window["MUI"]={};

MUI.LinkedList = new Class({
    initialize:function(source, dest, fillType, options){
	this.setOptions(options);
	this.source = $(source);
	this.dest = $(dest);
	this.fillType = fillType;
	this.source.addEvent(this.options.catchEvent || "change", this._fill.bind(this));
    },
    
    _fill:function(){
	this.dest.empty();
        if(this.source.value=="") return;
	new Element("option").injectInside(this.dest); // empty option
	switch(this.fillType){
	    case 'JSArray':
		this._fillByArray(this.options.data[this.source.value]);
		this.fireEvent("fill");
		break;
	    case 'Ajax':
		new Json.Remote(this.options.url, this.options.ajaxOptions)
		    .addEvent("onComplete", function(data){
			this._fillByArray(data);
			this.fireEvent("fill");
		    });
		break;
	    default: case 'Custom':
		this.options.customMethod.call(this, function(){
		    this.fireEvent("fill");
		}.bind(this));
		break;
	}
    },
	
    _fillByArray:function(data){
	    data.each(function(item){
		var options = new Element("option")
		    .setProperty("value", item['value'])
		    .set('html', item["text"])
		    .injectInside(this.dest);
	    }.bind(this));
	}
    
    
});

MUI.LinkedList.implement(new Events, new Options);

Element.implement({
    linkWith:function(dest, fillType, options){
	this.link = new MUI.LinkedList(this, dest, fillType, options);
	return this;
    }
})