// Enhanced mobile experience for registration form
document.addEventListener("DOMContentLoaded", function () {
    // Prevent zoom on iOS when focusing on inputs
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        const viewportMeta = document.querySelector('meta[name="viewport"]');
        if (viewportMeta) {
            viewportMeta.content =
                "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no";
        }
    }

    // Auto-format phone numbers
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener("input", function (e) {
            let value = e.target.value.replace(/\D/g, "");
            if (value.startsWith("0")) {
                if (value.length > 11) {
                    value = value.substring(0, 13);
                }
                // Format: 08xx-xxxx-xxxx
                if (value.length > 4) {
                    value = value.substring(0, 4) + "-" + value.substring(4);
                }
                if (value.length > 9) {
                    value = value.substring(0, 9) + "-" + value.substring(9);
                }
            }
            e.target.value = value;
        });
    }

    // Auto-format KK number
    const kkInput = document.querySelector('input[name="kk_number"]');
    if (kkInput) {
        kkInput.addEventListener("input", function (e) {
            let value = e.target.value.replace(/\D/g, "");
            if (value.length > 16) {
                value = value.substring(0, 16);
            }
            // Format: xxxx-xxxx-xxxx-xxxx
            if (value.length > 4) {
                value = value.substring(0, 4) + "-" + value.substring(4);
            }
            if (value.length > 9) {
                value = value.substring(0, 9) + "-" + value.substring(9);
            }
            if (value.length > 14) {
                value = value.substring(0, 14) + "-" + value.substring(14);
            }
            e.target.value = value;
        });
    }

    // Enhanced form validation feedback
    const form = document.querySelector("#form");
    if (form) {
        const inputs = form.querySelectorAll(
            "input[required], select[required]"
        );

        inputs.forEach((input) => {
            input.addEventListener("blur", function () {
                validateField(this);
            });

            input.addEventListener("input", function () {
                if (this.classList.contains("error")) {
                    validateField(this);
                }
            });
        });
    }

    function validateField(field) {
        const value = field.value.trim();
        const fieldWrapper = field.closest(".fi-fo-field-wrp");

        // Remove existing error classes
        field.classList.remove("error", "success");

        if (field.hasAttribute("required") && !value) {
            field.classList.add("error");
            return false;
        }

        // Email validation
        if (field.type === "email" && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add("error");
                return false;
            }
        }

        // Phone validation
        if (field.name === "phone" && value) {
            const phoneRegex = /^08[0-9]{8,}$/;
            const cleanPhone = value.replace(/\D/g, "");
            if (!phoneRegex.test(cleanPhone)) {
                field.classList.add("error");
                return false;
            }
        }

        // KK number validation
        if (field.name === "kk_number" && value) {
            const cleanKK = value.replace(/\D/g, "");
            if (cleanKK.length !== 16) {
                field.classList.add("error");
                return false;
            }
        }

        field.classList.add("success");
        return true;
    }

    // Progress tracking
    function updateProgress() {
        const wizard = document.querySelector(".fi-wi");
        if (wizard) {
            const steps = wizard.querySelectorAll(".fi-wi-step");
            const activeStep = wizard.querySelector(".fi-wi-step-active");

            if (activeStep && steps.length > 0) {
                const currentIndex = Array.from(steps).indexOf(activeStep);
                const progress = ((currentIndex + 1) / steps.length) * 100;

                // Update any progress bars
                const progressBars = document.querySelectorAll(".progress-bar");
                progressBars.forEach((bar) => {
                    bar.style.width = progress + "%";
                });
            }
        }
    }

    // Call updateProgress on step changes
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.target.classList.contains("fi-wi-step")) {
                updateProgress();
            }
        });
    });

    const wizard = document.querySelector(".fi-wi");
    if (wizard) {
        observer.observe(wizard, {
            attributes: true,
            subtree: true,
            attributeFilter: ["class"],
        });
    }

    // Smooth scroll to top when changing steps
    const wizardSteps = document.querySelectorAll(".fi-wi-step");
    wizardSteps.forEach((step) => {
        step.addEventListener("click", function () {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth",
                });
            }, 100);
        });
    });

    // Enhanced touch support for mobile
    if ("ontouchstart" in window) {
        // Add touch-friendly classes
        document.body.classList.add("touch-device");

        // Improve button interactions
        const buttons = document.querySelectorAll(".fi-btn");
        buttons.forEach((button) => {
            button.addEventListener("touchstart", function () {
                this.classList.add("touched");
            });

            button.addEventListener("touchend", function () {
                setTimeout(() => {
                    this.classList.remove("touched");
                }, 150);
            });
        });
    }

    // Form submission enhancement
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener("click", function (e) {
            // Add loading state
            this.classList.add("loading");
            this.disabled = true;

            // Re-enable if form validation fails
            setTimeout(() => {
                if (document.querySelector(".fi-fo-field-wrp-error-message")) {
                    this.classList.remove("loading");
                    this.disabled = false;
                }
            }, 1000);
        });
    }

    // Auto-capitalize name fields
    const nameInputs = document.querySelectorAll(
        'input[name="name"], input[name="head_of_family"]'
    );
    nameInputs.forEach((input) => {
        input.addEventListener("input", function (e) {
            // Capitalize first letter of each word
            const words = e.target.value.toLowerCase().split(" ");
            const capitalizedWords = words.map((word) => {
                return word.charAt(0).toUpperCase() + word.slice(1);
            });
            e.target.value = capitalizedWords.join(" ");
        });
    });

    // Initialize progress
    updateProgress();
});

// Add CSS classes for enhanced mobile experience
const style = document.createElement("style");
style.textContent = `
    .touch-device .fi-btn:active,
    .touch-device .fi-btn.touched {
        transform: scale(0.98);
        opacity: 0.9;
    }

    .fi-input input.error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }

    .fi-input input.success {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
    }

    .fi-btn.loading {
        position: relative;
        color: transparent !important;
    }

    .fi-btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #ffffff;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 640px) {
        .fi-wi-step-label {
            font-size: 0.75rem !important;
            line-height: 1.2 !important;
        }

        .fi-wi-step {
            padding: 0.5rem !important;
        }
    }
`;
document.head.appendChild(style);
