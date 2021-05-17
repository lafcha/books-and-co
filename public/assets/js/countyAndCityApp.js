// This app is used to generate counties and cities from api for a form
const countyAndCityApp = {

    init: function() {
        county.getAllSelectElements();// Get all select element from API
        countyAndCityApp.addAllEventListeners();
    },
    
    // Converts json response in object
    convertFromJson(response){
        return response.json();
    },
    
    selectDatasetElement: function(valueToSelect, select){
        for (const option of select) {
            if (option.value == valueToSelect) {
                option.selected = 'selected';
            }
        }
    },

    addAllEventListeners: function(){
        county.addAllEventListeners();
        document.querySelector(".submit").addEventListener('click', function(evt) {
            let errors = 0;
            const countyElement = document.querySelector(".county-form")
            const cityElement = document.querySelector(".city-form")
            countyValue = Number(countyElement.value);
            if (cityElement != null) {
                cityValue = cityElement.value;
            }

            // If the countyValue is not Valid, add an error message
            if (!((countyValue > 0 && countyValue < 96) || (countyValue > 970 && countyValue < 975) || (countyValue == 976))) {
                document.querySelector("#county-error").textContent = 'Numéro de département non valide';
                errors++;
            } else {
                document.querySelector("#county-error").textContent = '';
                // if the county is valid, we're doing validations on city ( is city in api)
                const url = 'https://geo.api.gouv.fr/departements/' + countyElement.value + '/communes?fields=nom,centre,population&format=json&geometry=centre';
                //Send the request
                fetch(url)
                .then(countyAndCityApp.convertFromJson) // Converts json response in object
                .then(function(countyListing){
                    for (const countyData of countyListing) {
                        if (countyData.nom === cityValue) {
                            return true;
                        }
                    }
                    if (document.querySelector("#city-error") != null) {
                        document.querySelector("#city-error").textContent = 'Ville non valide';
                        errors++;
                    }
                })
                .then(function(result){
                    console.log(result);
                    if (errors > 0) {
                        evt.preventDefault();
                    }
                })
            }
        })
    },

    addSelectedElement : function(selectElement, optionElement){
        // display the option element at the end of the select
        selectElement.append(optionElement);
    },
};

document.addEventListener('DOMContentLoaded', countyAndCityApp.init);