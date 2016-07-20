<h2>Товары
    <span id="panel-button-operation">
        <button class="button add-product">+ Добавить</button>
        <button class="button-error delete-product" id="button-operation-delete">
            Удалить 
            <span id="count-elements-delete"></span>
        </button>
    </span>
</h2>

<link type="text/css" rel="stylesheet" href="/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/dataTables/formatted-numbers.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/products/products.css">
<script type="text/javascript" src="/template/additionalFiles/products/products.js"></script>

<form id="form-product">  
    <table id="table-list" class="product-table" border="0" cellspacing="0">
        <thead>
            <tr>
                <td width="20px"> 
                    <div id="box-input-select-all">
                        <input type="checkbox" id="select-all-checkbox">
                        <div class="box-arrow-down"></div>
                    </div> 
                </td>
                <td>ID</td>
                <td></td>
                <td></td>
                <td>Наименование</td>
                <td>Цена</td>
                <!-- <td>Габариты</td> -->
                <td>Вес</td>
                <td>К-во</td>
                <td>Статус</td>
                <td>Считать только<br>в выручку</td>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</form>

