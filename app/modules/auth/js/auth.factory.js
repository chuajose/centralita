trocoWeb
    .factory("authFactory", ["$http", "$q", "CONFIG", "store", function ($http, $q, CONFIG, store) {
        return {
            register: function (user) {
                var deferred,
                    data = {
                        email: user.email,
                        password: user.password,
                        password_confirm: user.password,
                        first_name: user.first_name,
                        last_name: user.last_name,
                    };
                deferred = $q.defer();

                $http({
                        method: 'POST',
                        url: CONFIG.APIURL + 'auth/register',
                        data: data,
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(function (res) {
                        deferred.resolve(res.data);
                    }, function (error) {
                        console.log(error);
                        if (error.data && error.data.response) {
                            deferred.reject(error.data);
                        } else {
                            deferred.reject('El servicio no está disponible');
                        }
                    });
                return deferred.promise;
            },
            login: function (user, device) {
                var deferred,
                    data = {
                        email: user.email,
                        password: user.password,
                        method: ''
                    };
                deferred = $q.defer();

                $http({
                        method: 'POST',
                        skipAuthorization: true, //no queremos enviar el token en esta petición
                        url: CONFIG.APIURL + 'auth/login',
                        data: data,
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(function (res) {
                        console.log(res);
                        if (res.data.code === 0) {
                            var result = {
                                token: res.data.token,
                                response: res.data.response
                            };
                            deferred.resolve(result);
                        } else {
                            deferred.reject(res.data.response);
                        }
                    }, function (error) {
                        console.log(error);
                        if (error.data && error.data.response) {
                            deferred.reject(error.data.response);
                        } else {
                            deferred.reject('El servicio no está disponible');
                        }
                    });
                return deferred.promise;
            },
            logout: function () {
                localStorage.clear();
                document.location.href = CONFIG.BASEURL + 'logout';
            },
            
            changePassword: function (old, password, repeat) {
                var deferred = $q.defer();
                $http({
                        method: 'PATCH',
                        url: CONFIG.APIURL + 'auth/password',
                        data: {
                            old_password: old,
                            new_password: password,
                            repeat_password: repeat
                        },
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(function (res) {
                        deferred.resolve(res.data.response);
                    }, function (error) {
                        if (error.data) {
                            deferred.reject(error.data);
                        } else {
                            deferred.reject(error);
                        }
                    });
                return deferred.promise;
            },
        };
}]);
