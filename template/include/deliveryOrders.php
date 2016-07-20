<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/deliveryOrders/deliveryOrders.php');

if($_GET['orderId'])
    $currentOrder = getDeliveryOrder($_GET['orderId']);
?>

<script type="text/javascript">
    orderId = "<?=$_GET['orderId']?>";
</script>

<script type="text/javascript" src="/template/additionalFiles/deliveryOrders/deliveryOrdersIncl.js"></script>

<style>
    #forma-delivery-orders{
        padding-top: 10px;
    }
    #overlay *{
        -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
    #table-list-data td{
        padding: 4px 4px;
    }
    input[name='total']{
        background: transparent;
        border: none;
    }
    input[name='total']:focus{
        box-shadow: none;
        background: #DDD;
        border: none;
    }
    #add-product-to-order{
        background: linear-gradient(to bottom, #F6FFDB 0px, #A3EA27 100%) transparent;
        border: 1px solid #9DCB2C;
        border-radius: 5px;
        color: #699A03;
        cursor: pointer;
        font-family: "tooltip";
        font-size: 12px;
        margin: 1px 0px 0px;
        padding: 0px 10px 2px;
        text-shadow: 0px 1px 1px #F6F6F6;
    }
    #order-product{
        position: relative;
        overflow: auto;
        /*min-height: 100px;
        max-height: 188px;*/
        height: 200px;
        width: 100%;
        background: #FFF;
        border: 1px solid #CCC;
    }
    select{
        width: 100%;
    }
    #modal-content {
        padding-bottom: 0px;
        padding-top: 0px;
    }
    .col-input{
        text-align: left;
    }
    .col-title{
        width: 100px;
        text-align: right;
    }
    .delivery-order-ttn, .delivery-order-comment{
        width: 100%;
    }
    .delivery-order-comment{
        height: 155px;
        width: 200px;
    }
</style>

<form id="forma-delivery-orders"> 
    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td class="col-title">Статус заказа</td>
                <td class="col-input">
                    <select class="delivery-order-status">
                        <?php
                        if ($_GET['orderId']) { ?>
                        <option value="<?=$currentOrder['s_id']?>"><?=$currentOrder['s_name']?></option>
                        <option disabled></option>
                        <? 
                        } 
                        $statuses = getDeliveryOrdersStatuses();
                        foreach ($statuses as $status) { ?>
                            <option value="<?=$status['id']?>"><?=$status['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td rowspan="10" width="500px" style="overflow: hidden; max-height: 250px; max-width: 450px; min-width: 450px" >
                    <div id="order-product"></div>
                    <div style="text-align:right;">
                        <span style="float:left;">
                            <button onclick="addNewProduct(); return false;" id="add-product-to-order">
                                <img src="/image/plus_circle.ico" style="margin: 0px 0px -4px 0px;">
                                Добавить товар</button>
                        </span>
                    </div> 
                </td>
            </tr>
            <tr>
                <td class="col-title">ТТН</td>
                <td class="col-input">
                    <input type="number" class="delivery-order-ttn" value="<?=$currentOrder['ttn']?>" placeholder="Введите ТТН">
                </td>
            </tr>
            <tr>
                <td class="col-title">
                    Комментарий
                </td>
                <td class="col-input">
                    <textarea class="delivery-order-comment" val="<?=$currentOrder['comment']?>"><?=$currentOrder['comment']?></textarea>
                </td>
            </tr>
            <!-- <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr> -->
        </tbody>
    </table>
    <input type="hidden" id="delivery-order-id" value="<?=$orderId?>">
</form>
<hr>
<p style="text-align:center;">
    <button class="button button-save-delivery-order">Сохранить</button>
    <button class="disabled close-modal">Отмена</button>
</p>
