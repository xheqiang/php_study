<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>数据列表</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/daterangepicker.css" rel="stylesheet" media="screen">
    <link href="/public/css/daterangepicker.css" rel="stylesheet" media="screen">
    <script src="/public/js/jquery-3.1.1.min.js"></script>
    <script src="/public/js/jquery.dataTables.js"></script>
    <script src="/public/js/dataTables.bootstrap.min.js"></script>
    <script src="/public/js/moment.min.js"></script>
    <script src="/public/js/daterangepicker.js" charset="UTF-8"></script>
</head>
<body>
<div class="container">
    <div class="well">
        <form class="form-inline" action="javascript:void(0);" id="search_form" role="form" style="margin-left:50%;">
            <div class="form-group">
                <label class="sr-only" for="key_value">关键字</label>
                <input type="text" class="form-control fa fa-dashboard" style="width:300px;" id="key_value" name="kw" value="" placeholder="请输入关键字">
            </div>

            <button type="submit" id="search_submit" class="btn btn-success">搜索</button>
        </form>
    </div>
    <table id="DataTable" class="display table table-striped table-bordered">
        <thead>
        <tr style="text-align:center;">
            <th style="text-align:center;line-height: 50px;">房屋说明</th>
            <th style="text-align:center;line-height: 30px;">厅室</th>
            <th style="text-align:center;line-height: 30px;">面积</th>
            <th style="text-align:center;line-height: 50px;">房租</th>
            <th style="text-align:center;">租房模式</th>
            <th style="text-align:center;line-height: 50px;">小区</th>
            <th style="text-align:center;line-height: 50px;">地址</th>
            <th style="text-align:center;">联系人</th>
            <th style="text-align:center;line-height: 50px;">联系电话</th>
            <th style="text-align:center;line-height: 30px;">地铁</th>
            <th style="text-align:center;line-height: 50px;">更新时间</th>
        </tr>
        </thead>
    </table>
</div>
<script>
    $(function () {

        /******初始化DataTable*****************************************************************************/
        var table = $('#DataTable').DataTable({
            "processing": true,		//显示加载信息
            "serverSide": true,		//开启服务器模式
            //"searching": true,		//开启搜索功能

            "autoWidth": true, 	//让Datatables自动计算宽度
            "searching": false,		//开启全局搜索功能
            "bLengthChange": false, //关闭每页显示多少条
            "lengthMenu": [10, 20, 30],		//改变每页显示条数列表的选项
            "pagingType": "full_numbers",		//分页按钮种类显示选项
            "order": [[1, "asc"]],				//表格初始化排序【全选框不用排序】
            "language": {
                "processing": "玩命加载中...",
                "lengthMenu": "显示 _MENU_ 项结果",
                "zeroRecords": "没有匹配结果",
                "search": "搜索:",
                "url": "",
                "emptyTable": "表中数据为空",
                "loadingRecords": "正在加载数据...",
                //下面三者构成了总体的左下角的内容。
                "info": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                "infoEmpty": "显示第 0 至 0 项结果，共 0 项",
                "infoFiltered": "(由 _MAX_ 项结果过滤)",
                "paginate": {
                    "first": "首页",
                    "previous": "上一页",
                    "next": "下一页",
                    "last": "尾页"
                },
            },

            "ajax": {
                "url": "./getData.php",
                "type": "POST",
                "data": {		//添加额外的参数发送到服务器(可以用作搜索条件)
                    extra_search: 3,
                    date: $("#reportrange").val(),
                },
            },
            "columns": [
                {"data": "title", "orderable": false},
                {"data": "room", "orderable": false},
                {"data": "area", "orderable": false},
                {"data": "money", "orderable": false},
                {"data": "method", "orderable": false},
                {"data": "village", "orderable": false},
                {"data": "address", "orderable": false},
                {"data": "contacts", "orderable": false},
                {"data": "telphone", "orderable": false},
                {"data": "line", "orderable": false},
                {"data": "updated_at", "orderable": false},
            ],
            "columnDefs": [],
        });
        /******初始化DataTable*****************************************************************************/

        //高亮列
        $('#DataTable tbody').on('mouseenter', 'td', function () {
            var colIdx = table.cell(this).index().column;

            $(table.cells().nodes()).removeClass('highlight');
            $(table.column(colIdx).nodes()).addClass('highlight');
        });
        //高亮行
        $('#DataTable tbody').on('mouseenter', 'tr', function () {

            if ($(this).hasClass('am-primary')) {
                $(this).removeClass('am-primary');
            } else {
                table.$('tr.am-primary').removeClass('am-primary');
                $(this).addClass('am-primary');
            }
        });
        /*****显示隐藏列********************************************************************************************/
        $('.toggle-vis').on('change', function (e) {
            e.preventDefault();
            //console.log($(this).attr('data-column'));
            var column = table.column($(this).attr('data-column'));
            column.visible(!column.visible());
        });

        $('.showcol').click(function () {
            $('.showul').toggle();
            return false;
        });

        $("#search_form").submit(function () {
            var search_query = $("#key_value").val();
            //console.log(search_query);
            table.ajax.url("./server.php?search_query=" + search_query).load();
            return false;
        });

    });
</script>
</body>
</html>