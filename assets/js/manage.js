var messagedialog, ruledialog, loading;
var globalmessage = {};
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
            fixedcenter : false,
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
globalmessage.messagedialog = function() {
    globalmessage.dialog.prototype.constructor.call(this);
    this.elementid  = "gm-create-message-dialog";
    this.moodleEditorStatus = false;
    this.resetFields = function() {
        document.getElementById('id_actiontype').value = 'insert';
        document.getElementById('id_name').value = '';
        document.getElementById('id_summary').value ='';
        document.getElementById('id_status').value = '';
        document.getElementById('mform1').id.value = '';
        document.getElementById('id_design').value = '';
        document.getElementById('id_message').value = '';
        if (typeof(tinyMCE) != 'undefined') {
            tinyMCE.get('id_description').setContent('');
        }
        YAHOO.util.Dom.get('create-title').style.display = 'block';
        YAHOO.util.Dom.get('edit-title').style.display = 'none';
    };
    this.updateFields = function(data) {
        document.getElementById('id_actiontype').value = 'update';
        document.getElementById('id_name').value = data.name;
        document.getElementById('id_summary').value = data.summary;
        document.getElementById('id_status').value = data.status;
        document.getElementById('mform1').id.value = data.id;
        document.getElementById('id_description').value = data.description;
        document.getElementById('id_design').value = data.design;
        document.getElementById('id_message').value = data.id;
        if (typeof(tinyMCE) != 'undefined') {
            tinyMCE.get('id_description').setContent(data.description);
        } else if (typeof(editor_67daf92c833c41c95db874e18fcb2786) != 'undefined') {
            editor_67daf92c833c41c95db874e18fcb2786.setHTML(data.description);
        }
        YAHOO.util.Dom.get('create-title').style.display = 'none';
        YAHOO.util.Dom.get('edit-title').style.display = 'block';
    };
    this.handleSuccess = function(response, o) {
        if (typeof response.error == 'undefined'){
            return alert(globalmessage.string('messageerror1'));
        } else if (typeof response.error != 'undefined' && response.error == 1){
            return alert(response.message);
        }
        var table = YAHOO.util.Dom.get('gm-table'),
        row = YAHOO.util.Selector.query('.message-'+response.id, 'gm-table', true);

        if (row == null) {
            var tableHTML = '<table id="gm-temp-table" style="position:absolute;left:-10000000px">' + response.rowcontent + '</table>';
            var tempEl = document.createElement('div');
            document.body.appendChild(tempEl);
            tempEl.innerHTML = tableHTML;
            var newRows = tempEl.getElementsByTagName('tr');
            table.appendChild(newRows[0]);
            document.body.removeChild(tempEl);
        } else {
            var tds = YAHOO.util.Selector.query('td', row);
            tds[0].getElementsByTagName('a')[0].innerHTML = response.data[0];
            tds[1].innerHTML = response.data[1];
            tds[3].innerHTML = response.data[2];
        }
        globalmessage.alternatetablerows('gm-table');
        this.dialog.cancel();
        return true;
    }
    this.validate = function(data) {
        console.log(data)
        var description = data.description;
        if (typeof(tinyMCE) != 'undefined') {
            description = tinyMCE.get('id_description').getContent();
            tinyMCE.triggerSave();
        } else if (typeof(editor_67daf92c833c41c95db874e18fcb2786) != 'undefined') {
            description = editor_67daf92c833c41c95db874e18fcb2786.getHTML();
            document.getElementById('id_description').value = description;
        }
        if (data.name == "" || description == "") {
            alert(globalmessage.string('messageerror2'));
            return false;
        }
        return true;
    }
    this.show = function() {
        this.dialog.show();

        // only if moodle standard html editor are used
        // re-generate the editor
        if (typeof(editor_67daf92c833c41c95db874e18fcb2786) != 'undefined' && !this.moodleEditorStatus) {
            var dialogdec = YAHOO.util.Selector.query(".htmlarea", 'gm-create-message-dialog', true);
            dialogdec.parentNode.removeChild(dialogdec);
            editor_67daf92c833c41c95db874e18fcb2786.generate();
            this.moodleEditorStatus = true;
        }
    }
    return this;
};
globalmessage.showeditform = function(messageid) {
    globalmessage.ajax({
        url: 'index.php?action=index/getmessage&id='+messageid,
        success: function(response, o) {
            messagedialog.updateFields(response);
            messagedialog.show();
        }
    });
};
globalmessage.removemessage = function(messageid) {
    var highlightRow, actionYes, actionNo;

    highlightRow = function(type) {
        var row = YAHOO.util.Selector.query('.message-'+messageid, 'gm-table', true),
        tds = row.getElementsByTagName('td');
        for (var i = 0; i < tds.length; i++) {
            if (type) {
                tds[i].style.backgroundColor = '#EDFF8C';
            } else {
                tds[i].style.backgroundColor = '';
            }
        }
    };
    actionYes = function() {
        var dialog = this;
        globalmessage.ajax({
            url: 'index.php?action=index/removemessage&id='+messageid,
            success: function(response, o) {
                if (response.error == 1) {
                    highlightRow(false);
                }
                alert(response.message);
                var removerow = YAHOO.util.Selector.query('.message-'+messageid, 'gm-table', true);
                removerow.style.display = 'none';
                //document.getElementById('gm-table').removeChild(removerow);
                dialog.hide();
            }
        });
    };
    actionNo = function() {
        this.hide();
        highlightRow(false);
    };
    // Instantiate the Dialog
    var confirmDialog = new YAHOO.widget.SimpleDialog("remove-message-dialog", {
        width: "330px",
        fixedcenter: true,
        visible: false,
        draggable: false,
        close: true,
        text: globalmessage.string('removemessagetext'),
        icon: YAHOO.widget.SimpleDialog.ICON_HELP,
        constraintoviewport: true,
        buttons: [ {
            text:globalmessage.string('yes'),
            handler:actionYes,
            isDefault:true
        }, {
            text:globalmessage.string('no'),
            handler:actionNo
        } ]
    } );
    confirmDialog.setHeader(globalmessage.string('confirmtitle'));
    confirmDialog.render("middle-column");
    confirmDialog.show();
    highlightRow(true);
};
globalmessage.designdialog = function() {
    globalmessage.dialog.prototype.constructor.call(this);
    var elements = YAHOO.util.Selector.query('input[type=text], select', 'gm-design-form'),
    self = this;
    this.elementid  = "gm-design-message-dialog";
    this.saveButtonText = globalmessage.string('save');
    this.handleSuccess = function(response, o) {
        alert(response.message);
        if (response.type == 'insert') {
            var designlist = document.getElementById('gmdesign_id');
            globalmessage.select(designlist).addIfNew(new Option(response.name, response.id));
            globalmessage.select(YAHOO.util.Dom.get('id_design')).addOptionsFromSelect(designlist);
        }
        return true;
    }
    this.validate = function(data) {
        if (data.name == '') {
            alert(globalmessage.string('designerror2'));
            return false;
        }
        if (data.width == '' || data.height == '') {
            alert(globalmessage.string('designerror1'));
            return false;
        }
        return true;
    }
    this.initPreviewButton = function() {
        var previewbutton = new YAHOO.widget.Button("gm-preview-design", {
            type: "checkbox"
        });
        previewbutton.addListener("checkedChange", function(e) {
            if (e.prevValue) {
                document.getElementById('gm-message-design-preview').style.display = 'none';
            } else {
                document.getElementById('gm-message-design-preview').style.display = 'block';
            }
        });
    };
    this.updateDesignPreview = function(element) {
        var message = document.getElementById('gm-message-popup'),
        innermessage = document.getElementById('gm-message-inner');
        switch(element.name) {
            case 'width':
                message.style.width = parseInt(element.value) + 'px';
                break;
            case 'height':
                message.style.height = parseInt(element.value) + 'px';
                break;
            case 'bgcolor':
                message.style.backgroundColor = element.value;
                break;
            case 'bgimage':
                message.style.backgroundImage = 'url('+element.value+')';
                break;
            case 'bgimageposition[top]':
            case 'bgimageposition[left]':
                var bgposition = document.getElementById('id_bgimageposition_left').value + 'px '
                + document.getElementById('id_bgimageposition_top').value + 'px';
                message.style.backgroundPosition = bgposition;
                break;
            case 'bgimagerepeat':
                message.style.backgroundRepeat = element.value;
                break;
            case 'bordersize':
            case 'bordercolor':
            case 'bordershape':
                var border = document.getElementById('id_bordersize').value + 'px '
                + document.getElementById('id_bordershape').value + ' '
                + document.getElementById('id_bordercolor').value;
                message.style.border = border;
                break;
            case 'padding[top]':
            case 'padding[left]':
            case 'padding[right]':
            case 'padding[bottom]':
                var padding = document.getElementById('id_padding_top').value + 'px '
                + document.getElementById('id_padding_right').value + 'px '
                + document.getElementById('id_padding_bottom').value + 'px '
                + document.getElementById('id_padding_left').value + 'px ';
                message.style.padding = padding;
                break;
            case 'innerpadding[top]':
            case 'innerpadding[bottom]':
            case 'innerpadding[right]':
            case 'innerpadding[left]':
                var innerpadding = document.getElementById('id_innerpadding_top').value + 'px '
                + document.getElementById('id_innerpadding_right').value + 'px '
                + document.getElementById('id_innerpadding_bottom').value + 'px '
                + document.getElementById('id_innerpadding_left').value + 'px ';
                innermessage.style.padding = innerpadding;
                break;
        }
    };
    this.initUpdateEvent = function() {
        YAHOO.util.Event.on(elements, 'change', function(e) {
            self.updateDesignPreview(this);
        });
    };
    this.initUpdatePreviewButton  = function() {
        new YAHOO.widget.Button("gm-updatepreview-design", {
            onclick: {
                fn: function() {
                    for (var i = 0; i <elements.length; i++) {
                        self.updateDesignPreview(elements[i]);
                    }
                }
            }
        });
    };
    this.initRemoveDesignButton = function() {
        new YAHOO.widget.Button("gm-remove-design", {
            onclick: {
                fn: function() {
                    var actionYes, confirmDialog;
                    actionYes = function() {
                        var designid = document.getElementById('designid').value, dialog = this;
                        globalmessage.ajax({
                            url: 'index.php?action=index/removedesign&id='+designid,
                            success: function(response, o) {
                                if (response.error == 0) {
                                    var designlist = YAHOO.util.Dom.get('gmdesign_id');
                                    globalmessage.select(designlist).removeOptionWithValue(designid);
                                    globalmessage.select(YAHOO.util.Dom.get('id_design')).addOptionsFromSelect(designlist);
                                    document.getElementById('designid').value = '';
                                    document.getElementById('id_designactiontype').value = 'insert';
                                    dialog.hide();
                                }
                                alert(response.message);
                            }
                        });
                    };
                    confirmDialog = new YAHOO.widget.SimpleDialog("remove-desing-dialog", {
                        width: "330px",
                        fixedcenter: true,
                        visible: false,
                        draggable: false,
                        close: true,
                        zindex:50000,
                        modal: true,
                        text: globalmessage.string('removedesigntext'),
                        icon: YAHOO.widget.SimpleDialog.ICON_HELP,
                        constraintoviewport: true,
                        buttons: [ {
                            text: globalmessage.string('yes'),
                            handler:actionYes,
                            isDefault:true
                        }, {
                            text:globalmessage.string('no'),
                            handler:function() {
                                this.hide();
                            }
                        } ]
                    } );
                    confirmDialog.setHeader(globalmessage.string('confirmtitle'));
                    confirmDialog.render("middle-column");
                    confirmDialog.show();
                }
            }
        });
    };
    this.initLoadDesignEvent = function() {
        YAHOO.util.Event.addListener("gmdesign_id", "change", function(e) {
            var idelement = document.getElementById('designid');
            var message = document.getElementById('gm-message-popup'),
            innermessage = document.getElementById('gm-message-inner'),
            removeButton = document.getElementById('gm-remove-design');
            if (this.value == '') {
                for (var i = 0; i <elements.length; i++) {
                    elements[i].value = '';
                }
                idelement.value = '';
                message.removeAttribute('style');
                innermessage.removeAttribute('style');
                document.getElementById('id_designactiontype').value = 'insert';
                removeButton.style.display = 'none';
                return;
            }
            globalmessage.ajax({
                url: 'index.php?action=index/getdesign&id='+this.value,
                success: function(response, o) {
                    if (typeof(response.error) == 'undefined' || response.error == 1) {
                        return alert(globalmessage.string('failedajax'));
                    }
                    for (var i = 0; i <elements.length; i++) {
                        var endofelement = elements[i].name.indexOf('['), data, elementname;
                        if (endofelement != -1) {
                            elementname = elements[i].name.substr(0, endofelement);
                            data = response.formcontent[elementname];
                        } else {
                            elementname = elements[i].name;
                            data = response.formcontent[elementname];
                        }
                        if (elementname == 'bgimage') {
                            document.getElementById('id_bgimageposition_top').disabled = false;
                            document.getElementById('id_bgimageposition_left').disabled = false;
                            document.getElementById('id_bgimagerepeat').disabled = false;
                        }
                        if ((elementname == 'innerpadding' || elementname == 'padding') && typeof(data) == 'object') {
                            document.getElementById('id_'+elementname+'_top').value = data.top;
                            document.getElementById('id_'+elementname+'_left').value = data.left;
                            document.getElementById('id_'+elementname+'_right').value = data.right;
                            document.getElementById('id_'+elementname+'_bottom').value = data.bottom;
                        } else if (elementname == 'bgimageposition' && typeof(data) == 'object') {
                            document.getElementById('id_bgimageposition_top').value = data.top;
                            document.getElementById('id_bgimageposition_left').value = data.left;
                            elements[i].disabled = false;
                        } else {
                            elements[i].value = data;
                        }
                    }
                    idelement.value = response.formcontent['id'];
                    document.getElementById('id_designactiontype').value = 'update';
                    removeButton.style.display = 'block';
                    return true;
                }
            });
        });
    };
    this.afterRender =  function() {
        this.initPreviewButton();
        this.initUpdatePreviewButton();
        this.initRemoveDesignButton();
        this.initUpdateEvent();
        this.initLoadDesignEvent();
    };
    return this;
};
globalmessage.ruledialog = function() {
    globalmessage.dialog.prototype.constructor.call(this);
    var self = this, table = document.getElementById('gm-rulestable'),
    tbody = table.getElementsByTagName('tbody')[0];
    this.response = false;
    this.elementid  = "gm-rules-dialog";
    this.rowsCount = 0;
    this.saveButtonText = globalmessage.string('save');
    this.handleSuccess = function(response, o) {
        if (typeof response.message != 'undefined') {
            alert(response.message);
        }
        if (response.error == 0) {
            self.dialog.cancel();
        }
        return true;
    }
    this.validate = function(data) {
        if (typeof(data['gmrules[0]']) == 'undefined') {
            alert(globalmessage.string('ruleerror3'));
            return false;
        }
        return true;
    }
    this.updateTitle = function(title) {
        document.getElementById('gm-rules-dialog-title').innerHTML = title;
    };
    this.clearRows = function() {
        var trs = tbody.getElementsByTagName('tr');
        var trslength = trs.length-1;
        for (var j=trslength; j>0; j--) {
            try{
                tbody.removeChild(trs[j]);
            } catch(e) {}
        }
    };
    this.renderRow = function(data, order) {
        var row = document.createElement('tr'), createtd;
        createtd = function(id, content) {
            var td = document.createElement('td');
            td.setAttribute('class', 'cell c'+id);
            td.innerHTML = content;
            return td;
        }
        row.setAttribute('id', 'role-' + order);
        YAHOO.util.Dom.addClass(row, 'rule-row');
        row.appendChild(createtd('0', order+1));
        row.appendChild(createtd('1', data.state));
        row.appendChild(createtd('2', data.leftside));
        row.appendChild(createtd('3', data.operator));
        row.appendChild(createtd('4', data.input));
        var action = '<td class="cell c5"><a href="javascript:;" onclick="globalmessage.removerule('+order+');">Remove</a>'
        + '<input type="hidden" name="gmrules['+(parseInt(order))+']" value="'+data.stateval+'|'+data.leftsideval+'|'+data.operatorval+'|'+data.inputval+'"/>';
        row.appendChild(createtd('5', action));
        return row;
    };
    this.renderRows = function() {
        this.clearRows();
        this.rowsCount = 0;
        if (this.response.rules != false) {
            for (var rule in this.response.rules) {
                var anrule = this.response.rules[rule];
                if (typeof(anrule.id) == 'undefined') {
                    continue;
                }
                var row = this.renderRow({
                    stateval: anrule.construct,
                    leftsideval: anrule.leftside,
                    operatorval: anrule.operator,
                    inputval: anrule.rightside,
                    state: anrule.constructtext,
                    leftside: anrule.leftsidetext,
                    operator: anrule.operatortext,
                    input: anrule.rightsidetext
                }, this.rowsCount);
                tbody.appendChild(row);
                this.rowsCount++;
            }
        }
    };
    this.initConstructs = function() {
        var state = YAHOO.util.Dom.get('rules-state');
        var tempEl = document.createElement('div');
        document.body.appendChild(tempEl);
        tempEl.innerHTML = '<select style="position:absolute;left:-10000000px">' + this.response.constructoptions + '</select>';
        state = globalmessage.select(state).addOptionsFromSelect(tempEl.getElementsByTagName('select')[0]);
        document.body.removeChild(tempEl);
        if (this.rowsCount > 0) {
            state.removeAttribute('disabled');
            globalmessage.select(state).removeIfOption();
        } else {
            state.selectedIndex = 0;
            state.disabled = true;
        }
    };
    this.initAddRuleButton = function() {
        var rulebutton = new YAHOO.widget.Button("gm-add-rule");
        rulebutton.on('click', this.addRule);
    };
    this.setMessage = function(message) {
        document.getElementById('gm-messageid').value = message;
    }
    this.addRule = function() {
        var input = YAHOO.util.Dom.get('rules-input');
        if (input.value == '') {
            return alert(globalmessage.string('ruleerror2'));
        }
        var left = YAHOO.util.Dom.get('rules-left');
        var operator = YAHOO.util.Dom.get('rules-operator');
        var state = YAHOO.util.Dom.get('rules-state');
        var table = YAHOO.util.Dom.get('gm-rulestable');
        var rows = YAHOO.util.Selector.query('.rule-row', 'gm-rulestable').length;

        var row = self.renderRow({
            state: state.options[state.selectedIndex].innerHTML,
            leftside: left.options[left.selectedIndex].innerHTML,
            operator: operator.options[operator.selectedIndex].innerHTML,
            input: input.value,
            stateval: state.value,
            leftsideval: left.value,
            operatorval: operator.value,
            inputval: input.value
        }, rows);

        table.getElementsByTagName('tbody')[0].appendChild(row);
        globalmessage.alternatetablerows('gm-rulestable');
        if (rows == 0) {
            state = document.getElementById('rules-state');
            globalmessage.select(state).removeIfOption();
        }
        input.value = '';
        operator.disabled = false;
        input.disabled = false;
        return true;
    };
    this.initRuleChangeEvent = function() {
        YAHOO.util.Event.addListener("rules-left", "change", function() {
            var operator = YAHOO.util.Dom.get("rules-operator");
            var input = YAHOO.util.Dom.get("rules-input");
            operator.disabled = false;
            input.disabled = false;
            if (this.value.indexOf('code_') == 0) {
                operator.disabled = true;
                input.disabled = true;
                operator.selectedIndex = 0;
                input.value = 'true';
            }
        });
    }
    this.initInputEvent = function() {
        YAHOO.util.Event.addListener("rules-input", "click", function() {
            var leftside = document.getElementById('rules-left').value;
            if (leftside != 'date') {
                return;
            }
            var calendar = new YAHOO.widget.Calendar("calendar","gm-calendar", {
                close:true
            });
            calendar.selectEvent.subscribe(function() {
                if (calendar.getSelectedDates().length > 0) {
                    var selDate = calendar.getSelectedDates()[0];
                    var wStr = calendar.cfg.getProperty("WEEKDAYS_LONG")[selDate.getDay()];
                    var dStr = selDate.getDate();
                    var mStr = calendar.cfg.getProperty("MONTHS_LONG")[selDate.getMonth()];
                    var yStr = selDate.getFullYear();
                    YAHOO.util.Dom.get("rules-input").value = wStr + ", " + dStr + " " + mStr + " " + yStr;
                } else {
                    YAHOO.util.Dom.get("rules-input").value = "";
                }
                calendar.hide();
            });
            calendar.render();
            calendar.show();
        });
    };
    this.afterRender = function() {

    };
    this.show = function() {
        this.dialog.show();
        this.initConstructs();
        if (this.isrendered) {
            this.initAddRuleButton();
            this.initRuleChangeEvent();
            this.initInputEvent();
            this.isrendered = false;
        }
        globalmessage.alternatetablerows('gm-rulestable');
    };
    return this;
};
globalmessage.showrulesform = function(messageid) {
    globalmessage.ajax({
        url: 'index.php?action=index/editrules&id='+messageid,
        success: function(response, o) {
            ruledialog.updateTitle(response.title);
            ruledialog.setMessage(messageid);
            ruledialog.response = response;
            ruledialog.renderRows();
            ruledialog.show();
        }
    });
}
globalmessage.removerule = function(rule) {
    var tr = YAHOO.util.Dom.get('role-'+rule), state = YAHOO.util.Dom.get('rules-state'),
    table = YAHOO.util.Dom.get('gm-rulestable'),
    rows = YAHOO.util.Selector.query('.rule-row', 'gm-rulestable').length;

    if (rule == 0 && rows > 1) {
        return alert(globalmessage.string('ruleerror1'));
    }

    table.getElementsByTagName('tbody')[0].removeChild(tr);
    if (rows == 1) {
        globalmessage.select(state).addIfOption();
    }
    return true;
}

// onload
YAHOO.util.Event.onDOMReady(function() {
    document.body.className += ' yui-skin-sam';

    loading = new YAHOO.widget.Panel("gm-loading", {
        width: "240px",
        fixedcenter: true,
        close: false,
        draggable: false,
        modal: true,
        visible:false
    });
    loading.setHeader(globalmessage.string('loadingtext'));
    loading.setBody("<img src='" + globalmessage.string('loadingimg') + "' width='220px' height='19px'/>");
    loading.render(document.body);

    ruledialog = new globalmessage.ruledialog();
    ruledialog.render();

    messagedialog = new globalmessage.messagedialog();
    messagedialog.render();
    var createbutton = new YAHOO.widget.Button("gm-addnew");
    createbutton.on('click', function() {
        messagedialog.resetFields();
        messagedialog.show();
    });

    var designdialog = new globalmessage.designdialog();
    designdialog.render();
    new YAHOO.widget.Button("gm-design", {
        onclick: {
            fn: function() {
                designdialog.show();
            }
        }
    });
});