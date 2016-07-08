/**
 * INSPINIA - Responsive Admin Theme
 *
 * Main controller.js file
 * Define controllers with data used in Inspinia theme
 *
 *
 * Functions (controllers)
 *  - MainCtrl
 *
 *
 */

/**
 * MainCtrl - controller
 * Contains several global data used in different view
 *
 */
function MainCtrl($rootScope, CONFIG,CallFactory) {
	var vm = this;
	vm.loadingCalls = false;
    this.logout = function () {
        localStorage.clear();
        document.location.href = CONFIG.BASEURL + 'es/logout';
    };

    vm.reloadCalls = function(){
    	vm.loadingCalls = true;
    
    	CallFactory.reload({}).then(function (res) {
            console.log(res);
            vm.loadingCalls = false;
        }, function (error) {
        });
    }

};





/**
 *
 * Pass all functions into module
 */
angular
    .module('trocoWeb')
    .controller('MainCtrl', MainCtrl)
  ;

