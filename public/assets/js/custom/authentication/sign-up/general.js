"use strict";

var KTSignupGeneral = function () {
    var e, t, a, s;

    var isStrongPassword = function () {
        return s.getScore() === 100;
    };

    return {
        init: function () {
            e = document.querySelector("#kt_sign_up_form");
            t = document.querySelector("#kt_sign_up_submit");

            s = KTPasswordMeter.getInstance(
                e.querySelector('[data-kt-password-meter="true"]')
            );

            a = FormValidation.formValidation(e, {
                fields: {
                    "name": {
                        validators: {
                            notEmpty: {
                                message: "Name is required"
                            }
                        }
                    },

                    email: {
                        validators: {
                            notEmpty: {
                                message: "Email address is required"
                            },
                            emailAddress: {
                                message: "The value is not a valid email address"
                            }
                        }
                    },

                    password: {
                        validators: {
                            notEmpty: {
                                message: "The password is required"
                            },
                            callback: {
                                message: "Please enter valid password",
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return isStrongPassword();
                                    }
                                }
                            }
                        }
                    },

                    "password_confirmation": {
                        validators: {
                            notEmpty: {
                                message: "The password confirmation is required"
                            },
                            identical: {
                                compare: function () {
                                    return e.querySelector('[name="password"]').value;
                                },
                                message: "The password and its confirm are not the same"
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }
                    }),

                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            });

            t.addEventListener("click", function (r) {
                r.preventDefault();

                a.revalidateField("password");

                a.validate().then(function (status) {
                    if (status === "Valid") {
                        t.setAttribute("data-kt-indicator", "on");
                        t.disabled = true;

                        setTimeout(function () {
                            t.removeAttribute("data-kt-indicator");
                            t.disabled = false;

                            // Submit form after validation
                            e.submit();
                        }, 500);
                    } else {
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            });

            // Reset password validation on input
            e.querySelector('input[name="password"]').addEventListener("input", function () {
                if (this.value.length > 0) {
                    a.updateFieldStatus("password", "NotValidated");
                }
            });
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
