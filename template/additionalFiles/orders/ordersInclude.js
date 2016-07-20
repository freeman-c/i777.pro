$(document).ready(function(){ 
    
    $('#modal-close').click(function(){
        setCallTaskState('<?=$order['id']?>',0);
    });

    $('#complete_status').click(function(){
        if($(this).is(':checked')){
            $('input[name="date_complete"]').removeAttr('disabled');
            $('input[name="date_complete"]').attr('readonly',true);
            $('input[name="date_complete"]').val('<?=date('Y-m-d');?>');
        }else{
            $('input[name="date_complete"]').attr('disabled','disabled');
            $('input[name="date_complete"]').val('0000-00-00');
        }
    });
    
    // $('input[name="phone"]').mask("(999) 999-99-99");

    var ttn = $('input[name="ttn"]').val();
    var delivery = $('select[name="delivery"]').val();
    if(ttn.length > 0){
        if(delivery == 'Новая Почта'){
            $.ajax({
                url: "/modules/np/info.php",
                method: 'POST',
                data : {ttn:ttn},
                beforeSend: function(){},
                success: function(data){
                    //alert(data);
                    $('#result-np').show();
                    $('#result-np').html(data);
                },
                error: function() { alert('Error API nova_poshta: tracking'); }                    
            });
        }
    }   

    $('select[name="delivery"]').chosen({disable_search: true});
    $('#city').chosen({no_result_text: 'Не удалось найти город', allow_single_deselect: true});
    $('#warehouse').chosen({no_result_text: 'Не удалось найти отделение', allow_single_deselect: true});


    function changeDeliveryType(){
        if ($('select[name="delivery"]').val() == 'Новая Почта'){
            $('.npComponentContainer').show();
            $('.otherComponentConrainer').hide();
        }
        else{
            $('.npComponentContainer').hide();
            if ($('select[name="delivery"]').val() != '')
                $('.otherComponentConrainer').show();
        }
    }

    <?php if ($_GET['status'] == '0') { 
        session_start();
        $user_info = get_user_description_login($_SESSION['user']['login']); 
        ?>
        $('#sm').find('option:contains("<?=$user_info['surname']?> <?=$user_info['name']?>")').attr('selected', 'selected');
        $('select[name="status"]').find('option:contains("Новый")').attr('selected', 'selected');
        // $('select[name="office"]').find('option:contains("Главный офис")').attr('selected', 'selected');
        $('select[name="delivery"]').find('option:contains("Новая")').attr('selected', 'selected');
        $('select[name="delivery"]').trigger('chosen:updated');
        $('select[name="payment"]').find('option:contains("Налож.")').attr('selected', 'selected');
    <?php } ?>

    changeDeliveryType();

    $('select[name="delivery"]').change(function(){
        changeDeliveryType(); 
    });

    //загружает список отделений
    function loadWarehouse(){
        $.ajax({
            url: "/modules/np/get_np_warehouse_list.php",
            method: 'POST',
            async: false,
            data : {
                format: 'select',
                cityName:$('#city').val()
            },
            success: function(data){
                // console.log(data);
                $('#warehouse').append(data);
                $("#warehouse").trigger('chosen:updated');
            }
        });
    }

    $('#city').change(function(){
        $('#warehouse').empty();
        $('#warehouse').append('<option value="">Выберите отделение</option>');
        loadWarehouse();  
    });

    $(function(){
        $(document).click(function(event) {
            if ($(event.target).closest(".div-tooltip").length || $(event.target).closest(".tooltip").length) return;
            $('.div-tooltip').fadeOut();
            event.stopPropagation();
        });
    });

    function addChildScript(parent_name, name, title, text){
        $('#'+parent_name).append('<ul id="menu-list">'
                            +'<li class="menu-item">'
                                +'<div class="category-wrapper">'
                                    +'<span class="expander"></span>'
                                    +'<div class="menu-item-label-container">'
                                       +'<span class="menu-item-label" onclick="ScriptToggle(event); showScriptText(event);">'
                                            +title
                                        +'</span>'
                                    +'</div>'
                                    +'<div class="script-view" id="'+name+'">'
                                        +'<input class="script-text" type="hidden" value="'+text+'">'
                                    +'</div>'
                                +'</div>'
                            +'</li>'
                    +'</ul>');
        $('#'+parent_name).parent().find('.expander:first').css('display', 'block');
    }

    function appendLi(type, name, title, text){
        $('.'+type).append('<li class="menu-item">'
                                +'<div class="category-wrapper">'
                                    +'<span class="expander"></span>'
                                    +'<div class="menu-item-label-container">'
                                       +'<span class="menu-item-label" onclick="ScriptToggle(event); showScriptText(event);">'
                                            +title
                                        +'</span>'
                                    +'</div>'
                                    +'<div class="script-view" id="'+name+'">'
                                        +'<input class="script-text" type="hidden" value="'+text+'">'
                                    +'</div>'
                                +'</div>'
                            +'</li>');
    }

    function createScriptList(){
    <?php 
        $scriptList = getTemplatesScript();
        $search = array("\r\n");
        foreach ($scriptList as $elem) {
            ?>
            var text = '<?=str_replace($search, "<br>", $elem['text'])?>';
            <?php
            if (!empty($elem['parent_name'])){
            ?> 
                addChildScript('<?=$elem['parent_name']?>', '<?=$elem['name']?>', '<?=$elem['title']?>', text);
            <?php
            }
            elseif ($elem['type'] == 0) {
                ?>
                    appendLi('main-scripts','<?=$elem['name']?>', '<?=$elem['title']?>', text);
                <?php
            }
            elseif ($elem['type'] == 1) {
                ?>
                    appendLi('sub-scripts', '<?=$elem['name']?>', '<?=$elem['title']?>', text);
                <?php
            }
        }
    ?>
    }

    $('#datetimepicker').datetimepicker({
        format:'d.m.Y H:i',
        minDate: 0,
        minTime: 0,
        onSelectDate: function(){
            var d = $('#datetimepicker').datetimepicker('getValue');
            if (d.getDate() != '<?=date('d')?>')
                $('#datetimepicker').datetimepicker({minTime: '00:00:00'});   
            else
                $('#datetimepicker').datetimepicker({minTime: 0});   
        }
    });
    $.datetimepicker.setLocale('ru');

    $('.no-answer').click(function(){
        <?php 
        date_default_timezone_set(TIME_ZONE);
        $call_time = date('H', time()+3600);
        ($call_time >= 9 && $call_time <= 20) ? $call_time = date('d.m.Y H:i', time()+3600) : $call_time = "08:00 ".date('d.m.Y', time()+24*3600);
        ?>
        $('#datetimepicker').val('<?=$call_time?>');
        $('.comment').text('<?=date('d.m.Y')?> НД <?=date('H:i')?>\n'+$('.comment').text());
    });

    $('.clear_datetimepicker').click(function(){
        $('#datetimepicker').val('');
    });

    $('.add_time_to_comment').click(function(){
        var comment = '   '+$('#datetimepicker').val()+'   \n'+$('.comment').val();
        $('.comment').val(comment);
    })

    function Product_Order(){
        <?php !empty($order['order_id']) ? $order_id = $order['order_id'] : $order_id = $_SESSION['user']['new_order']; ?>
        link = "/template/include/order_product.php?order_id=<?=$order_id?>";
        // alert(link);
        $('#order-product').load(link);   
    }

    function getRecomendedProductList(){
        <?php !empty($order['order_id']) ? $order_id = $order['order_id'] : $order_id = $_SESSION['user']['new_order']; ?>
        $('#recomended-product-container').load('/template/include/recomended_product_in_order.php?order_id=<?=$order_id?>');
    }

    Product_Order();
    getRecomendedProductList();

    createScriptList();

    getGeoIPInfo('<?=$order['ip']?>', '<?=$order['site']?>');   
});