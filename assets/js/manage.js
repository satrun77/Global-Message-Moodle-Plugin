M.moo_gm = {};
var messagedialog, ruledialog;
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
        Y.YUI2.util.Dom.get('create-title').style.display = 'block';
        Y.YUI2.util.Dom.get('edit-title').style.display = 'none';
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
        Y.YUI2.util.Dom.get('create-title').style.display = 'none';
        Y.YUI2.util.Dom.get('edit-title').style.display = 'block';
    };
    this.handleSuccess = function(response, o) {
        if (typeof response.error == 'undefined'){
            return alert(globalmessage.string('messageerror1'));
        } else if (typeof response.error != 'undefined' && response.error == 1){
            return alert(response.message);
        }
        var table = Y.YUI2.util.Dom.get('gm-table'),
        row = Y.one('#gm-table .message-'+response.id);

        if (row == null) {
            var tableHTML = '<table id="gm-temp-table" style="position:absolute;left:-10000000px">' + response.rowcontent + '</table>';
            var tempEl = document.createElement('div');
            document.body.appendChild(tempEl);
            tempEl.innerHTML = tableHTML;
            var newRows = tempEl.getElementsByTagName('tr');
            if (Y.one('#gm-table tbody').hasClass('empty')) {
                Y.one('#gm-table tbody').removeClass('empty');
                Y.one('#gm-table tbody').set('innerHTML', '');
            }
            table.getElementsByTagName('tbody')[0].appendChild(newRows[0]);
            document.body.removeChild(tempEl);
        } else {
            var tds = row.all('td');
            tds.item(0).one('a').set('innerHTML', response.data[0]);
            tds.item(1).set('innerHTML', response.data[1]);
            tds.item(3).set('innerHTML', response.data[2]);
        }
        globalmessage.alternatetablerows('gm-table');
        this.dialog.cancel();
        return true;
    }
    this.validate = function(data) {
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
            var dialogdec = Y.one("#gm-create-message-dialog .htmlarea");
            dialogdec.parentNode.removeChild(dialogdec);
            editor_67daf92c833c41c95db874e18fcb2786.generate();
            this.moodleEditorStatus = true;
        }
    }
    this.saveButtonText = globalmessage.string('savemessage');
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
    var actionYes, actionNo,
        row = Y.one('#gm-table .message-'+messageid);
    
    actionYes = function() {
        var dialog = this;
        globalmessage.ajax({
            url: 'index.php?action=index/removemessage&id='+messageid,
            success: function(response, o) {
                if (response.error == 1) {
                    globalmessage.highlightRow(row, false);
                }
                alert(response.message);
                var removerow = Y.one('#gm-table .message-'+messageid);
                removerow.setStyle('display', 'none');
                dialog.hide();
            }
        });
    };
    actionNo = function() {
        this.hide();
        globalmessage.highlightRow(row, false);
    };
    // Instantiate the Dialog
    var confirmDialog = new Y.YUI2.widget.SimpleDialog("remove-message-dialog", {
        width: "330px",
        fixedcenter: true,
        visible: false,
        draggable: false,
        close: true,
        modal: true,
        text: globalmessage.string('removemessagetext'),
        icon: Y.YUI2.widget.SimpleDialog.ICON_HELP,
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
    confirmDialog.render("page-admin-local-globalmessage-index");
    confirmDialog.show();
    globalmessage.highlightRow(row, true);
};
globalmessage.designdialog = function() {
    globalmessage.dialog.prototype.constructor.call(this);
    var elements = Y.all('#gm-design-form input[type=text], select'),
    self = this;
    this.elementid  = "gm-design-message-dialog";
    this.saveButtonText = globalmessage.string('savedesign');
    this.handleSuccess = function(response, o) {
        alert(response.message);
        if (response.type == 'insert') {
            var designlist = document.getElementById('gmdesign_id');
            globalmessage.select(designlist).addIfNew(new Option(response.name, response.id));
            globalmessage.select(Y.YUI2.util.Dom.get('id_design')).addOptionsFromSelect(designlist);
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
        var previewbutton = new Y.YUI2.widget.Button("gm-preview-design", {
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
        switch(element.get('name')) {
            case 'width':
                message.style.width = parseInt(element.get('value')) + 'px';
                break;
            case 'height':
                message.style.height = parseInt(element.get('value')) + 'px';
                break;
            case 'bgcolor':
                message.style.backgroundColor = element.get('value');
                break;
            case 'bgimage':
                if (element.get('value') != '') {
                    message.style.backgroundImage = 'url('+element.get('value')+')';
                }
                break;
            case 'bgimageposition[top]':
            case 'bgimageposition[left]':
                var bgposition = document.getElementById('id_bgimageposition_left').value + 'px '
                + document.getElementById('id_bgimageposition_top').value + 'px';
                message.style.backgroundPosition = bgposition;
                break;
            case 'bgimagerepeat':
                message.style.backgroundRepeat = element.get('value');
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
        elements.on('change', function (e) {
            self.updateDesignPreview(e.target);
        });
    };
    this.initUpdatePreviewButton  = function() {
        new Y.YUI2.widget.Button("gm-updatepreview-design", {
            onclick: {
                fn: function() {
                    elements.each(function (element) {
                        self.updateDesignPreview(element);
                    });
                }
            }
        });
    };
    this.initRemoveDesignButton = function() {
        new Y.YUI2.widget.Button("gm-remove-design", {
            onclick: {
                fn: function() {
                    var actionYes, confirmDialog;
                    actionYes = function() {
                        var designid = document.getElementById('designid').value, dialog = this;
                        globalmessage.ajax({
                            url: 'index.php?action=index/removedesign&id='+designid,
                            success: function(response, o) {
                                if (response.error == 0) {
                                    var designlist = Y.YUI2.util.Dom.get('gmdesign_id');
                                    globalmessage.select(designlist).removeOptionWithValue(designid);
                                    globalmessage.select(Y.YUI2.util.Dom.get('id_design')).addOptionsFromSelect(designlist);
                                    document.getElementById('designid').value = '';
                                    document.getElementById('id_designactiontype').value = 'insert';
                                    dialog.hide();
                                }
                                alert(response.message);
                            }
                        });
                    };
                    confirmDialog = new Y.YUI2.widget.SimpleDialog("remove-desing-dialog", {
                        width: "330px",
                        fixedcenter: true,
                        visible: false,
                        draggable: false,
                        close: true,
                        zindex:50000,
                        modal: true,
                        text: globalmessage.string('removedesigntext'),
                        icon: Y.YUI2.widget.SimpleDialog.ICON_HELP,
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
                    confirmDialog.render("page");
                    confirmDialog.show();
                }
            }
        });
    };
    this.initLoadDesignEvent = function() {
        Y.YUI2.util.Event.addListener("gmdesign_id", "change", function(e) {
            var idelement = document.getElementById('designid');
            var message = document.getElementById('gm-message-popup'),
            innermessage = document.getElementById('gm-message-inner'),
            removeButton = document.getElementById('gm-remove-design');
            if (this.value == '') {
                elements.each(function (element) {
                        element.set('value', '');
                    });
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
                    for (var i = 0; i <elements.size(); i++) {
                        var endofelement = elements.item(i).get('name').indexOf('['), data, elementname;
                        if (endofelement != -1) {
                            elementname = elements.item(i).get('name').substr(0, endofelement);
                            data = response.formcontent[elementname];
                        } else {
                            elementname = elements.item(i).get('name');
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
                            elements.item(i).set('disabled', false);
                        } else {
                            elements.item(i).set('value', data);
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
    var self = this, table = Y.one('#gm-rulestable tbody');
    this.response = false;
    this.elementid  = "gm-rules-dialog";
    this.rowsCount = 0;
    this.saveButtonText = globalmessage.string('saverules');
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
        table.set('innerHTML', '');
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
        Y.YUI2.util.Dom.addClass(row, 'rule-row');
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
                table.append(row);
                this.rowsCount++;
            }
        }
    };
    this.initConstructs = function() {
        var state = Y.YUI2.util.Dom.get('rules-state');
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
        var rulebutton = new Y.YUI2.widget.Button("gm-add-rule");
        rulebutton.on('click', this.addRule);
    };
    this.setMessage = function(message) {
        document.getElementById('gm-messageid').value = message;
    }
    this.addRule = function() {
        var left = Y.YUI2.util.Dom.get('rules-left');
        var operator = Y.YUI2.util.Dom.get('rules-operator');
        var input = Y.YUI2.util.Dom.get('rules-input');
        if (left.value.indexOf('code_') == 0) {
            operator.disabled = true;
            input.disabled = true;
            operator.selectedIndex = 1;
            input.value = 'true';
        }

        if (input.value == '') {
            return alert(globalmessage.string('ruleerror2'));
        }
        var state = Y.YUI2.util.Dom.get('rules-state');
        var rows = Y.all('#gm-rulestable .rule-row').size();

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
        if (table.hasClass('empty')) {
            table.removeClass('empty');
            table.set('innerHTML', '');
        }
        table.append(row);
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
        Y.YUI2.util.Event.addListener("rules-left", "change", function() {
            var operator = Y.YUI2.util.Dom.get("rules-operator");
            var input = Y.YUI2.util.Dom.get("rules-input");
            operator.disabled = false;
            input.disabled = false;
            if (this.value.indexOf('code_') == 0) {
                operator.disabled = true;
                input.disabled = true;
                operator.selectedIndex = 1;
                input.value = 'true';
            }
        });
    }
    this.initInputEvent = function() {
        Y.YUI2.util.Event.addListener("rules-input", "click", function() {
            var leftside = document.getElementById('rules-left').value;
            if (leftside != 'date') {
                return;
            }
            var calendar = new Y.YUI2.widget.Calendar("calendar","gm-calendar", {
                close:true
            });
            calendar.selectEvent.subscribe(function() {
                if (calendar.getSelectedDates().length > 0) {
                    var selDate = calendar.getSelectedDates()[0];
                    var wStr = calendar.cfg.getProperty("WEEKDAYS_LONG")[selDate.getDay()];
                    var dStr = selDate.getDate();
                    var mStr = calendar.cfg.getProperty("MONTHS_LONG")[selDate.getMonth()];
                    var yStr = selDate.getFullYear();
                    Y.YUI2.util.Dom.get("rules-input").value = wStr + ", " + dStr + " " + mStr + " " + yStr;
                } else {
                    Y.YUI2.util.Dom.get("rules-input").value = "";
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
    var tr = Y.one('#role-'+rule), state = Y.YUI2.util.Dom.get('rules-state'),
    table = Y.one('#gm-rulestable tbody'),
    rows = Y.all('#gm-rulestable .rule-row').size();

    if (rule == 0 && rows > 1) {
        return alert(globalmessage.string('ruleerror1'));
    }

    tr.remove();
    if (rows == 1) {
        globalmessage.select(state).addIfOption();
    }
    return true;
}

M.moo_gm.init = function(YUI, strings) {
    Y = YUI;
    document.body.className += ' yui-skin-sam';
    globalmessage.strings = strings;

    loading = new Y.YUI2.widget.Panel("gm-loading", {
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
    var createbutton = new Y.YUI2.widget.Button("gm-addnew");
    createbutton.on('click', function() {
        messagedialog.resetFields();
        messagedialog.show();
    });

    var designdialog = new globalmessage.designdialog();
    designdialog.render();
    
    new Y.YUI2.widget.Button("gm-design", {
        onclick: {
            fn: function() {
                designdialog.show();
            }
        }
    });
};
