export class MapBuilder{
    constructor(){
        this.center = null;
        this.nominatimUrl = null;
        this.mapId = null;
        this.zoom = 13;
    }

    build(){
        
    }

    setCenter(center){
        this.center = center;
    }

    setMapId(id){
        this.mapId = id;
    }

    setNominatimUrl(url){
        this.nominatimUrl = url;
    }

    setZoom(zoom){
        this.zoom = zoom;
    }

}