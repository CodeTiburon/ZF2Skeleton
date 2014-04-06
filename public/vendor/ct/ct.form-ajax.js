/**
 * Author: Nickolay U. Kofanov
 * Company: CodeTiburon
 * Date: 09.06.2013
 * Version: 1.0
 */
(function($) {
    /**
     * Example of usage:
     *
     *  $('#form').formAjax({
     *      beforeSubmit: function() { ... },
     *      success: function() { ... },
     *      error: function() { ... },
     *      errorElem: {
     *          image: '/images/error-16x16.png'
     *      }
     *      submitButton: '#submit-button',
     *      reset: function(event, ui) {
     *          var $form = $(this);
     *          $form.find(':hidden').val('');
     *      }
     *  });
     *  .....
     *  $('#form').formAjax('clearErrors'); // clear form errors
     *  $('#form').formAjax('option', 'success', function() { ... });  // change form error image
     *  $('#form').formAjax('setError', 'email', 'Invalid email format!');
     *  $('#form').formAjax('reset');  // reset form to inital state
     *  $('#form').formAjax('elem', 'title');  // get element with [name="title"]
     */
    $.widget('ct.formAjax', {

        hasError: false,
        submitButton: null,

        options: {
            dataType: 'json',
            useDefaultJsonHandler: true,
            autoDisable: true,
            autoEnable: true,
            submitButton: null,
            errorElem: { // error-element options
                'show': $.fn.slideDown,
                'hide' : $.fn.slideUp,
                'speed': 300,
                'image': false
            },
            errorGritter: {  // error-gritter options
                sticky: false,
                time: 7000,
                class_name: 'gritter-error',
               'image': false
            },
            reset: null  // reset event callback
        },

        _create: function() {
            var $widget = this;
            if (this.options.submitButton) {
                var $form = this.element;
                this.submitButton = $(this.options.submitButton).click(function(e) {
                    $widget.element.submit();
                    e.preventDefault();
                });
            }
            else {
                this.submitButton = this.element.find(':submit');
            }
            var options = $.extend({}, this.options);

            options.beforeSubmit = function(arr, $form, options) {
                $widget.clearErrors($widget.options.errorElem.speed);
                if ($widget.options.beforeSubmit &&
                    $widget.options.beforeSubmit(arr, $form, options) === false)
                {
                    return false;
                }
                if ($widget.options.autoDisable) {
                    if ($widget.submitButton.hasClass('ui-button')) {
                        $widget.submitButton.button('disable');
                    }
                    else {
                        $widget.submitButton.attr('disabled', 'disabled');
                    }
                }
            };

            options.complete = function(jqXHR, textStatus) {
                if ($widget.options.complete) {
                    $widget.options.complete(jqXHR, textStatus, $widget.element);
                }
                if ($widget.hasError || $widget.options.autoEnable) {
                    if ($widget.submitButton.hasClass('ui-button')) {
                        $widget.submitButton.button('enable');
                    }
                    else {
                        $widget.submitButton.removeAttr('disabled');
                    }
                }
            };

            if ('json' === options.dataType) {
                options.success = function(data, textStatus, jqXHR) {
                    if ($widget.options.useDefaultJsonHandler) {
                        if ( ($widget.hasError = ('errors' in data)) && ('object' === typeof(data.errors)) ) {
                            var errors = data.errors;
                            // --- Handle gritter message ---
                            if (('gritter' in errors) && ('gritter' in jQuery)) {
                                var opts = $widget.options.errorGritter;

                                opts.title = errors.gritter.title || 'Error!';
                                opts.text  = errors.gritter.text  || 'There were some errors...';

                                delete errors.gritter;
                                $.gritter.add(opts);
                            }
                            // --- Handle element messages ---
                            $widget.setErrors(data.errors);
                        }
                        else if ('reloadUrl' in data) {
                            window.location.reload();
                        }
                        else if ('redirectUrl' in data) {
                            window.location = data.redirectUrl;
                        }
                        else if ('replaceWith' in data) {
                            $form.replaceWith($(data.replaceWith));
                        }
                    }
                    if ($widget.options.success)  {
                        $widget.options.success(data, textStatus, jqXHR, $widget.element)
                    }
                }
            }
            else {
                options.success = function(data, textStatus, jqXHR) {
                    if ($widget.options.success) {
                        $widget.options.success(data, textStatus, jqXHR, $widget.element);
                    }
                }
            }

            // ======== AJAX-ERORR HANDLER ========
            options.error = function(jqXHR, textStatus, errorThrown) {
                $widget.hasError = true;
                if (window.console) {
                    window.console.log(errorThrown);
                    window.console.log(jqXHR);
                }
                if ($widget.options.error) {
                    $widget.options.error(jqXHR, textStatus, $form);
                }
            };

            this.element.ajaxForm(options);
        },

        _destroy: function() {

        },

        elem: function(elementName) {
            var elem = this.element[0].elements[elementName];
            return this.element.pushStack( elem ? [elem] : [] );
        },

        setError: function(name, messages, speed) {
            if (!this.hasError) this.hasError = true;
            speed = speed || this.options.errorElem.speed;

            if (typeof(messages) !== 'string') {
                if ($.isPlainObject(messages)) {
                    messages = $.map(messages, function(val) { return val; });
                }
                messages = $.isArray(messages) ? messages.join('</li><li>') : messages.toString();
            }

            var $elem = $(this.element[0].elements[name]),
                $error  = $elem.data('control-error'),
                $parent = $elem.data('control-group'),
                errorList = '<li>' + messages + '</li>';

            if (!$error) {
                $error = '<div class="help-inline control-error" style="display:none">';
                if (this.options.errorElem.image) {
                    $error += '<img src="';
                    $error += this.options.errorElem.image;
                    $error += '" class="error-img" width="16" height="16" alt="!" />&nbsp;';
                }
                $error += '<ul class="error-list">';
                $error += errorList;
                $error += '</ul></div>';

                $error = $($error);
                $parent = $elem.closest('.control-group');
                if (!$parent.length) {
                    $parent = $elem.parent();
                }
                $parent.append($error);

                $elem.data('control-group', $parent);
                $elem.data('control-error', $error);
            }
            else {
                $error.children('ul').html(errorList);
            }

            $parent.addClass('error');
            this.options.errorElem.show.call($error, speed);
        },

        setErrors: function(errors, speed) {
            if (typeof(errors) === 'object') {
                for(var name in errors) {
                    this.setError(name, errors[name], speed);
                }
            }
        },

        clearErrors: function(speed) {
            if (!this.hasError) return;
            var elements = this.element[0].elements;
            for(var i = 0; i < elements.length; ++i) {
                var $element = $(elements[i]);
                var $parent  = $element.data('control-group');
                if ($parent && $parent.hasClass('error')) {
                    $parent.removeClass('error');
                    this.options.errorElem.hide.call($element.data('control-error'), speed);
                }
            }
            this.hasError = false;
        },

        reset: function() {
            this.element.resetForm();
            this.clearErrors();
            this._trigger('reset');
        },

        _setOptions: function() {
            this._super();
        },

        _setOption: function(key, value) {
            this._super(key, value);
        }
    });

})(jQuery);

