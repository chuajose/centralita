angular.module('trocoWeb')
    //trocoWeb
    // =========================================================================
    // Base controller for extension functions
    // =========================================================================
    .controller('CallController', ['CONFIG', 'CallFactory', 'CommercialFactory' , function (CONFIG, CallFactory, CommercialFactory) {

        var vm = this,
            query = {};
        vm.page = 1;
        vm.limit = 30;
        vm.maxSize = 5;

        var endDate = new Date();
        var startDate = new Date();
        startDate.setDate(endDate.getDate() -15);


        console.log(startDate);

        vm.to = endDate;
        vm.from = startDate;

        vm.pageChanged = function(){

            getCalls();
        }

        vm.onSubmit = function(){

            if (vm.from) {
                startDate =vm.from;
            }
            if (vm.to) {
                endDate = vm.to;
            }

            if (vm.commercial_id) {
                query.commercial_id = vm.commercial_id;
            }
            vm.page = 1;
            getCalls();
        }
        
        var getCommercials = function(){
            CommercialFactory.get({limit:999,page:1}).then(function (res){
                vm.commercials = res.commercials;

            }, function(error){

            });
        }


        var getCalls = function(){

            var d =startDate.getTime();
            var f =endDate.getTime();

            query.from = d / 1000;
            query.to = f / 1000;
            query.page = vm.page;
            query.limit = vm.limit;

            console.log(query);
            
            CallFactory.get(query).then(function (res){

                vm.calls = res.calls;
                vm.total = res.total;
                vm.duration = res.duration;
                vm.more5 = res.more5;

            }, function(error){

            });
        }

        getCalls();
        getCommercials();
    	
     }])