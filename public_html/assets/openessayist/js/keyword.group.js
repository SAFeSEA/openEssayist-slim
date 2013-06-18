/**
 * 
 */
var myClass = function(){};

/**
 * 
 */
myClass.prototype = {
    some_property: null,
    some_other_property: 0,

    doSomething: function(msg) {
        this.some_property = msg;
        alert(this.some_property);
    }
};