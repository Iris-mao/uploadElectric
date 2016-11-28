<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>电路图测试 view</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-inline">
    <div class="form-group dropdown _map_toggle">
        <button type="button" class="btn btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            选择电路图
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="1">电图路1</a></li>
            <li><a href="2">电路图2</a></li>
        </ul>
    </div>
    <div class="form-group btn btn-primary"><a href="add_electric.php" style="color: #fff;">添加电路图</a></div>
</div>
<div class="electric-content">
    <div id="preview">
        <img id="imghead" src="">
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    var demo_data_json = {
        'id': 1,
        'map': 'img/electric1.png',
        'name': 'xxx区域电路图',
        'data': [
            {
                "meter_id": "1",
                "meter_name": "我只电表名字",
                "params": [{"key": "i", "value": "500A"}, {"key": "c", "value": "100"}, {
                    "key": "_",
                    "value": "1213"
                }],
                "left": "511px",
                "top": "244px",
                "width": "80px"
            }, {
                "meter_id": "1",
                "meter_name": "我只电表名字aaa",
                "params": [{"key": "i", "value": "500A"}, {"key": "c", "value": "100"}, {
                    "key": "_",
                    "value": "1213"
                }],
                "left": "111px",
                "top": "344px",
                "width": "80px"
            }
        ]
    };

    $('._map_toggle a').click(function (e) {
        e.preventDefault();

        // json get

        draw_map(demo_data_json);
    });

    // 绘制电路图
    function draw_map(options) {
        var defaults = {};
        options = $.extend(defaults, options);

        // background
        $('#preview img').attr('src', options.map);

        for (var i in options.data) {
            add(options.data[i]);
        }

    }

    var meter_params = {
        '_': '自定义',
        'i': '电流',
        'u': '电压',
        'c': '电容'
    };

    function add(options) {
        var defaults = {
            left: '10px',
            top: '10px',
            data: []
        };
        options = $.extend(defaults, options);
        var html = '<div>' + options.meter_name + '</div>';
        for (var i in options.params) {
            var label = options.params[i].key == '_' ? '' : meter_params[options.params[i].key] + '：';
            html += '<div>' + label + options.params[i].value + '</div>'
        }
        $('<div class="contant" style="left:' + options.left + '; top:' + options.top + ';">' + html + '</div>')
            .appendTo('.electric-content');
    }
</script>
</body>