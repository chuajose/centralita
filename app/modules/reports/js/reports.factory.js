trocoWeb
    .factory("ReportsFactory", ["$http", "$q", "CONFIG", "store", function ($http, $q, CONFIG, store) {
        return {
            get: function (data) {
                var deferred,
                deferred = $q.defer();

                $http({
                        method: 'GET',
                        url: CONFIG.APIURL + 'reports/index',
                        params: data,
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(function (res) {
                        console.log(res);
                        if (res.data.code === 0) {
                            deferred.resolve(res.data.response);
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
            post: function (data) {
                var deferred;
                deferred = $q.defer();
                $http({
                        method: 'POST',
                        url: CONFIG.APIURL + 'reports/index',
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

        };
}]);
