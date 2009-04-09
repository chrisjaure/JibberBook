//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	inc/jbscript.js
//-------------------------------------------------------------------------------------

Element.extend({
    injectHTML: function(content, where){
        new Element('div').setHTML(content).getChildren().inject(this, where);
        return this;
    }
});

var Guestbook = {

    // object variables
    obj: {},
    fx: {},
    mouseDown: false,
    offset: 2,
    
    /*
     Function: submitForm
     
     Captures form submission event and uses ajaxReq instead.
     It also sets the form value _ajax to true, enabling the server-side script to differentiate regular submissions from ajax submissions.
    */
    submitForm: function(event){
        this.loading();
        this.obj.form.elements['_ajax'].value = true;
        this.ajaxReq.send(this.obj.form.getProperty('action'), this.obj.form.toQueryString());
        event.stop();
    },
    
    /*
     Function: processAddResponse
     
     Attached to onComplete event of ajaxReq. Expects JSON object to be returned from the server with 'value', 'content', and 'message' properties.
     
     value:
     1 indicates successful addition of comment
     2 indicates comment flagged as spam
     >=3 indicates validation error
     content: contains comment html if value == 1
     message: contains descriptive text associated with response
    */
    processAddResponse: function(){
        var response = Json.evaluate.attempt(this.ajaxReq.response.text);
        if (response) {
            if (response.value != '1') {
                this.showMessage(response.message, 'error');
                if (response.value == '2') 
                    this.obj.form.reset();
            }
            else {
                this.obj.commentWrapper.injectHTML(response.content, 'top');
                var new_comment = this.obj.commentWrapper.getFirst();
                if (this.obj.scroller == window) {
                    this.fx.scroll.toElement(this.obj.commentWrapper).chain(function(){
                        this.showMessage(response.message, 'confirm');
                    }.bind(this));
                }
                else {
                    this.fx.scroll.toTop();
                    this.showMessage(response.message, 'confirm');
                }
                this.obj.form.reset();
            }
        }
        else 
            this.showMessage(this.text.SERVER_ERROR, 'error');
        this.loaded();
    },
    
    /*
     Function: processLoadComments
     
     Attached to onComplete event of loadComments. Expects JSON object to be returned from the server with 'value' and 'content' properties.
     
     value:
     0 indicates no additional comments to load
     content: contains comment html
    */
    processLoadComments: function(){
        var response = Json.evaluate(this.loadComments.response.text);
        if (response.value == '0') {
            this.obj.scroller.removeEvents();
            this.obj.loadingMessage.setText(this.text.COMMENTS_LOADED);
        }
        this.obj.loadingMessage.injectHTML(response.content, 'before');
    },
    
    /*
     Function: loading
     
     Used to indicate ajax activity.
    */
    loading: function(){
        this.obj.submitButton.setProperties({
            'disabled': true,
            'value': this.text.LOADING
        });
    },
    
    /*
     Function: loaded
     
     Reverses actions of loading().
    */
    loaded: function(){
        this.obj.submitButton.setProperties({
            'disabled': false,
            'value': this.obj.submitButton.orgVal
        });
    },
    
    /*
     Function: show
     
     Attached to obj.scroller scroll event. Determines when to load additional comments.
    */
    show: function(){
        if (!this.mouseDown) {
            var dimensions = this.obj.scroller.getSize();
            var offset = (this.obj.scroller == window) ? 0 : this.obj.scroller.getTop();
            if (this.obj.loadingMessage.getTop() - offset < dimensions.scroll.y + dimensions.size.y) {
                if (!this.loadComments.running) {
                    this.loadComments.send('actions/loadcomments.php', 'offset=' + this.offset);
                    this.offset++;
                }
            }
        }
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
        });
        this.obj.messageWrapper.className = type;
        this.obj.messageWrapper.getFirst().setText(text);
        this.fx.message.start(0.9);
    },
    
    /*
     Function: initialize
     
     This method must be called to start the script and enable ajax functionality.
     
     Parameters:
     form - id of the form
     comments - id of comments wrapper
     message - id of the message wrapper
    */
    initialize: function(form, comments, message, loading, text){
    
        // set objects
        this.obj.form = $(form);
        this.obj.commentWrapper = $(comments);
        this.obj.comments = this.obj.commentWrapper.getChildren();
        this.obj.messageWrapper = $(message);
        this.obj.submitButton = this.obj.form.getElement('input[type=submit]');
        this.obj.submitButton.orgVal = this.obj.submitButton.value;
        this.obj.loadingMessage = $(loading);
        this.text = text;
        var parent = this.obj.commentWrapper;
        while (parent) {
            if (parent.getTag() == 'body') {
                this.obj.scroller = window;
                parent = null;
                break;
            }
            var overflow = parent.getStyle('overflow');
            if (overflow == 'auto' || overflow == 'scroll') {
                this.obj.scroller = parent;
                parent = null;
            }
            else {
                parent = parent.getParent();
            }
        }
        
        // set effects
        this.fx.message = new Fx.Style(this.obj.messageWrapper, 'opacity');
        this.fx.scroll = new Fx.Scroll(this.obj.scroller, {
            'duration': 1000,
            'transition': Fx.Transitions.Circ.easeInOut
        });
        
        // set hxr objects 
        this.loadComments = new XHR({
            method: 'get',
            'onSuccess': this.processLoadComments.bind(this)
        });
        this.ajaxReq = new XHR({
            method: 'post',
            'onSuccess': this.processAddResponse.bind(this),
            'onFailure': function(){
                this.showMessage(this.text.ERROR, 'error');
                this.loaded();
            }.bind(this)
        });
        
        // attach events
        this.obj.messageWrapper.addEvent('mouseover', function(){
            this.fx.message.start(0);
        }.bind(this));
        if (this.obj.loadingMessage) {
            this.obj.loadingMessage.setText(this.text.COMMENTS_LOADING);
            this.obj.scroller.addEvents({
                'scroll': this.show.bind(this),
                'mousedown': function(){
                    this.mouseDown = true;
                }.bind(this),
                'mouseup': function(){
                    this.mouseDown = false;
                    this.show();
                }.bind(this)
            });
        }
        this.obj.form.addEvent('submit', this.submitForm.bindWithEvent(this));
        
        // misc
        if (window.ie6) {
            this.obj.messageWrapper.setStyle('zoom', 1);  // fix background issue in ie6
        }
    }
};
