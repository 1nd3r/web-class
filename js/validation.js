/**
 * SparkNest Services - Form Validation
 * 
 * This file handles all the form validation for our cleaning services website.
 * We're checking emails, dates, names, and other form data to ensure
 * users enter valid information before submitting forms.
 * 
 * The main things we validate:
 * - Email addresses (proper format)
 * - Service dates (no past dates)
 * - Names (letters, spaces, hyphens, etc.)
 * - Phone numbers
 * - Required fields for bookings and contact forms
 */

/*
 * Helper Functions
 * These are the basic functions that other validation functions use
 */

// Show an error message under a form field
function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorContainer = document.getElementById(fieldId + 'Error') || createErrorContainer(fieldId);
    
    // Make the field look like it has an error (red border)
    field.classList.add('error');
    field.classList.remove('valid');
    
    // Show the error message to the user
    errorContainer.textContent = message;
    errorContainer.style.display = 'block';
}

// Clear the error message and styling from a field
function clearError(fieldId) {
    const field = document.getElementById(fieldId);
    const errorContainer = document.getElementById(fieldId + 'Error');
    
    // Make the field look good again (green border)
    field.classList.remove('error');
    field.classList.add('valid');
    
    // Hide the error message
    if (errorContainer) {
        errorContainer.style.display = 'none';
    }
}

// If there's no error container for a field, we need to create one
function createErrorContainer(fieldId) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.createElement('div');
    errorDiv.id = fieldId + 'Error';
    errorDiv.className = 'error';
    field.parentNode.appendChild(errorDiv); // stick it right after the input field
    return errorDiv;
}

/*
 * Email Validation
 * This checks if an email address looks legit
 */

// The main email validation function - returns true if email is good
function validateEmail(email) {
    // This pattern checks for proper email format
    const emailPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    
    // First, check if they actually entered something
    if (!email || email.trim() === '') {
        return false;
    }
    
    // Email addresses can't be super long
    if (email.length > 254) {
        return false;
    }
    
    // Finally, check if it matches our email pattern
    return emailPattern.test(email);
}

// This function validates an email field and shows error messages if needed
function validateEmailField(fieldId) {
    const emailField = document.getElementById(fieldId);
    const email = emailField.value.trim(); // remove extra spaces
    
    // Check if they left it empty
    if (email === '') {
        showError(fieldId, 'Email address is required.');
        return false;
    }
    
    // Check if the email format is valid
    if (!validateEmail(email)) {
        showError(fieldId, 'Please enter a valid email address (e.g., user@example.com).');
        return false;
    }
    
    // If we get here, the email is good!
    clearError(fieldId);
    return true;
}

/*
 * Date Validation
 * This makes sure service dates make sense for cleaning services
 */

