jQuery(document).ready(function($) {
    let currentStep = 1;
    const totalSteps = 3;
    let petIndex = 0;
    
    function showStep(step) {
        $('.pr-form-step').css('display', 'none');
        $(`#step-${step}`).css('display', 'block').fadeIn(300);
        updateStepIndicators(step);
        window.scrollTo(0, 0);
        console.log(`Showing step ${step}`);
    }
    
    function updateStepIndicators(currentStep) {
        $('.pr-step-item').removeClass('active');
        $(`#step-indicator-${currentStep}`).addClass('active');
    }
    
    $('.btn-next').click(function() {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        } else {
            showValidationErrors(currentStep);
        }
    });
    
    $('.btn-prev').click(function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });
    
    function validateStep(step) {
        let isValid = true;
        const requiredFields = $(`#step-${step} [required]`);
        
        requiredFields.each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('error');
            } else {
                $(this).removeClass('error');
            }
        });
        
        const radioGroups = $(`#step-${step} input[type="radio"][required]`).get()
            .map(radio => radio.name)
            .filter((value, index, self) => self.indexOf(value) === index);
            
        radioGroups.forEach(name => {
            if (!$(`input[name="${name}"]:checked`).length) {
                isValid = false;
                $(`input[name="${name}"]`).first().parents('.pr-form-group').addClass('error');
            } else {
                $(`input[name="${name}"]`).first().parents('.pr-form-group').removeClass('error');
            }
        });
        
        return isValid;
    }
    
    function showValidationErrors(step) {
        const firstError = $(`#step-${step} .error`).first();
        if (firstError.length) {
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }
    }
    
    $('#pet-relocation-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateStep(currentStep)) {
            showValidationErrors(currentStep);
            return;
        }
        
        const formData = new FormData(this);
        formData.append('action', 'submit_pet_relocation');
        formData.append('nonce', petRelocation.nonce);
        
        // Log form data for debugging
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        $('.pr-btn[type="submit"]').prop('disabled', true).text('Submitting...');
        
        $.ajax({
            url: petRelocation.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('AJAX Success Response:', response);
                if (response.success) {
                    $('.pr-form-step').css('display', 'none');
                    $('.pr-step-container').hide();
                    $('.pr-success').fadeIn(300);
                    window.scrollTo(0, 0);
                    // Auto-reload after 5 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                } else {
                    alert('Error submitting form. Please try again. Server message: ' + (response.data || 'Unknown error'));
                    $('.pr-btn[type="submit"]').prop('disabled', false).text('Submit');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('AJAX Error:', textStatus, errorThrown);
                alert('Error submitting form. Please try again. AJAX error: ' + textStatus);
                $('.pr-btn[type="submit"]').prop('disabled', false).text('Submit');
            }
        });
    });
    
    $('.pr-add-pet').click(function(e) {
        e.preventDefault();
        const petCount = parseInt($('#number_of_pets').val()) || 1;
        if (petCount >= 10) {
            alert('Maximum 10 pets allowed.');
            return;
        }
        
        petIndex++;
        $('#number_of_pets').val(petCount + 1);
        
        const newTextBox = `
            <div class="pr-form-group additional-info-box" data-pet-index="${petIndex}">
                <label class="pr-form-label" for="additional_info_${petIndex}">Additional Information for Pet ${String(petCount + 1).padStart(2, '0')}</label>
                <input type="text" name="pets[${petIndex}][additional_info]" id="additional_info_${petIndex}" class="pr-form-control" placeholder="Enter additional details for Pet ${String(petCount + 1).padStart(2, '0')}">
            </div>
        `;
        $('#additional-info-container').append(newTextBox);
    });
    
    $('.view-details').click(function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_request_details',
                id: id,
                nonce: '<?php echo wp_create_nonce("get_request_details"); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#request-details').html(response.data);
                    $('#request-details-modal').fadeIn(300);
                }
            }
        });
    });
    
    $('.close').click(function() {
        $('#request-details-modal').fadeOut(300);
    });
    
    $(window).click(function(e) {
        if ($(e.target).hasClass('modal')) {
            $('#request-details-modal').fadeOut(300);
        }
    });
    
    showStep(1);
    
    $(document).on('input change', '.pr-form-control, input[type="radio"]', function() {
        $(this).removeClass('error');
        $(this).parents('.pr-form-group').removeClass('error');
    });
});