/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define(['jquery', 'datatable'], function () {
    var index = (function () {

        initDataTable = function () {

            var configTable = $('#config-table').DataTable({
                dom: 'lftip',
                paging: false
            });
        };

        return {
            init: function () {
                initDataTable();
            }
        };

    }());    
    
    return index;
});