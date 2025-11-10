export default class Component{
    constructor(id, appUrl){
        this.id = id;
        this.appUrl = appUrl;
        this.element = null;

        this.onBuildChild();
    }

    /**
     * Specifies whether the element must be hidden.
     * @param {Boolean} value 
     */
    hidden(value){
        this.element.hidden = value;
    }

    onBuildChild(){}
}