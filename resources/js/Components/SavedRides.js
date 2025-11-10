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
        this.hidden(true);

            var closeButton = document.createElement('button');
            closeButton.setAttribute('id', 'saved-rides-close-btn');
            closeButton.innerHTML = 'Close';
            closeButton.addEventListener('click', () => {
                this.hidden(true);
            });

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
                console.log(data);
            }).catch((error) => {
                throw new Error(error);
            });

        this.element.appendChild(closeButton);
        this.element.appendChild(refreshButton);
        this.element.appendChild(savedRideListDiv);
        
    }
}