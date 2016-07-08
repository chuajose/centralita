angular.module('trocoWeb')
    //trocoWeb
    // =========================================================================
    // Base controller for extension functions
    // =========================================================================
    .controller('ExtensionController', ['CONFIG', 'ExtensionFactory' , function (CONFIG, ExtensionFactory) {

        var vm = this,
            query = {};

        vm.page =1;
        vm.limit = 20;
        vm.maxSize = 5;

        vm.pageChanged = function(){

            getExtensions();
        }

        vm.onSubmit = function(){

            ExtensionFactory.get({search:vm.search}).then(function (res){

                vm.extensions = res.extensions;
                vm.total = res.total;

            }, function(error){

            });
        }

        var getExtensions = function(){
            query.page = vm.page;
            query.limit = vm.limit;
            console.log(query);
            ExtensionFactory.get(query).then(function (res){

                vm.extensions = res.extensions;
                vm.total = res.total;

            }, function(error){

            });
        }

        vm.unasigned = function(extension, index){

            ExtensionFactory.update({id:extension.id, unasigned:1}).then(function (res){

                vm.extensions[index]= res.extension;

            }, function(error){

            });
        }

        vm.changeStatus = function(extension, status, index){

            ExtensionFactory.update({id:extension.id, status:status}).then(function (res){

                vm.extensions[index]= res.extension;

            }, function(error){

            });
        }

        getExtensions();
    	
     }])