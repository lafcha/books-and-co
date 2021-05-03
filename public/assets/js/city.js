const city = {
    handleChangeCounty: function(evt){
        let countyValue = evt.target.value;
        if (((countyValue > 0 && countyValue < 96) || (countyValue > 970 && countyValue < 975) || (countyValue == 976))) {
            city.getAllSelectElementsByCounty(countyValue);
        }
    },

    getAllSelectElementsByCounty: function(countyCode){
        // Desired url for the api request
        const url = 'https://geo.api.gouv.fr/departements/' + countyCode + '/communes?fields=nom,centre,population&format=json&geometry=centre';

        //Send the request
        fetch(url)
        .then(countyAndCityApp.convertFromJson) // Converts json response in object
        .then(city.displayAllSelectElements); // Display all county in the county select
    },

    displayAllSelectElements: function(cityListing){
        let selectElement = document.querySelector('#registration_form_city');
        //Sort all cities by popluation DESC
        cityListing.sort(function (b, a) {
            return a.population - b.population;
        });

        // We empty the content of the select
        selectElement.textContent = "";

        // We reduce the number of cities to 20
        cityListing = cityListing.slice(0, 20);
        for(let cityData of cityListing){
            let cityName = cityData.nom;
            
            // initialize the option element with data
            const optionElement = document.createElement("option");
            optionElement.value = cityName;
            optionElement.textContent = cityName;
            
            countyAndCityApp.addSelectedElement(selectElement, optionElement); // implement the option element in the county select
        }
    },
}
