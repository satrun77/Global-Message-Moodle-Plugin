var loading, globalmessage = {};
globalmessage.select = function(select) {
    var loopOptions;
    loopOptions = function(callback) {
        var options = select.options;
        for (var i=0; i<options.length; i++) {
            if (callback(options[i], i)) {
                return true;
            }
        }
        return false;
    };
    this.add = function(option) {
        if (YAHOO.env.ua.gecko > 0) {
            select.add(option, null);
        } else {
            select.add(option, select.length);
        }
    };
    this.addIfNew = function(option) {
        if (!this.isValueExist(option.value)) {
            return this.add(option);
        }
    };
    this.isValueExist = function(value) {
        return loopOptions(function(op, i) {
            if (op.value == value) {
                return true;
            }
            return false;
        });
    };
    this.removeIfOption = function() {
        this.removeOptionWithValue('if', function(select) {
            select.removeAttribute('disabled');
            return true;
        })
    };
    this.removeOptionWithValue = function(value, callback) {
        return loopOptions(function(op, i) {
            if (op.value == value) {
                select.options[i] = null;
                if (typeof(callback) != 'undefined') {
                    return callback(select);
                }
                return true;
            }
            return false;
        });
    };
    this.addIfOption = function() {
        this.add(new Option("IF", "if"));
        select.selectedIndex = select.length-1;
        select.disabled = true;
    }
    this.removeAllOptions = function() {
        select.length = 0;
    }
    this.addOptionsFromSelect = function(otherSelect) {
        this.removeAllOptions();
        var options = otherSelect.options;
        for (var i=0; i<options.length; i++) {
            this.add(new Option(options[i].innerHTML, options[i].value));
        }
        return select;
    }
    return this;
};
globalmessage.string = function(name) {
    var t = YAHOO.util.Selector.query("input[name^="+name+"]", 'gm-strings', true);
    if (typeof t == 'object') {
        return t.value;
    }
    return '';
};
globalmessage.alternatetablerows = function(el) {
    var rows = document.getElementById(el).getElementsByTagName('tr');
    for (var i=0; i<rows.length; i++) {
        var className = ('r' + (i%2? 1 : 0));
        YAHOO.util.Dom.addClass(rows[i], className); 
    }
};
globalmessage.dialog = function() {
    var self = this;
    this.elementid = null;
    this.loading = null;
    this.width = "750px";
    this.saveButtonText = globalmessage.string('submit');
    this.isrendered = false;
    this.dialog = null;
    this.handleSubmit = function() {
        loading.show();
        this.submit();
    };
    this.handleCancel = function() {
        this.cancel();
    };
    this.handleSuccess = function(response, o) {
    };
    this.handleFailure = function(response, o) {
        return false;
    };
    this.validate = function(data) {
        return true;
    }
    this.afterRender = function() {
    };
    this.render = function() {
        // Remove progressively enhanced content class, just before creating the module
        YAHOO.util.Dom.removeClass(this.elementid, "yui-pe-content");
        // Instantiate the Dialog
        this.dialog = new YAHOO.widget.Dialog(this.elementid,
        {
            modal: false,
            width : this.width,
            fixedcenter : true,
            visible : false,
            constraintoviewport : false,
            hideaftersubmit: false,
            autofillheight: false,
            buttons : [ {
                text:this.saveButtonText,
                handler:this.handleSubmit,
                isDefault:true
            },
            {
                text:"Cancel",
                handler:this.handleCancel
            } ]
        });
        // Validate the entries in the form to require that both first and last name are entered
        this.dialog.validate = function() {
            if (!self.validate(this.getData())) {
                loading.hide();
                return false;
            }
            return true;
        };
        // Wire up the success and failure handlers
        this.dialog.callback = {
            success: function(o) {
                loading.hide();
                var response = YAHOO.lang.JSON.parse(o.responseText);
                self.handleSuccess(response, o);
            },
            failure: function(o) {
                loading.hide();
                var response = YAHOO.lang.JSON.parse(o.responseText);
                if (!self.handleFailure(response, o)) {
                    alert(globalmessage.string('failedajax'));
                }
            }
        };
        this.dialog.render();
        this.dialog.center();
        var dialogel = YAHOO.util.Dom.get(this.elementid+'_c');
        if (parseInt(dialogel.style.top) < 0) {
            dialogel.style.top = '0px';
        }
        this.isrendered = true;
        this.afterRender();
    }
    this.show = function() {
        //if (this.isrendered) {
        this.dialog.show();
    //}
    }
    return this;
};
globalmessage.ajax = function(options) {
    if (typeof(options.failure) == 'undefined') {
        options.failure = function(response, o) {
            return false;
        };
    }
    if (typeof(options.success) == 'undefined') {
        options.success = function(response, o) {
        };
    }
    loading.show();
    YAHOO.util.Connect.asyncRequest('GET', options.url, {
        success: function(o) {
            loading.hide();
            var response = YAHOO.lang.JSON.parse(o.responseText);
            options.success(response, o);
        },
        failure: function(o) {
            loading.hide();
            var response = YAHOO.lang.JSON.parse(o.responseText);
            if (!options.failure(response, o)) {
                alert(globalmessage.string('failedajax'));
            }
        }
    }, null);
};
globalmessage.highlightRow = function(row, type) {
    var tds = row.getElementsByTagName('td');
    for (var i = 0; i < tds.length; i++) {
        if (type) {
            tds[i].style.backgroundColor = '#EDFF8C';
        } else {
            tds[i].style.backgroundColor = '';
        }
    }
};