//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/inc/jbascript.js
//-------------------------------------------------------------------------------------

new Asset.css('inc/jbastyle_js.css');

var guestbookAdmin = {

    // object variables
    obj: {},
    fx: {},
    
    /*
     Function: processResponse
     
     Attached to xhrReq onComplete event. Expects JSON object to be returned from the server with 'value', 'id', and 'message' properties.
     
     value: 1 indicates success
     id: id of comment reclassified or deleted, set to 'all' if all comments are deleted
     message: descriptive text associated with the response
    */
    processResponse: function(){
        var response = Json.evaluate.attempt(this.xhrReq.response.text);
        if (response) {
            if (response.value == '1') {
                if (response.id == 'all') 
                    $$('.comment').each(function(el){
                        el.remove()
                    });
                else 
                    $(response.id).remove();
                this.showMessage(response.message, 'confirm');
            }
            else {
                this.showMessage(response.message, 'error');
            }
        }
        else 
            this.showMessage(this.text.ERROR, 'error');
    },
    
    /*
     Function: showMessage
     
     Shows a message based on text and type.
     
     Parameters:
     text - Message text to show.
     type - Class to be applied to message container (overwrites all previous classes).
    */
    showMessage: function(text, type){
        this.obj.messageWrapper.setStyles({
            'left': (window.getWidth() / 2 - 150),
            'top': window.getScrollTop() + 30,
            'opacity': 0
        })
        this.obj.messageWrapper.className = type;
        this.obj.messageWrapper.getFirst().setText(text);
        this.fx.message.start(0.9);
    },
    
    /*
     Function: responseFailure
     
     Attached to xhrReq onFailure event.
    */
    responseFailure: function(){
        this.showMessage(this.text.ERROR, 'error');
    },
    
    /*
     Funtion: initialize
     
     Starts the admin script.
    */
    initialize: function(text){
        this.text = text;
        this.xhrReq = new XHR({
            'method': 'get',
            'onSuccess': this.processResponse.bind(this),
            'onFailure': this.responseFailure.bind(this)
        });
        $$('.mark_spam, .mark_ham, .delete_comment, #delete_spam').each(function(el){
            el.addEvent('click', function(e){
                if (!this.xhrReq.running) 
                    this.xhrReq.send(el.getProperty('href'), '_ajax=1');
                this.showMessage(this.text.LOADING, 'loading');
                new Event(e).stop();
            }.bind(this));
        }.bind(this));
        this.obj.messageWrapper = $('message').setStyles({
            'left': (window.getWidth() / 2 - 150),
            'top': window.getScrollTop() + 30
        });
        if (window.ie6) 
            this.obj.messageWrapper.setStyle('zoom', 1); // fix background issue in ie6
        this.fx.message = this.obj.messageWrapper.effect('opacity');
        this.obj.messageWrapper.addEvent('mouseover', function(){
            this.fx.message.start(0);
        }.bind(this));
    }
}
