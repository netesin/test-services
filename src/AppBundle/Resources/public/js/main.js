'use strict';

/**
 * Main module
 */
define(
    [
        'angular',
        'angular-messages',
        'angular-ui-bootstrap',
        'angular-ui-router',
        'rpc',
        'jquery'
    ],
    function (angular) {
        
        /**
         * Main module
         */
        
        angular
            .module(
                'app',
                [
                    'ngMessages',
                    'rpc',
                    'ui.router',
                    'ui.bootstrap'
                ]
            )
            .config(['$locationProvider', '$urlRouterProvider', '$stateProvider', config]);
        
        /**
         * Config
         */
        function config($locationProvider, $urlRouterProvider, $stateProvider) {
            
            $locationProvider.html5Mode(true).hashPrefix('!');
            $urlRouterProvider.otherwise('/');
            
            $stateProvider
                .state(
                    'order',
                    {
                        url    : '/',
                        views  : {
                            'main@': {
                                controller  : ['dateFilter', 'rpc', 'services', OrderController],
                                controllerAs: '$ctrl',
                                templateUrl : '/tpl/order.html'
                            }
                        },
                        resolve: {
                            services: ['rpc', function (rpc) {
                                return rpc('services.get');
                            }]
                        }
                    }
                )
                .state(
                    'admin',
                    {
                        url    : '/admin',
                        views  : {
                            'main@': {
                                controller  : ['dateFilter', '$uibModal', 'rpc', 'services', 'discounts', AdminController],
                                controllerAs: '$ctrl',
                                templateUrl : '/tpl/admin.html'
                            }
                        },
                        resolve: {
                            services : ['rpc', function (rpc) {
                                return rpc('services.get');
                            }],
                            discounts: ['rpc', function (rpc) {
                                return rpc('discounts.get');
                            }]
                        }
                    }
                )
        }
        
        
        /**
         * Admin controller
         */
        function AdminController(dateFilter, $uibModal, rpc, services, discounts) {
            
            var self       = this;
            self.services  = services;
            self.discounts = discounts;
            
            self.createService = createService;
            self.editService   = editService;
            self.deleteService = deleteService;
            
            self.createDiscount = createDiscount;
            self.editDiscount   = editDiscount;
            self.deleteDiscount = deleteDiscount;
            
            // Discount
            
            /**
             * Edit discount.
             */
            function editDiscount(discount) {
                
                var oldValue = discount;
                var modal    = $uibModal.open({
                    animation   : true,
                    size        : 'lg',
                    controller  : ['$uibModalInstance', 'discount', 'services', discountModalCtrl],
                    controllerAs: '$ctrl',
                    templateUrl : '/tpl/modal/discount.html',
                    resolve     : {
                        discount: angular.copy(discount),
                        services: function () {
                            return self.services;
                        }
                    }
                });
                
                modal.result.then(function (discount) {
                    
                    rpc('discounts.update', discount)
                        .then(function (result) {
                            if (result) {
                                var index             = self.discounts.indexOf(oldValue);
                                self.discounts[index] = angular.extend(oldValue, discount);
                            }
                        });
                    
                });
                
            }
            
            /**
             * Delete discount.
             */
            function deleteDiscount(discount) {
                rpc('discounts.delete', {id: discount.id})
                    .then(function (result) {
                        if (result) {
                            self.discounts.splice(self.discounts.indexOf(discount), 1);
                        }
                    });
            }
            
            /**
             * Create discount.
             */
            function createDiscount() {
                
                var modal = $uibModal.open({
                    animation   : true,
                    size        : 'lg',
                    controller  : ['$uibModalInstance', 'discount', 'services', discountModalCtrl],
                    controllerAs: '$ctrl',
                    templateUrl : '/tpl/modal/discount.html',
                    resolve     : {
                        discount: {},
                        services: function () {
                            return self.services;
                        }
                    }
                });
                
                modal.result.then(function (discount) {
                    
                    rpc('discounts.create', discount)
                        .then(function (result) {
                            if (result) {
                                self.discounts.push(result);
                            }
                        });
                    
                });
                
            }
            
            /**
             * Discount modal controller.
             */
            function discountModalCtrl($uibModalInstance, discount, services) {
                
                var self                     = this;
                var originalDiscountServices = [];
                discount                     = discount || {};
                
                if (discount.services) {
                    
                    discount.services.forEach(function (service) {
                        originalDiscountServices.push(service);
                    });
                    
                    discount.services = [];
                    
                    originalDiscountServices.forEach(function (service) {
                        discount.services[service.id] = true;
                    })
                    
                }
                
                if (discount.activatedAt) {
                    discount.activatedAt = new Date(discount.activatedAt);
                }
                
                if (discount.activatedTo) {
                    discount.activatedTo = new Date(discount.activatedTo);
                }
                
                self.discount = discount;
                self.services = services;
                self.close    = close;
                
                
                self.dateFormat = 'yyyy-MM-dd';
                
                self.isOpenActivatedAtDatepicker = false;
                self.isOpenActivatedToDatepicker = false;
                self.openActivatedAtDatepicker   = openActivatedAtDatepicker;
                self.openActivatedToDatepicker   = openActivatedToDatepicker;
                
                function openActivatedAtDatepicker() {
                    self.isOpenActivatedAtDatepicker = true;
                }
                
                function openActivatedToDatepicker() {
                    self.isOpenActivatedToDatepicker = true;
                }
                
                function close(discount) {
                    
                    if (!discount.services) {
                        discount.services = [];
                    }
                    
                    var discountServices = [];
                    
                    services.forEach(function (service) {
                        
                        if (discount.services[service.id]) {
                            discountServices.push(angular.copy(service));
                        }
                        
                    });
                    
                    if (discount.activatedAt) {
                        discount.activatedAt = dateFilter(discount.activatedAt, 'yyyy-MM-dd');
                    }
                    
                    if (discount.activatedTo) {
                        discount.activatedTo = dateFilter(discount.activatedTo, 'yyyy-MM-dd');
                    }
                    
                    discount.services = discountServices;
                    
                    $uibModalInstance.close(discount);
                }
            }
            
            // Service
            
            /**
             * Create new service.
             */
            function createService() {
                
                var modal = $uibModal.open({
                    animation   : true,
                    size        : 'lg',
                    controller  : ['service', serviceModalCtrl],
                    controllerAs: '$ctrl',
                    templateUrl : '/tpl/modal/service.html',
                    resolve     : {
                        service: {}
                    }
                });
                
                modal.result.then(function (service) {
                    
                    rpc('services.create', service)
                        .then(function (result) {
                            if (result) {
                                self.services.push(result);
                            }
                        });
                    
                });
            }
            
            /**
             * Edit service.
             */
            function editService(service) {
                
                var oldValue = service;
                var modal    = $uibModal.open({
                    animation   : true,
                    size        : 'lg',
                    controller  : ['service', serviceModalCtrl],
                    controllerAs: '$ctrl',
                    templateUrl : '/tpl/modal/service.html',
                    resolve     : {
                        service: angular.copy(service)
                    }
                });
                
                modal.result.then(function (service) {
                    
                    rpc('services.update', service)
                        .then(function (result) {
                            if (result) {
                                var index            = self.services.indexOf(oldValue);
                                self.services[index] = angular.extend(oldValue, service);
                            }
                        });
                    
                });
                
            }
            
            /**
             * Delete service.
             */
            function deleteService(service) {
                rpc('services.delete', {id: service.id})
                    .then(function (result) {
                        if (result) {
                            self.services.splice(self.services.indexOf(service), 1);
                        }
                    });
            }
            
            /**
             * Service modal controller.
             */
            function serviceModalCtrl(service) {
                this.service = service;
            }
            
        }
        
        /**
         * Order controller
         */
        function OrderController(dateFilter, rpc, services) {
            
            var self = this;
            
            self.services         = services;
            self.dateFormat       = 'yyyy-MM-dd';
            self.dateOptions      = {
                maxDate    : new Date(),
                startingDay: 1
            };
            self.isOpenDatepicker = false;
            self.openDatepicker   = openDatepicker;
            self.getDiscount      = getDiscount;
            
            function openDatepicker() {
                self.isOpenDatepicker = true;
            }
            
            /**
             * Get discount by order.
             */
            function getDiscount() {
                
                var order = angular.copy(self.order);
                
                if (order.birthday) {
                    order.birthday = dateFilter(order.birthday, 'yyyy-MM-dd')
                }
                
                if (!order.services) {
                    order.services = [];
                }
                
                var orderServices = [];
                
                services.forEach(function (service) {
                    
                    if (order.services[service.id]) {
                        orderServices.push(angular.copy(service));
                    }
                    
                });
                
                order.services = orderServices;
                
                rpc('order.getDiscount', order)
                    .then(function (result) {
                        alert('Ваша скидка ' + result + '%');
                    });
                
            }
            
        }
        
        // On ready run.
        
        require(['domReady!'], function (document) {
            
            angular.bootstrap(document, ['app']);
            
            angular.element('#main').removeAttr('style');
            angular.element('#loader').css({display: 'none'});
            angular.element('body').css({overflow: 'auto'});
            
        });
    }
);

