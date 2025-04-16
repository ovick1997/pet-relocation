<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="pr-form">
    <div class="pr-step-container">
        <div class="pr-step-item active" id="step-indicator-1">
            <h3>Step 1</h3>
            <p>Pet Information</p>
        </div>
        <div class="pr-step-item" id="step-indicator-2">
            <h3>Step 2</h3>
            <p>Location & Travel</p>
        </div>
        <div class="pr-step-item" id="step-indicator-3">
            <h3>Step 3</h3>
            <p>Additional Service</p>
        </div>
    </div>

    <form id="pet-relocation-form" enctype="multipart/form-data">
        <!-- Step 1: Pet Information -->
        <div class="pr-form-step active" id="step-1">
            <div class="pr-pet-info">
                <h4 class="pr-pet-info-title">Pet Information 01</h4>
                <input type="hidden" name="number_of_pets" id="number_of_pets" value="1">
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="pet_type_0">Pet Type *</label>
                    <select name="pets[0][pet_type]" id="pet_type_0" class="pr-form-control" required>
                        <option value="">Select pet type</option>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                        <option value="Bird">Bird</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="breed_0">Breed *</label>
                    <input type="text" name="pets[0][breed]" id="breed_0" class="pr-form-control" placeholder="Enter breed" required>
                </div>
                
                <div class="pr-form-row">
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="age_0">Age *</label>
                            <input type="text" name="pets[0][age]" id="age_0" class="pr-form-control" placeholder="e.g. 2 years" required>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="weight_0">Weight *</label>
                            <input type="text" name="pets[0][weight]" id="weight_0" class="pr-form-control" placeholder="e.g. 10 kg" required>
                        </div>
                    </div>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="spaying_status_0">Spaying/Neutering Status *</label>
                    <select name="pets[0][spaying_status]" id="spaying_status_0" class="pr-form-control" required>
                        <option value="">Select status</option>
                        <option value="Spayed">Spayed</option>
                        <option value="Neutered">Neutered</option>
                        <option value="Not Spayed/Neutered">Not Spayed/Neutered</option>
                    </select>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="vaccination_report_0">Vaccination Report *</label>
                    <select name="pets[0][vaccination_report]" id="vaccination_report_0" class="pr-form-control" required>
                        <option value="">Select status</option>
                        <option value="Up to date">Up to date</option>
                        <option value="Not up to date">Not up to date</option>
                    </select>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="health_condition_0">Health Condition</label>
                    <input type="text" name="pets[0][health_condition]" id="health_condition_0" class="pr-form-control" placeholder="Any health issues?">
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="specific_medicine_0">Specific Medicine</label>
                    <input type="text" name="pets[0][specific_medicine]" id="specific_medicine_0" class="pr-form-control" placeholder="Any specific medicine?">
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="behaviour_training_0">Behaviour/Training</label>
                    <input type="text" name="pets[0][behaviour_training]" id="behaviour_training_0" class="pr-form-control" placeholder="Any behaviour/training notes?">
                </div>
                
                <div id="additional-info-container"></div>
                
                <a href="#" class="pr-add-pet">Add Another Pet Information</a>
            </div>
            
            <div class="pr-navigation">
                <div></div>
                <button type="button" class="pr-btn pr-btn-primary btn-next">Next</button>
            </div>
        </div>

        <!-- Step 2: Location & Travel -->
        <div class="pr-form-step" id="step-2">
            <div class="pr-form-group">
                <label class="pr-form-label" for="relocation_type">Relocation Type *</label>
                <select name="relocation_type" id="relocation_type" class="pr-form-control" required>
                    <option value="">Select relocation type</option>
                    <option value="Domestic">Domestic</option>
                    <option value="International">International</option>
                </select>
            </div>
            
            <div class="pr-location-section">
                <h4 class="pr-location-title">Departure Location</h4>
                <div class="pr-form-group">
                    <label class="pr-form-label" for="departure_address">Address *</label>
                    <input type="text" name="departure_address" id="departure_address" class="pr-form-control" placeholder="Enter departure address" required>
                </div>
                
                <div class="pr-form-row">
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="departure_city">City *</label>
                            <input type="text" name="departure_city" id="departure_city" class="pr-form-control" placeholder="Enter city" required>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="departure_country">Country *</label>
                            <input type="text" name="departure_country" id="departure_country" class="pr-form-control" placeholder="Enter country" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pr-location-section">
                <h4 class="pr-location-title">Arrival Location</h4>
                <div class="pr-form-group">
                    <label class="pr-form-label" for="arrival_address">Address *</label>
                    <input type="text" name="arrival_address" id="arrival_address" class="pr-form-control" placeholder="Enter arrival address" required>
                </div>
                
                <div class="pr-form-row">
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="arrival_city">City *</label>
                            <input type="text" name="arrival_city" id="arrival_city" class="pr-form-control" placeholder="Enter city" required>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="arrival_country">Country *</label>
                            <input type="text" name="arrival_country" id="arrival_country" class="pr-form-control" placeholder="Enter country" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pr-form-group">
                <label class="pr-form-label" for="travel_date">Travel Date *</label>
                <input type="date" name="travel_date" id="travel_date" class="pr-form-control" required>
            </div>
            
            <div class="pr-form-group">
                <label class="pr-form-label">Traveling on Same Flight? *</label>
                <div class="pr-radio-group">
                    <label class="pr-radio-label">
                        <input type="radio" name="same_flight" value="Yes" class="pr-radio-input" required> Yes
                    </label>
                    <label class="pr-radio-label">
                        <input type="radio" name="same_flight" value="No" class="pr-radio-input"> No
                    </label>
                </div>
            </div>
            
            <div class="pr-form-group">
                <label class="pr-form-label" for="flight_info">Flight Information</label>
                <input type="text" name="flight_info" id="flight_info" class="pr-form-control" placeholder="Enter flight details">
            </div>
            
            <div class="pr-form-group">
                <label class="pr-form-label" for="emergency_contact">Emergency Contact</label>
                <input type="text" name="emergency_contact" id="emergency_contact" class="pr-form-control" placeholder="Enter emergency contact">
            </div>
            
            <div class="pr-navigation">
                <button type="button" class="pr-btn pr-btn-secondary btn-prev">Previous</button>
                <button type="button" class="pr-btn pr-btn-primary btn-next">Next</button>
            </div>
        </div>

        <!-- Step 3: Additional Service -->
        <div class="pr-form-step" id="step-3">
            <div class="pr-form-group">
                <label class="pr-form-label">Health Certificate *</label>
                <div class="pr-radio-group">
                    <label class="pr-radio-label">
                        <input type="radio" name="health_certificate" value="Yes" class="pr-radio-input" required> Yes
                    </label>
                    <label class="pr-radio-label">
                        <input type="radio" name="health_certificate" value="No" class="pr-radio-input"> No
                    </label>
                </div>
            </div>
            
            <div class="pr-form-group">
                <label class="pr-form-label">Grooming Required *</label>
                <div class="pr-radio-group">
                    <label class="pr-radio-label">
                        <input type="radio" name="grooming_required" value="Yes" class="pr-radio-input" required> Yes
                    </label>
                    <label class="pr-radio-label">
                        <input type="radio" name="grooming_required" value="No" class="pr-radio-input"> No
                    </label>
                </div>
            </div>
            
            <div class="pr-form-group">
                <label class="pr-form-label" for="post_arrival_support">Post-Arrival Support</label>
                <select name="post_arrival_support" id="post_arrival_support" class="pr-form-control">
                    <option value="">Select support</option>
                    <option value="Airport pickup">Airport pickup</option>
                    <option value="Customs clearance">Customs clearance</option>
                    <option value="Temporary boarding">Temporary boarding</option>
                </select>
            </div>
            
            <div class="pr-form-row">
                <div class="pr-form-col">
                    <div class="pr-form-group">
                        <label class="pr-form-label" for="is_microchipped">Microchipped *</label>
                        <select name="is_microchipped" id="is_microchipped" class="pr-form-control" required>
                            <option value="">Select option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="pr-form-col">
                    <div class="pr-form-group">
                        <label class="pr-form-label" for="vaccination_status">Vaccination Status *</label>
                        <select name="vaccination_status" id="vaccination_status" class="pr-form-control" required>
                            <option value="">Select status</option>
                            <option value="Up to date">Up to date</option>
                            <option value="Not up to date">Not up to date</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="pr-form-row">
                <div class="pr-form-col">
                    <div class="pr-form-group">
                        <label class="pr-form-label" for="health_issues">Health Issues</label>
                        <select name="health_issues" id="health_issues" class="pr-form-control">
                            <option value="">Select option</option>
                            <option value="Allergies">Allergies</option>
                            <option value="Chronic conditions">Chronic conditions</option>
                            <option value="None">None</option>
                        </select>
                    </div>
                </div>
                <div class="pr-form-col">
                    <div class="pr-form-group">
                        <label class="pr-form-label" for="iata_crate">IATA Crate *</label>
                        <select name="iata_crate" id="iata_crate" class="pr-form-control" required>
                            <option value="">Select option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="pr-navigation">
                <button type="button" class="pr-btn pr-btn-secondary btn-prev">Previous</button>
                <button type="submit" class="pr-btn pr-btn-primary">Submit</button>
            </div>
        </div>
    </form>

    <!-- Success Message -->
    <div class="pr-success">
        <div class="pr-success-icon">✔</div>
        <h3 class="pr-success-title">Successfully Booked</h3>
        <p class="pr-success-message">Thank you for your submission! We'll get back to you soon.</p>
    </div>
</div>