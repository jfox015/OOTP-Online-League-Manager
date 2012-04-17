    function checkRequired() {
        var form = document.file_list;
        if (required != null) {
            var startIndex = 0;
            for (var i = 0; i < required.length; i++) {
                for (var j = startIndex; j < form.elements.length; j++) {
                    if (i == 0) {
                        //alert(form.elements[j].value);
                        //alert(required[i]);
                    }
                    if (form.elements[j].type == 'checkbox') {
                        var table = form.elements[j].value.split(".");
                        if (table[0] == required[i]) {
                            form.elements[j].checked = true; // END if
                            //startIndex = j;
                        }
                    }
                } // END for
            }
        }
    }
    function setCheckBoxState(state) {
        var form = document.file_list;
        for (var i = 0; i < form.elements.length; i++) {
            if (form.elements[i].type == 'checkbox')
                form.elements[i].checked = state; // END if
        } // END for
    } // END function