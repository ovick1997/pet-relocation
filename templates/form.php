<?php
/*
 * Pet Relocation Form - Form Template
 * License: GPL-2.0+
 * Author: Md Shorov Abedin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="pr-form">
    <div class="pr-step-container">
        <div class="pr-step-item active" id="step-indicator-1">
            <h3>Step-1</h3>
            <p><span class="pr-mobile-none">Pet</span> Information</p>
        </div>
        <div class="pr-step-item" id="step-indicator-2">
            <h3>Step-2</h3>
            <p>Location <span class="pr-mobile-none">Information</span></p>
        </div>
        <div class="pr-step-item" id="step-indicator-3">
            <h3>Step-3</h3>
            <p><span class="pr-mobile-none">Additional</span> Service</p>
        </div>
    </div>

    <form id="pet-relocation-form" enctype="multipart/form-data">
        <!-- Step 1: Pet Information -->
        <div class="pr-form-step active" id="step-1">
            <div class="pr-form-group">
                <label class="pr-form-label" for="number_of_pets">Number of Pet *</label>
                <select name="number_of_pets" id="number_of_pets" class="pr-form-control pfc-w" required>
                    <option value="">Select number of pets</option>
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
           
            <div class="pr-pet-info">
            <h4 class="pr-pet-info-title">Pet Information 01</h4>
               
                
              
                <div class="pr-form-row">
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="pet_type_0">Pet Type *</label>
                            <select name="pets[0][pet_type]" id="pet_type_0" class="pr-form-control " required>
                                <option value="">Select pet type</option>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                                <option value="Bird">Bird</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="breed_0">Breed *</label>
                            <select name="pets[0][breed]" id="breed_0" class="pr-form-control" required>
                                <option value="">Select breed</option>
                                <option value="Labrador">Labrador</option>
                                <option value="Persian">Persian</option>
                                <option value="Parrot">Parrot</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="age_0">Age *</label>
                            <select name="pets[0][age]" id="age_0" class="pr-form-control" required>
                                <option value="">Select age</option>
                                <option value="0-1 year">0-1 year</option>
                                <option value="1-3 years">1-3 years</option>
                                <option value="3-7 years">3-7 years</option>
                                <option value="7+ years">7+ years</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="weight_0">Weight *</label>
                            <select name="pets[0][weight]" id="weight_0" class="pr-form-control" required>
                                <option value="">Select weight</option>
                                <option value="0-5 kg">0-5 kg</option>
                                <option value="5-10 kg">5-10 kg</option>
                                <option value="10-20 kg">10-20 kg</option>
                                <option value="20+ kg">20+ kg</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                        <label class="pr-form-label" for="spaying_status_0">Spaying/Neutering Status *</label>
                        <select name="pets[0][spaying_status]" id="spaying_status_0" class="pr-form-control" required>
                            <option value="">Select status</option>
                            <option value="Spayed">Spayed</option>
                            <option value="Neutered">Neutered</option>
                            <option value="Not Spayed/Neutered">Not Spayed/Neutered</option>
                        </select>
                        </div>
                    </div>
                    <div class="pr-form-col">   
                        <div class="pr-form-group">
                        <label class="pr-form-label" for="vaccination_report_0">Vaccination Report *</label>
                        <select name="pets[0][vaccination_report]" id="vaccination_report_0" class="pr-form-control" required>
                            <option value="">Select status</option>
                            <option value="Up to date">Up to date</option>
                            <option value="Not up to date">Not up to date</option>
                        </select>
                    </div>
                    </div>
                </div>
                
            
                
           
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="health_condition_0">Health Condition</label>
                    <input type="text" name="pets[0][health_condition]" id="health_condition_0" class="pr-form-control" placeholder="Any health issues?">
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="specific_medicine_0">Any Specific Medicine</label>
                    <input type="text" name="pets[0][specific_medicine]" id="specific_medicine_0" class="pr-form-control" placeholder="Any specific medicine?">
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="behaviour_training_0">Behaviour Training</label>
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
                <label class="pr-form-label">Relocation Type *</label>
                <div class="pr-radio-group">
                    <label class="pr-radio-label">
                        <input type="radio" name="relocation_type" value="Domestic" class="pr-radio-input" required> Domestic
                    </label>
                    <label class="pr-radio-label">
                        <input type="radio" name="relocation_type" value="International" class="pr-radio-input"> International
                    </label>
                </div>
            </div>
            
            <div class="pr-location-section">
                <h4 class="pr-location-title">Departure Location</h4>
                <div class="pr-form-group">
                    <label class="pr-form-label" for="departure_address_line">Address Line *</label>
                    <input type="text" name="departure_address_line" id="departure_address_line" class="pr-form-control" placeholder="Enter address line" required>
                </div>
                
                <div class="pr-form-row">
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="departure_city">City *</label>
                            <select name="departure_city" id="departure_city" class="pr-form-control" required>
                                <option value="">Select city</option>
                                <option value="New York">New York</option>
                                <option value="Los Angeles">Los Angeles</option>
                                <option value="London">London</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="departure_country">Country *</label>
                            <select name="departure_country" id="departure_country" class="pr-form-control" required>
                                <option value="">Select country</option>
                                <option value="USA">USA</option>
                                <option value="UK">UK</option>
                                <option value="Canada">Canada</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="travel_date">Travel Date *</label>
                    <input type="date" name="travel_date" id="travel_date" class="pr-form-control" required>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="same_flight">Are You Travelling in The Same Flight? *</label>
                    <select name="same_flight" id="same_flight" class="pr-form-control" required>
                        <option value="">Select option</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="flight_information">Your Flight Information</label>
                    <input type="text" name="flight_information" id="flight_information" class="pr-form-control" placeholder="Enter flight details">
                </div>
            </div>
            
            <div class="pr-location-section">
                <h4 class="pr-location-title">Arrival Location</h4>
                <div class="pr-form-group">
                    <label class="pr-form-label" for="arrival_address_line">Address Line *</label>
                    <input type="text" name="arrival_address_line" id="arrival_address_line" class="pr-form-control" placeholder="Enter address line" required>
                </div>
                
                <div class="pr-form-row">
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="arrival_city">City *</label>
                            <select name="arrival_city" id="arrival_city" class="pr-form-control" required>
                                <option value="">Select city</option>
                                <option value="New York">New York</option>
                                <option value="Los Angeles">Los Angeles</option>
                                <option value="London">London</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-form-col">
                        <div class="pr-form-group">
                            <label class="pr-form-label" for="arrival_country">Country *</label>
                            <select name="arrival_country" id="arrival_country" class="pr-form-control" required>
                                <option value="">Select country</option>
                                <option value="USA">USA</option>
                                <option value="UK">UK</option>
                                <option value="Canada">Canada</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="pr-form-group">
                    <label class="pr-form-label" for="emergency_contact">Emergency Contact Arrival Country (Optional)</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" class="pr-form-control" placeholder="Enter emergency contact">
                </div>
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
                        <input type="radio" name="health_certificate" value="Issued" class="pr-radio-input" required> Issued
                    </label>
                    <label class="pr-radio-label">
                        <input type="radio" name="health_certificate" value="Pending" class="pr-radio-input"> Pending
                    </label>
                </div>
            </div>
            <div class="pr-form-group">
                <label class="pr-form-label">Pet Grooming Required Before Relocation? *</label>
                <div class="pr-radio-group">
                    <label class="pr-radio-label">
                        <input type="radio" name="grooming_required" value="Yes" class="pr-radio-input" required> Yes
                    </label>
                    <label class="pr-radio-label">
                        <input type="radio" name="grooming_required" value="No" class="pr-radio-input"> No
                    </label>
                </div>
            </div>
            <div class="pr-additonal-service-section">
                <div class="pr-form-group">
                    <label class="pr-form-label" for="post_arrival_support">Post Arrival Support</label>
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
                        <label class="pr-form-label" for="is_microchipped">Is your pet microchipped? *</label>
                        <select name="is_microchipped" id="is_microchipped" class="pr-form-control" required>
                            <option value="">Select option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="pr-form-col">
                    <div class="pr-form-group">
                        <label class="pr-form-label" for="vaccination_status">Is your pet up to date with vaccinations? *</label>
                        <select name="vaccination_status" id="vaccination_status" class="pr-form-control" required>
                            <option value="">Select status</option>
                            <option value="Up to date">Up to date</option>
                            <option value="Not up to date">Not up to date</option>
                        </select>
                    </div>
                </div>
                <div class="pr-form-col">
                    <div class="pr-form-group">
                        <label class="pr-form-label" for="health_issues">Does your pet have any health issues?</label>
                        <input type="text" name="health_issues" id="health_issues" class="pr-form-control" placeholder="Describe any health issues">
                    </div>
                </div>
                <div class="pr-form-col">
                    <div class="pr-form-group">
                        <label class="pr-form-label" for="iata_crate">Do you have an IATA approved travel crate? *</label>
                        <select name="iata_crate" id="iata_crate" class="pr-form-control" required>
                            <option value="">Select option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
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