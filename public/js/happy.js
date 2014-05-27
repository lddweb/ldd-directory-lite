(function happyJS($) {
    function trim(el) {
        return (''.trim) ? el.val().trim() : $.trim(el.val());
    }
    $.fn.isHappy = function isHappy(config) {
        var fields = [], item;
        var pauseMessages = false;

        function isFunction(obj) {
            return !!(obj && obj.constructor && obj.call && obj.apply);
        }
        function defaultError(error) { //Default error template
            var msgErrorClass = config.classes && config.classes.message || 'unhappyMessage';
            return $('<span id="' + error.id + '" class="' + msgErrorClass + '" role="alert">' + error.message + '</span>');
        }
        function getError(error) { //Generate error html from either config or default
            if (isFunction(config.errorTemplate)) {
                return config.errorTemplate(error);
            }
            return defaultError(error);
        }
        function handleSubmit() {
            var  i, l;
            var errors = false;
            for (i = 0, l = fields.length; i < l; i += 1) {
                if (!fields[i].testValid(true)) {
                    errors = true;
                }
            }
            if (errors) {
                if (isFunction(config.unHappy)) config.unHappy();
                return false;
            } else if (config.testMode) {
                if (isFunction(config.happy)) config.happy();
                if (window.console) console.warn('would have submitted');
                return false;
            }
            if (isFunction(config.happy)) config.happy();
        }
        function handleMouseUp() {
            pauseMessages = false;
        }
        function handleMouseDown() {
            pauseMessages = true;
            $(window).bind('mouseup', handleMouseUp);
        }
        function processField(opts, selector) {
            var field = $(selector);
            var error = {
                message: opts.message || '',
                id: selector.slice(1) + '_unhappy'
            };
            var errorEl = $(error.id).length > 0 ? $(error.id) : getError(error);
            var handleBlur = function handleBlur() {
                if (!pauseMessages) {
                    field.testValid();
                } else {
                    $(window).bind('mouseup', field.testValid.bind(this));
                }
            };

            fields.push(field);
            field.testValid = function testValid(submit) {
                var val, gotFunc, temp;
                var el = $(this);
                var error = false;
                var required = !!el.get(0).attributes.getNamedItem('required') || opts.required;
                var password = (field.attr('type') === 'password');
                var arg = isFunction(opts.arg) ? opts.arg() : opts.arg;
                var fieldErrorClass = config.classes && config.classes.field || 'unhappy';

                // clean it or trim it
                if (isFunction(opts.clean)) {
                    val = opts.clean(el.val());
                } else if (!password && typeof opts.trim === 'undefined' || opts.trim) {
                    val = trim(el);
                } else {
                    val = el.val();
                }

                // write it back to the field
                el.val(val);

                // get the value
                gotFunc = ((val.length > 0 || required === 'sometimes') && isFunction(opts.test));

                // check if we've got an error on our hands
                if (submit === true && required === true && val.length === 0) {
                    error = true;
                } else if (gotFunc) {
                    error = !opts.test(val, arg);
                }

                if (error) {
                    el.addClass(fieldErrorClass)
                    el.closest('div').after(errorEl)
                    return false;
                } else {
                    temp = errorEl.get(0);
                    // this is for zepto
                    if (temp.parentNode) {
                        temp.parentNode.removeChild(temp);
                    }
                    el.removeClass(fieldErrorClass);
                    return true;
                }
            };
            field.bind(opts.when || config.when || 'blur', handleBlur);
        }

        for (item in config.fields) {
            processField(config.fields[item], item);
        }

        $(config.submitButton || this).bind('mousedown', handleMouseDown);

        if (config.submitButton) {
            $(config.submitButton).click(handleSubmit);
        } else {
            this.bind('submit', handleSubmit);
        }
        return this;
    };
})(this.jQuery);

var happy = {
    USPhone: function (val) {
        return /^\(?(\d{3})\)?[\- ]?\d{3}[\- ]?\d{4}$/.test(val);
    },

    // matches mm/dd/yyyy (requires leading 0's (which may be a bit silly, what do you think?)
    date: function (val) {
        return /^(?:0[1-9]|1[0-2])\/(?:0[1-9]|[12][0-9]|3[01])\/(?:\d{4})/.test(val);
    },

    email: function (val) {
        return /^(?:\w+\.?\+?)*\w+@(?:\w+\.)+\w+$/.test(val);
    },

    minLength: function (val, length) {
        return val.length >= length;
    },

    maxLength: function (val, length) {
        return val.length <= length;
    },

    equal: function (val1, val2) {
        return (val1 == val2);
    },

    math: function( val ) {
        console.log( "Val is " + val );
        return ( val == 14 );
    }
};