// Check that service date is not in the past
function validateServiceDate(fieldId) {
    const dateField = document.getElementById(fieldId);
    const selectedDate = new Date(dateField.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // set to midnight so we can compare just the date
    
    if (!dateField.value) {
        showError(fieldId, 'Service date is required.');
        return false;
    }
    
    if (selectedDate < today) {
        showError(fieldId, 'Service date cannot be in the past.');
        return false;
    }
    
    clearError(fieldId);
    return true;
}

/*
 * Name Validation
 * This checks that names look like real human names
 */

// The main name validation function - checks if a name looks legit
function validateName(name) {
    // This pattern allows letters (including international ones), spaces, hyphens, apostrophes, and periods
    const namePattern = /^[a-zA-ZÀ-ÿ\u0100-\u017F\u0180-\u024F\u1E00-\u1EFF\s\-'.]+$/;
    
    // First check if they entered anything at all
    if (!name || name.trim() === '') {
        return false;
    }
    
    // Clean up the name and check if it's reasonable length
    const trimmedName = name.trim();
    if (trimmedName.length < 2) {
        return false; // names need to be at least 2 characters
    }
    
    if (trimmedName.length > 50) {
        return false; // probably not a real name if it's super long
    }
    
    // Check if it only contains valid characters
    if (!namePattern.test(trimmedName)) {
        return false; // contains numbers or weird symbols
    }
    
    // Don't allow double spaces or double special characters
    if (/\s{2,}/.test(trimmedName) || /[-'.]{2,}/.test(trimmedName)) {
        return false;
    }
    
    // Names shouldn't start or end with special characters
    if (/^[-'.\s]|[-'.\s]$/.test(trimmedName)) {
        return false;
    }
    
    return true; // if we get here, the name looks good!
}

/**
 * Validate name field and display appropriate message
 * @param {string} fieldId - The ID of the name field
 * @param {string} fieldName - Display name of the field (e.g., "First Name")
 * @returns {boolean} - True if valid, false otherwise
 */
function validateNameField(fieldId, fieldName) {
    const nameField = document.getElementById(fieldId);
    const name = nameField.value.trim();
    
    if (name === '') {
        showError(fieldId, fieldName + ' is required.');
        return false;
    }
    
    if (name.length < 2) {
        showError(fieldId, fieldName + ' must be at least 2 characters long.');
        return false;
    }
    
    if (name.length > 50) {
        showError(fieldId, fieldName + ' must be less than 50 characters.');
        return false;
    }
    
    if (!validateName(name)) {
        showError(fieldId, fieldName + ' can only contain letters, spaces, hyphens, apostrophes, and periods.');
        return false;
    }
    
    clearError(fieldId);
    return true;
}

/*
 * Phone Number Validation
 */

/**
 * Validate phone number format
 * @param {string} fieldId - The ID of the phone field
 * @returns {boolean} - True if valid, false otherwise
 */
function validatePhoneField(fieldId) {
    const phoneField = document.getElementById(fieldId);
    const phone = phoneField.value.trim();
    
    // Phone is required for bookings
    if (phone === '') {
        showError(fieldId, 'Phone number is required.');
        return false;
    }
    
    // Basic phone pattern (supports various formats)
    const phonePattern = /^[\+]?[1-9][\d]{0,15}$|^[\+]?[(]?[\d\s\-\(\)]{10,20}$/;
    
    if (!phonePattern.test(phone.replace(/[\s\-\(\)]/g, ''))) {
        showError(fieldId, 'Please enter a valid phone number.');
        return false;
    }
    
    clearError(fieldId);
    return true;
}

/*
 * Address Validation
 */

/**
 * Validate address field
 * @param {string} fieldId - The ID of the address field
 * @returns {boolean} - True if valid, false otherwise
 */
function validateAddressField(fieldId) {
    const addressField = document.getElementById(fieldId);
    const address = addressField.value.trim();
    
    if (address === '') {
        showError(fieldId, 'Address is required.');
        return false;
    }
    
    if (address.length < 10) {
        showError(fieldId, 'Please provide a complete address (at least 10 characters).');
        return false;
    }
    
    if (address.length > 500) {
        showError(fieldId, 'Address is too long. Please provide a shorter address.');
        return false;
    }
    
    clearError(fieldId);
    return true;
}

// FORM VALIDATION FUNCTIONS

/**
 * Validate the booking form
 * @returns {boolean} - True if all fields are valid, false otherwise
 */
function validateBookingForm() {
    let isValid = true;
    
    // Validate personal information
    if (!validateNameField('firstName', 'First Name')) isValid = false;
    if (!validateNameField('lastName', 'Last Name')) isValid = false;
    if (!validateEmailField('email')) isValid = false;
    if (!validatePhoneField('phone')) isValid = false;
    if (!validateAddressField('address')) isValid = false;
    
    // Validate service details
    if (!validateServiceDate('preferredDate')) isValid = false;
    if (!validateRequiredField('serviceType', 'Service type')) isValid = false;
    
    return isValid;
}

/**
 * Validate the contact form
 * @returns {boolean} - True if all fields are valid, false otherwise
 */
function validateContactForm() {
    let isValid = true;
    
    // Validate personal information
    if (!validateNameField('contactFirstName', 'First Name')) isValid = false;
    if (!validateNameField('contactLastName', 'Last Name')) isValid = false;
    if (!validateEmailField('contactEmail')) isValid = false;
    if (!validatePhoneField('contactPhone')) isValid = false;
    
    // Validate required fields
    if (!validateRequiredField('contactSubject', 'Subject')) isValid = false;
    if (!validateTextField('contactMessage', 'Message', 10, 1000)) isValid = false;
    
    return isValid;
}

/**
 * Validate required dropdown/select fields
 * @param {string} fieldId - The ID of the field
 * @param {string} fieldName - Display name of the field
 * @returns {boolean} - True if valid, false otherwise
 */
function validateRequiredField(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    const value = field.value.trim();
    
    if (value === '' || value === null) {
        showError(fieldId, fieldName + ' is required.');
        return false;
    }
    
    clearError(fieldId);
    return true;
}

/**
 * Validate text area fields
 * @param {string} fieldId - The ID of the field
 * @param {string} fieldName - Display name of the field
 * @param {number} minLength - Minimum required length
 * @param {number} maxLength - Maximum allowed length
 * @returns {boolean} - True if valid, false otherwise
 */
function validateTextField(fieldId, fieldName, minLength = 0, maxLength = 500) {
    const field = document.getElementById(fieldId);
    const value = field.value.trim();
    
    if (value === '') {
        showError(fieldId, fieldName + ' is required.');
        return false;
    }
    
    if (value.length < minLength) {
        showError(fieldId, fieldName + ' must be at least ' + minLength + ' characters long.');
        return false;
    }
    
    if (value.length > maxLength) {
        showError(fieldId, fieldName + ' must be less than ' + maxLength + ' characters.');
        return false;
    }
    
    clearError(fieldId);
    return true;
}

// REAL-TIME VALIDATION SETUP

/*
 * Form Setup
 * This gets everything ready when the page loads
 */

// Main setup function that runs when the page is ready
function setupFormValidation() {
    // Wait for the page to fully load before trying to find form elements
    document.addEventListener('DOMContentLoaded', function() {
        
        // Set up the main booking form if it exists on this page
        const bookingForm = document.getElementById('bookingForm');
        if (bookingForm) {
            setupBookingFormListeners(); // add all the validation listeners
        }
        
        // Set up the contact form if it exists on this page
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            setupContactFormListeners(); // add validation listeners
        }
        
        // Set up the quick booking form (the one on the homepage)
        setupQuickBookingValidation();
    });
}

/**
 * Setup validation listeners for booking form
 */
function setupBookingFormListeners() {
    const form = document.getElementById('bookingForm');
    
    // Real-time validation on blur (when user leaves field)
    addBlurValidation('firstName', () => validateNameField('firstName', 'First Name'));
    addBlurValidation('lastName', () => validateNameField('lastName', 'Last Name'));
    addBlurValidation('email', () => validateEmailField('email'));
    addBlurValidation('phone', () => validatePhoneField('phone'));
    addBlurValidation('address', () => validateAddressField('address'));
    addBlurValidation('preferredDate', () => validateServiceDate('preferredDate'));
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateBookingForm()) {
            submitBookingForm();
        } else {
            showFormError('Please correct the errors above before submitting.');
        }
    });
}

