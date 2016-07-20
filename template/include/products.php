
<?php 
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/products/products.php');
    if (!empty($_GET['id']))
        $product = getProduct_($_GET['id']);
?>

<script type="text/javascript" src="/template/additionalFiles/products/productsInclude.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/products/productsInclude.css">

<form id="forma-product">
    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr>
            <td>Название</td>
            <td> <input type="text" class="name" value="<?=$product['name']?>"> </td>
        </tr>
        <tr>
            <td>Цена</td>
            <td> <input type="number" min="0" max="10000" step="1" class="price" value="<?=$product['price']?>" size="8"> </td>
        </tr>
        <tr>
            <td>Вес</td>
            <td> <input type="number" min = "0.2" max="100" step="0.05" class="weight" value="<?=$product['weight']?>" size="8" style="width: 50px;"> </td>
        </tr>
        <tr>
            <td>Параметры</td>
            <td style="padding: 2px">
                <table>
                    <tr>
                        <td style="padding: 0">
                            Ш <input type="number" class="width" value="<?=$product['width']?>" size="4" style="width: 50px;">
                            В <input type="number" class="height" value="<?=$product['height']?>" size="4" style="width: 50px;">
                            Д <input type="number" class="length" value="<?=$product['length']?>" size="4" style="width: 50px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>Количество</td>
            <td> <input type="text" class="quantity" value="<?=$product['quantity']?>" size="3"> </td>
        </tr>
        <tr>
            <td>Считать только<br>в выручку</td>
            <td>
                <input type="checkbox" id="only_profit" class="only_profit">
                <script type="text/javascript">
                    var only_profit = '<?=$product['only_profit']?>';
                    if (only_profit == 'on'){
                        $('#only_profit').prop('checked','checked');
                    }
                </script>
            </td>
        </tr>
        <tr>
            <td>Описание<br>для менеджеров</td>
            <td> <textarea class="full_description" rows="8" cols="39"><?=$product['full_description']?></textarea> </td>
        </tr>
        <tr>
            <td>Описание<br>для курьеров</td>
            <td> 
                <input type="text" class="description" value="<?=htmlspecialchars($product['description']);?>" size="40">
            </td>
        </tr>
        <tr>
            <td>Ссылка</td>
            <td> 
                <input type="text" class="link" value="<?=htmlspecialchars($product['link']);?>" size="200" style="width: 247px">
            </td>
        </tr>
        <tr>
            <td></td>
            <td> 
                <a target="_blank" href="<?=htmlspecialchars($product['link']);?>"><?=htmlspecialchars($product['link']);?></a>
            </td>
        </tr>
    </table>
    <input type="hidden" class="id" id="product-id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
    <button class="button button-save-product">Сохранить</button>
    <button class="disabled close-modal">Отмена</button>
</p>