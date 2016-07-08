angular.module('trocoWeb')
    //trocoWeb
    // =========================================================================
    // Base controller for login functions
    // =========================================================================
    .controller('LoginController', ['CONFIG', 'authFactory', 'jwtHelper', 'store', '$state', '$location', '$rootScope', function (CONFIG, authFactory, jwtHelper, store, $state, $location, $rootScope) {
        $rootScope.currentState = 1;
        console.log('entro en login');
        var vm = this;


        vm.loginV = 1;
        vm.register = 0;
        vm.forgot = 0;

        var token = store.get('token') || null,
            bool = true;
        if (token) {
            bool = jwtHelper.isTokenExpired(token);
            if (bool === false) {
               $location.path('/');
                return false;
            }
        }

        vm.login = function (form) {

            console.log(vm.user);
            if (form.$valid) {
                authFactory.login(vm.user).then(function (res) {
                    console.log(res);
                    if (res.token !="") {

                        store.set('token', res.token);
                        store.set('my_user', res.response.users);
                        $rootScope.myUser = res.response.users;
                        $state.go('app.commercial.list');
                    }else{
                        vm.flash = res.response.error;
                    }
                    
                }, function (error) {
                    vm.flash = error;
                });
            }
        };

        vm.user = {
            email: store.get('email') || '',
            password: ''
        };
        vm.flash = '';
    }])
.controller('RegisterController', ['CONFIG', 'authFactory', 'store', '$state',  function (CONFIG, authFactory, store, $state) {
        var vm = this;
        vm.flash = false;
        vm.onSubmit = function (form) {
            console.log(vm.user);
            if (form.$valid) {
                authFactory.register(vm.user).then(function (res) {
                    console.log(res);
                    if (res.code.status == 1) {
                        vm.flash = res.code.error;
                    }else{
                    }
                    
                }, function (error) {
                    vm.flash = error;
                });
            }
        };

       
    }])
;