/**
 * Setup validation listeners for contact form
 */
function setupContactFormListeners() {
    const form = document.getElementById('contactForm');
    
    // Real-time validation on blur
    addBlurValidation('contactFirstName', () => validateNameField('contactFirstName', 'First Name'));
    addBlurValidation('contactLastName', () => validateNameField('contactLastName', 'Last Name'));
    addBlurValidation('contactEmail', () => validateEmailField('contactEmail'));
    addBlurValidation('contactPhone', () => validatePhoneField('contactPhone'));
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateContactForm()) {
            submitContactForm();
        } else {
            showFormError('Please correct the errors above before submitting.');
        }
    });
}

/**
 * Setup validation for quick booking form on homepage
 */
function setupQuickBookingValidation() {
    const quickForm = document.querySelector('.quick-booking form');
    if (quickForm) {
        quickForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateQuickBookingForm()) {
                submitQuickBookingForm();
            }
        });
    }
}

/**
 * Add blur validation to a field
 * @param {string} fieldId - The ID of the field
 * @param {Function} validationFunction - The validation function to call
 */
function addBlurValidation(fieldId, validationFunction) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.addEventListener('blur', validationFunction);
        field.addEventListener('input', function() {
            // Clear error styling while typing
            field.classList.remove('error');
        });
    }
}

