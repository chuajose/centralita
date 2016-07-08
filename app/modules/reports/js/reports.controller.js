angular.module('trocoWeb')
    //trocoWeb
    // =========================================================================
    // Base controller for extension functions
    // =========================================================================
    .controller('ReportsController', ['CONFIG', 'ReportsFactory', 'CommercialFactory' , function (CONFIG, ReportsFactory, CommercialFactory) {

        var vm = this,
            endDate = false,
            startDate = false,
            query = {};
        vm.loading = false;
        vm.url="";

        vm.onSubmit = function(){
            if (vm.commercial_id) {
                query.commercial_id = vm.commercial_id
            }

            if (vm.from) {
                var d =vm.from.getTime();
                var startDate = d / 1000;
                query.from = startDate;
               
            }
            if (vm.to) {
                var d =vm.to.getTime();
                var endDate = d / 1000;
                query.to = endDate;
                
            }

            getReports();

            
        }

        vm.onSubmitAdd = function(){
            vm.loading = true;

            var report = {};
            //vm.url = CONFIG.BASEURL+"reports/index";
            if (vm.commercial_id) {
               // vm.url = vm.url + "?commercial_id="+vm.commercial_id;
                report.commercial_id = vm.commercial_id
            }

            if (vm.from) {

                var d =vm.from.getTime();
                var startDate = d / 1000;
                report.from = startDate;

                //if(vm.commercial_id) vm.url = vm.url+"&from="+startDate;
                //else vm.url = vm.url+"?from="+startDate;
            }
            if (vm.to) {
                var d =vm.to.getTime();
                var endDate = d / 1000;
                report.to = endDate;

                //if(vm.commercial_id || startDate) vm.url = vm.url+"&to="+endDate;
                //else vm.url = vm.url+"?to="+endaDate;
            }

            //vm.submit = true;


            ReportsFactory.post(report).then(function (res){
                
                getReports();
                vm.loading = false;


            }, function(error){

            });

            
         //   vm.url = CONFIG.BASEURL+"reports/index?commercial_id="+vm.commercial_id+"&to="+endDate+"&from="+startDate;

        }
        
        var getCommercials = function(){
            CommercialFactory.get(query).then(function (res){

                vm.commercials = res.commercials;

            }, function(error){

            });
        }


        var getReports = function(){
            query.page = vm.page;
            query.limit = vm.limit;
            ReportsFactory.get(query).then(function (res){
                console.log(res);
                vm.reports = res.reports;
                vm.total = res.total;

            }, function(error){

            });
        }


        getReports();
        getCommercials();
    	
     }])