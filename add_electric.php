<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>电路图添加测试</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div>
    <input type="file" onchange="previewImage(this)"/>
    <!--    <button type="button" class="add">add</button>-->
</div>
<div class="electric-content">
    <div id="preview">
        <img id="imghead" src="img/electric1.png">
    </div>
</div>
<div class="electric-data">
    <div class="form-group">
        <label class="control-label">电表ID</label>
        <select class="form-control _meter_id" required>
            <option value="">-select first-</option>
            <option value="1">电表1</option>
            <option value="2">电表2</option>
            <option value="3">电表3</option>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label">电表显示</label>
        <button type="button" class="btn _add_param"><span class="glyphicon glyphicon-plus"></span></button>
    </div>
    <div class="form-horizontal _params"></div>
    <div class="form-group">
        <button type="button" class="btn btn-primary btn-block _add">添加</button>
    </div>
</div>

<div class="form-group col-sm-2 col-sm-offset-7">
    <button type="button" class="btn btn-block _save">保存</button>
</div>
<textarea name="" id="code" cols="200" rows="10"></textarea>

<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    var meter_params = {
        '_': '自定义',
        'i': '电流',
        'u': '电压',
        'c': '电容'
    };

    function create_id() {
        var time = (new Date()).valueOf();
        var _start = 1000;
        var _end = 9999;
        var rnd = Math.floor(Math.random() * (_end - _start) + _start);
        return time + rnd;
    }

    function form_reset() {
        var form = $('.electric-data');
        form
            .find('._meter_id').val('').end()
            .find('._params').html('').end()
            .find('._add').text('添加');
        form.data('modify_id', null);
    }
    $('#preview').click(function () {
        form_reset();
    });

    function form_add_param(options) {
        var max_count = 4;
        var wrap = $('._params');
        if (wrap.find('> .form-group').size() >= max_count) {
            alert('只能显示' + max_count + '条信息');
            return false;
        }

        var defaults = {
            key: '',
            value: ''
        };
        options = $.extend(defaults, options);

        var html = '<div class="form-group">' +
            '<div class="col-sm-10">' +
            '<select class="form-control" required>' +
            '<option value="">-请选择-</option>';
        for (var key in meter_params) {
            html += '<option value="' + key + '">' + meter_params[key] + '</option>';
        }
        html += '</select>' +
            '</div>' +
            '<div class="col-sm-2">' +
            '<button type="button" class="close"><span aria-hidden="true">×</span></button>' +
            '</div>' +
            '</div>';
        $(html).appendTo(wrap)
            .find('.close')
            .click(function () {
                if (confirm('sure?')) {
                    $(this).parents('.form-group').remove();
                }
            }).end()
            .find('select')
            .change(function () {
                if ($(this).val() == '_') {
                    $('<input type="text" class="form-control" value="' + options.value + '" placeholder="请输入自定义内容" required>').insertAfter(this);
                } else {
                    $(this).next().remove();
                }
            })
            .val(options.key)
            .change();
    }

    // 添加显示数据
    $('._add_param').click(function () {
        form_add_param();
    });

    // add content
    $('._add').click(function () {
        // 验证输入合法性
        var form = $('.electric-data');
        var check_success = true;
        $(form).find('[required]').each(function (i, o) {
            check_success = ($(o).val() != '') && check_success;
        });
        if (!check_success) {
            alert('请输入完整');
            return false;
        }

        // 电表ID
        var meter_id = $(form).find('._meter_id').val();

        // 获取 params
        var params = [];
        $('.electric-data ._params .form-group').each(function (i, o) {
            params.push({key: $(o).find('select').val(), value: $(o).find('input').val() || null});
        });

        add({target_id: form.data('modify_id'), meter_id: meter_id, params: params});
        form_reset();
    });

    function save_status() {
        var myJson = [];
        $('.electric-content > .contant').each(function (i, obj) {
            var _data = $(obj).data('options');
            myJson[i] =
            {
                'meter_id': _data.meter_id,
                'params': _data.params,
                'left': $(obj).css('left'),
                'top': $(obj).css('top'),
                'width': $(obj).css('width')

            }
        });
        var string = JSON.stringify(myJson);
        $('textarea#code').val(string);
    }

    $('._save').click(function () {
        // save status
        save_status();
    });

    //    lib.api.get('url',function(json){});
    var json = [{
        "meter_id": "1",
        "params": [{"key": "i", "value": null}, {"key": "c", "value": null}, {"key": "_", "value": "1213"}],
        "left": "553px",
        "top": "320px",
        "width": "80px"
    }];

    for (var i in json) {
        add(json[i]);
    }

    function add(options) {
        var defaults = {
            target_id: null,
            left: '10px',
            top: '10px',
            meter_id: null,
            params: []
        };
        options = $.extend(defaults, options);
        var html = '<div>电表ID：' + options.meter_id + '</div>';
        for (var i in options.params) {
            html += '<div>' + (options.params[i].value || meter_params[options.params[i].key]) + '</div>'
        }
        if (options.target_id) {
            var contant = $('.contant[rel=' + options.target_id + ']');
            contant
                .html(html)
                .data('options', options);
            // 重新设定高度
            var count_of_item = contant.find('div').size();
            var height = contant.find('div').height();
            contant.height(height * count_of_item);
        } else {
            $('<div class="contant" rel=' + create_id() + ' style="left:' + options.left + '; top:' + options.top + ';">' + html + '</div>')
                .appendTo('.electric-content')
                .data('options', options)
                .draggable({
                    containment: "parent"
                })
                .click(function () {
                    var contant_options = $(this).data('options');
                    var form = $('.electric-data');
                    form.find('._meter_id').val(contant_options.meter_id); // todo: 全浏览器兼容？
                    form.find('._params').html('');
                    for (var index in contant_options.params) {
                        var item = contant_options.params[index];
                        form_add_param(item);
                    }
                    form.find('._add').text('保存');
                    form.data('modify_id', $(this).attr('rel'));
                });
        }
    }
</script>
<script type="text/javascript">
    //图片上传预览    IE是用了滤镜。
    function previewImage(file) {
        var div = document.getElementById('preview');
        if (file.files && file.files[0]) {
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.onload = function () {
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width = rect.width;
                img.height = rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top + 'px';
            }
            var reader = new FileReader();
            reader.onload = function (evt) {
                img.src = evt.target.result;
            };
            reader.readAsDataURL(file.files[0]);
        }
        else //兼容IE
        {
            var sFilter = 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status = ('rect:' + rect.top + ',' + rect.left + ',' + rect.width + ',' + rect.height);
            div.innerHTML = "<div id=divhead style='width:" + rect.width + "px;height:" + rect.height + "px;margin-top:" + rect.top + "px;" + sFilter + src + "\"'></div>";
        }
    }
</script>
</body>