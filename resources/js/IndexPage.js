import SavedRides from '../js/Components/SavedRides.js';

export default class IndexPage{
    constructor(appUrl){
        this.appUrl = appUrl;
        this.savedRides = null;
    }

    loadAuthObjects(ids){
        var savedRides = new SavedRides(ids.saved_rides, this.appUrl);
    }

    onUpdateReview(reviewId){
        fetch(this.appUrl + '/reviews/' + reviewId + '/update', {
            method: "PUT",
            body: JSON.stringify({
                description: document.getElementById('review-form-description').value,
                rating: parseInt(document.getElementById('review-form-rating').value)
            }),
            headers: {
                "Content-type": "application/json",
                "Accept": "application/json",
                "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
            },
        }).then((response) => {
            return response.json();
        }).then((data) => {
            console.log(data);
        }).catch((error) => {
            throw new Error(error);
        });
    }

    onDeleteReview(reviewId){
        fetch(this.appUrl + '/reviews/' + reviewId + '/delete', {
            method: "DELETE",
            headers: {
                "Content-type": "application/json",
                "Accept": "application/json",
                "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
            },
        }).then((response) => {
            return response.json();
        }).then((data) => {
            console.log(data);
        }).catch((error) => {
            throw new Error(error);
        });
    }
}