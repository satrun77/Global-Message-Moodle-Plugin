M.moo_gm = {};
globalmessage.installcustomrule = function(ruleid) {
    var row = Y.one('#gm-table .customrule-'+ruleid);
    globalmessage.highlightRow(row, true);
    globalmessage.ajax({
        url: 'index.php?action=about/installcustomrule&rule='+ruleid,
        success: function(response, o) {
            globalmessage.highlightRow(row, false);
            var a = row.getElementsByTagName('td')[2].getElementsByTagName('a')[0];
            a.innerHTML = globalmessage.string('uninstall');
            a.setAttribute('onclick', 'globalmessage.removecustomrule("'+ruleid+'");');
            alert(response.message);
        }
    });
};
globalmessage.removecustomrule = function(ruleid) {
    var actionYes, actionNo;
    var row = Y.one('#gm-table .customrule-'+ruleid);
    globalmessage.highlightRow(row, true);
    actionYes = function() {
        var dialog = this;
        globalmessage.ajax({
            url: 'index.php?action=about/removecustomrule&rule='+ruleid,
            success: function(response, o) {
                if (response.error == 0) {
                    var a = row.getElementsByTagName('td')[2].getElementsByTagName('a')[0];
                    a.innerHTML = globalmessage.string('install');
                    a.setAttribute('onclick', 'globalmessage.installcustomrule("'+ruleid+'");');
                    dialog.hide();
                }
                alert(response.message);
                globalmessage.highlightRow(row, false);
            }
        });
    };
    actionNo = function() {
        this.hide();
        globalmessage.highlightRow(row, false);
    };
    // Instantiate the Dialog
    var confirmDialog = new Y.YUI2.widget.SimpleDialog("remove-customrule-dialog", {
        width: "330px",
        fixedcenter: true,
        visible: false,
        draggable: false,
        close: true,
        modal: true,
        text: globalmessage.string('removecustomruletext'),
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
M.moo_gm.init = function(Y) {
    document.body.className += ' yui-skin-sam';

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

    YUI({
        filter: 'raw'
    }).use('tabview', function(Y) {
        var tabview = new Y.TabView({
            srcNode: '#gm-about-tab'
        });
        tabview.render();
    });
};