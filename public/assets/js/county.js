const county = {
    getAllSelectElements: function(){
        // Desired url for the api request
        const url = 'https://geo.api.gouv.fr/departements?fields=nom,code';

        //Send the request
        fetch(url)
          .then(countyAndCityApp.convertFromJson) // Converts json response in object
          .then(county.displayAllSelectElements); // Display all county in the county select
    },

    displayAllSelectElements: function(countyListing){
        // get the select county select element
        let selectElement = document.querySelector('.county-form');

        for(let countyData of countyListing){
            // get the name and code of a county
            let countyName = countyData.nom;
            let countyCode = countyData.code;

            // initialize the option element with data
            const optionElement = document.createElement("option");
            optionElement.value = countyCode;
            optionElement.textContent = countyCode + " - " + countyName;

            countyAndCityApp.addSelectedElement(selectElement, optionElement); // implement the option element in the county select
        }
        if (document.querySelector('.county-form').dataset.county != null) {
            countyValue = document.querySelector('.county-form').dataset.county;
            if (countyValue.length == 1) {
                countyValue = 0 + countyValue;
            }
            countyAndCityApp.selectDatasetElement(countyValue, selectElement);
            city.getAllSelectElementsByCounty(countyValue);
        }
    },

    addAllEventListeners: function(){
        document.querySelector(".county-form").addEventListener('change', city.handleChangeCounty)
    },
}
