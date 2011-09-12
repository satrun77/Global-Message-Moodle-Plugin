M.moo_gm = {};

M.moo_gm.init = function(Y) {
    YUI({ filter: 'raw' }).use('tabview', function(Y) {
        var tabview = new Y.TabView({srcNode: '#gm-about-tab'});
        tabview.render();
    });
};