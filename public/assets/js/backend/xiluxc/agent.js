define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xiluxc/agent/index' + location.search,
                    add_url: 'xiluxc/agent/add',
                    edit_url: 'xiluxc/agent/edit',
                    del_url: 'xiluxc/agent/del',
                    multi_url: 'xiluxc/agent/multi',
                    import_url: 'xiluxc/agent/import',
                    table: 'xiluxc_agent',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'province_name', title: __('省份')},
                        {field: 'city_name', title: __('城市')},
                        {field: 'district_name', title: __('地区')},
                        {field: 'address', title: __('Address'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'lng', title: __('Lng'), operate:'BETWEEN'},
                        {field: 'lat', title: __('Lat'), operate:'BETWEEN'},
                        {field: 'commission_rate', title: __('Commission_rate'), operate:'BETWEEN'},
                        {field: 'status', title: __('Status')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
