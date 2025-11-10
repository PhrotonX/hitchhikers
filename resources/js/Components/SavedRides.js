import Component from '../../js/Components/Component.js';

export default class SavedRides extends Component{
    constructor(id, appUrl){
        super(id, appUrl);
    }

    /**
     * Build the HTML elements for saved rides.
     * This function must be called only once.
     */
    onBuildChild(){
        this.element = document.getElementById(this.id);
        // this.hidden(true);

            var closeButton = document.createElement('button');
            closeButton.setAttribute('id', 'saved-rides-close-btn');
            closeButton.innerHTML = 'Close';
            closeButton.addEventListener('click', () => {
                this.hidden(true);
            });

            var heading = document.createElement('h2');
            heading.innerText = "Saved Rides";

            var refreshButton = document.createElement('button');
            refreshButton.setAttribute('id', 'saved-rides-refresh-btn');
            refreshButton.innerHTML = 'Refresh';
            refreshButton.addEventListener('click', () => {
                
            });

            var savedRideListDiv = document.createElement('div');
            savedRideListDiv.setAttribute("id", "saved-ride-list");
            
            // @TODO: Show all folders first
            // ...
            // Then, show all uncategorized rides
            fetch(this.appUrl + '/saved-rides/folders/0/rides')
            .then((response) => {
                return response.json();
            }).then((data) => {
                // console.log(data);

                for(let i = 0; i < Object.keys(data.saved_rides).length; i++){
                    // console.log(item);

                    //@TODO: Must be clickable to display a more details dialog, displaying more details regarding the ride then
                    // displays all ride markers and then
                    // pans to map to the ride locations automatically.
                    //@TODO: Add "Delete" and "Edit" buttons
                    
                    let itemDiv = document.createElement('div');
                    itemDiv.setAttribute('id', 'saved-ride-item-' + data.saved_rides[i].id);
                    itemDiv.setAttribute('data-ride-id', data.rides[i].id);

                        let itemParagraph = document.createElement('p');

                        itemParagraph.innerText = data.rides[i].ride_name;

                    itemDiv.appendChild(itemParagraph);
                    savedRideListDiv.appendChild(itemDiv);
                }
            }).catch((error) => {
                throw new Error(error);
            });

        this.element.appendChild(closeButton);
        this.element.appendChild(heading);
        this.element.appendChild(refreshButton);
        this.element.appendChild(savedRideListDiv);
        
    }
}