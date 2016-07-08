angular.module('trocoWeb')
    //trocoWeb
    // =========================================================================
    // Base controller for login functions
    // =========================================================================
    .controller('CommercialController', ['CONFIG', 'CommercialFactory' , function (CONFIG, CommercialFactory) {

        var vm = this,
            query = {};
        vm.page = 1;
        vm.maxSize = 5;
        vm.limit = 10;

        vm.pageChanged = function(){

            getCommercials();
        }

        vm.onSubmit = function(){

            query.search = vm.search;
            getCommercials();
        }

         vm.update = function(){
            commercial = {};
            commercial.id = 3;
            commercial.name = "Barbara Alvarez";

            //updateCommercials(commercial);
        }

        var getCommercials = function(){
            query.limit = vm.limit;
            query.page = vm.page;
            CommercialFactory.get(query).then(function (res){

                vm.commercials = res.commercials;
                vm.total = res.total;

            }, function(error){

            });
        }

        vm.deleteCommercial = function(commercial, index){

            commercial.deleted = 1;

            console.log(commercial);
            CommercialFactory.update(commercial).then(function (res){
                vm.commercials.splice(index, 1);

            }, function(error){

            });
        }
        getCommercials();
    	
     }])
    .controller('CommercialShowController', ['CONFIG', 'CommercialFactory', '$stateParams', 'ExtensionFactory' , function (CONFIG, CommercialFactory,$stateParams,ExtensionFactory) {

        var vm = this,
            query = {};

        vm.onSubmit = function(){
            query.id = $stateParams.Id;
            query.name = vm.commercial.name;
            query.extensions_id = vm.commercial.extensions.id;
            query.status = vm.commercial.status.id;
            CommercialFactory.update(query).then(function (res){
            console.log(res);
                

            }, function(error){

            });
        }
        var getCommercials = function(){
            CommercialFactory.get({id:$stateParams.Id}).then(function (res){
            console.log(res);
                vm.commercial = res.commercials;
                vm.total = res.total;

            }, function(error){

            });
        }


        var getExtensions = function(){
            ExtensionFactory.get({type:0, page:1, limit:9999, status:1}).then(function (res){

                vm.extensions = res.extensions;
                vm.total = res.total;

            }, function(error){

            });
        }

        getExtensions();
        

        getCommercials();


    }])