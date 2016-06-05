if (typeof(tinymce) != 'undefined') {

    if (tinymce.majorVersion == 4) {
        (function () {
            tinymce.PluginManager.add('affilinet_mce_button', function (editor, url) {
                function _show_image(co) {
                    return co.replace(/\[affilinet_performance_ad([^\]]*)\]/g, function (a, b) {
                        var image = b.split('=');
                        return '<img src="'+ affilinet_mce_variables.image_path + image[1] + '.jpg" class="affilinet_performance_ad"  title="affilinet_performance_ad' + tinymce.DOM.encode(b) + '" />';
                    });
                }

                function _remove_image(co) {
                    function getAttr(s, n) {
                        n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
                        return n ? tinymce.DOM.decode(n[1]) : '';
                    }

                    return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function (a, im) {
                        var cls = getAttr(im, 'class');

                        if (cls.indexOf('affilinet_performance_ad') != -1)
                            return '<p>[' + tinymce.trim(getAttr(im, 'title')) + ']</p>';

                        return a;
                    });
                }

                /**
                 * Placeholders are not editable
                 */
                editor.on('click', function (ed, o) {
                    if (ed.target.className == 'affilinet_performance_ad') {
                        ed.stopImmediatePropagation();
                        editor.windowManager.open({
                            title: affilinet_mce_variables.choose_size,
                            body: [
                                {
                                    type: 'listbox',
                                    name: 'size',
                                    label: 'Size',
                                    'values': [
                                        {text: 'Super Banner (728px x 90px)', value: '728x90'},
                                        {text: 'Medium Rectangle (300px x 250px)', value: '300x250'},
                                        {text: 'Square Button (250px x 250px)', value: '250x250'},
                                        {text: 'Fullsize Banner (468px x 60px)', value: '468x60'},
                                        {text: 'Wide Scyscraper (160px x 600px)', value: '160x600'},
                                        {text: 'Scyscraper (120px x 600px)', value: '120x600'}
                                    ]
                                }
                            ],
                            onsubmit: function (e) {
                                // Insert content when the window form is submitted
                                editor.selection.select(ed.target);
                                editor.selection.setContent('[affilinet_performance_ad size=' + e.data.size + ']');
                                //ed.stopImmediatePropagation();
                            }

                        });

                    }
                });


                editor.on('BeforeSetcontent', function (event) {
                    event.content = _show_image(event.content);
                });


                //replace shortcode as its inserted into editor (which uses the exec command)
                editor.on('ExecCommand', function (event) {
                    if (event.command === 'mceInsertContent') {
                        tinyMCE.activeEditor.setContent(_show_image(tinyMCE.activeEditor.getContent()));
                    }
                });


                //replace the image back to shortcode on save
                editor.on('PostProcess', function (event, o) {
                    event.content = _remove_image(event.content);
                });


                editor.addButton('affilinet_mce_button', {
                    icon: true,
                    image: affilinet_mce_variables.image_path  + 'affilinet_signet_small.png',
                    type: 'menubutton',
                    text: 'Affilinet Performance Ads',
                    menu: [
                        {
                            text: 'Super Banner (728px x 90px)',
                            onclick: function () {
                                editor.insertContent('[affilinet_performance_ad size=728x90]');
                            }
                        },
                        {
                            text: 'Medium Rectangle (300px x 250px)',
                            onclick: function () {
                                editor.insertContent('[affilinet_performance_ad size=300x250]');
                            }
                        }, {
                            text: 'Square Button (250px x 250px)',
                            onclick: function () {
                                editor.insertContent('[affilinet_performance_ad size=250x250]');
                            }
                        }, {
                            text: 'Fullsize Banner (468px x 60px)',
                            onclick: function () {
                                editor.insertContent('[affilinet_performance_ad size=468x60]');
                            }
                        }, {
                            text: 'Wide Scyscraper (160px x 600px)',
                            onclick: function () {
                                editor.insertContent('[affilinet_performance_ad size=160x600]');
                            }
                        }, {
                            text: 'Scyscraper (120px x 600px)',
                            onclick: function () {
                                editor.insertContent('[affilinet_performance_ad size=120x600]');
                            }
                        }
                    ]
                });

            });
        })();
    }
    else if (tinymce.majorVersion == 3) {


        (function () {
            //******* Load plugin specific language pack
            //tinymce.PluginManager.requireLangPack('example');

            tinymce.create('tinymce.plugins.affilinet_mce_button', {

                /**
                 * Creates control instances based in the incomming name. This method is normally not
                 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
                 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
                 * method can be used to create those.
                 *
                 * @param {String} n Name of the control to create.
                 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
                 * @return {tinymce.ui.Control} New control instance or null if no control was created.
                 */
                createControl: function (n, cm) {
                    if (n == 'affilinet_mce_button') {
                        var mlb = cm.createListBox('affilinet_mce_button', {
                            title: 'Affilinet Ads',
                            onselect: function (v) { //Option value as parameter
                                if (v !== '') {
                                    tinyMCE.activeEditor.execCommand('mceInsertContent', false, '[affilinet_performance_ad size=' + v + ']');
                                }
                                this.selectByIndex(-1);

                            }
                        });

                        // Add some values to the list box

                        mlb.add('Super Banner (728px x 90px)', '728x90');
                        mlb.add('Medium Rectangle (300px x 250px)', '300x250');
                        mlb.add('Square Button (250px x 250px)', '250x250');
                        mlb.add('Fullsize Banner (468px x 60px)', '468x60');
                        mlb.add('Wide Scyscraper (160px x 600px)', '160x600');
                        mlb.add('Scyscraper (120px x 600px)', '120x600');


                        // Return the new listbox instance
                        return mlb;
                    }

                    return null;
                },

                getInfo: function () {
                    return {
                        longname: 'Affilinet Shortcode Selector',
                        author: 'Stefan Gotre',
                        authorurl: 'https://teraone.de',
                        infourl: 'https://teraone.de',
                        version: "0.1"
                    };
                }
            });

            // Register plugin
            tinymce.PluginManager.add('affilinet_mce_button', tinymce.plugins.affilinet_mce_button);
        })();


    }
}