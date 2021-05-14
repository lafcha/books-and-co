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
        let selectElements = document.querySelectorAll('.county-form');
        for (const selectElement of selectElements) {
            
            for(let countyData of countyListing){
                // get the name and code of a county
                let countyName = countyData.nom;
                let countyCode = countyData.code;
                
                // initialize the option element with data
                const optionElement = document.createElement("option");
                optionElement.value = countyCode;
        

                if (countyName.length > 14){
                    optionElement.textContent = countyCode + " - " + countyName.substr(0,14) + "…";
                } else {
                    optionElement.textContent = countyCode + " - " + countyName ;
                }
                
                countyAndCityApp.addSelectedElement(selectElement, optionElement); // implement the option element in the county select
            }
            if (selectElement.dataset.county != null) {
                countyValue = selectElement.dataset.county;
                if (countyValue.length == 1) {
                    countyValue = 0 + countyValue;
                }
                countyAndCityApp.selectDatasetElement(countyValue, selectElement);
                city.getAllSelectElementsByCounty(countyValue);
            }
        }
    },

    addAllEventListeners: function(){
        document.querySelector(".county-form").addEventListener('change', city.handleChangeCounty)
    },
}