/**
 * Validate quick booking form
 * @returns {boolean} - True if valid, false otherwise
 */
function validateQuickBookingForm() {
    let isValid = true;
    
    // Validate name field
    if (!validateNameField('quickName', 'Name')) isValid = false;
    
    // Validate email field
    if (!validateEmailField('quickEmail')) isValid = false;
    
    // Validate service type (basic text validation)
    const serviceField = document.getElementById('quickProject');
    if (serviceField && serviceField.value.trim() === '') {
        showError('quickProject', 'Please tell us about your service needs.');
        isValid = false;
    } else if (serviceField) {
        clearError('quickProject');
    }
    
    return isValid;
}

/**
 * Submit booking form to server
 */
function submitBookingForm() {
    const form = document.getElementById('bookingForm');
    const formData = new FormData(form);
    formData.append('form_type', 'booking');
    
    showSuccessMessage('Processing your booking request...');
    
    fetch('contact-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            form.reset();
        } else {
            showFormError(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showFormError('An error occurred. Please try again.');
    });
}

/**
 * Submit contact form to server
 */
function submitContactForm() {
    const form = document.getElementById('contactForm');
    const formData = new FormData(form);
    formData.append('form_type', 'contact');
    
    showSuccessMessage('Sending your message...');
    
    fetch('contact-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            form.reset();
        } else {
            showFormError(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showFormError('An error occurred. Please try again.');
    });
}

/**
 * Submit quick booking form to server
 */
function submitQuickBookingForm() {
    const form = document.querySelector('.quick-booking form');
    const formData = new FormData(form);
    formData.append('form_type', 'quick_contact');
    
    showSuccessMessage('Processing your request...');
    
    fetch('contact-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            form.reset();
        } else {
            showFormError(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showFormError('An error occurred. Please try again.');
    });
}

/**
 * Show success message
 * @param {string} message - Success message to display
 */
function showSuccessMessage(message) {
    // Create or update success message container
    let successDiv = document.getElementById('form-success');
    if (!successDiv) {
        successDiv = document.createElement('div');
        successDiv.id = 'form-success';
        successDiv.className = 'success-message';
        document.querySelector('main').insertBefore(successDiv, document.querySelector('main').firstChild);
    }
    
    successDiv.textContent = message;
    successDiv.style.display = 'block';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        successDiv.style.display = 'none';
    }, 5000);
}

/**
 * Show form error message
 * @param {string} message - Error message to display
 */
function showFormError(message) {
    // Create or update error message container
    let errorDiv = document.getElementById('form-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'form-error';
        errorDiv.className = 'form-error-message';
        document.querySelector('main').insertBefore(errorDiv, document.querySelector('main').firstChild);
    }
    
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    
    // Auto-hide after 7 seconds
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 7000);
}

/**
 * Quick contact validation for homepage sidebar form
 * This validates the "Book Your Cleaning Service" form
 */
function quickContactValidation() {
    let isValid = true;
    
    // Validate name field
    const nameValid = validateNameField('quickName', 'Name');
    if (!nameValid) isValid = false;
    
    // Validate email field
    const emailValid = validateEmailField('quickEmail');
    if (!emailValid) isValid = false;
    
    // Validate service type (basic text validation)
    const serviceField = document.getElementById('quickProject');
    if (serviceField && serviceField.value.trim() === '') {
        showError('quickProject', 'Please tell us about your service needs.');
        isValid = false;
    } else if (serviceField) {
        clearError('quickProject');
    }
    
    // If all validation passes, submit the form
    if (isValid) {
        submitQuickBookingForm();
    }
    
    return isValid;
}

/*
 * Start Everything Up!
 * This runs the main setup function when the script loads
 */

// Let's get this validation party started!
setupFormValidation(); // Commit 10 - Create service booking form
// Commit 20 - Setup PHP backend for form processing
