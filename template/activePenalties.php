<h2>Активные штрафы
    <span id="panel-button-operation">
        <button class="button add-active-penalty">+ Добавить</button>
        <button class="button-error delete-active-penalty" id="button-operation-delete">
            Удалить 
            <span id="count-elements-delete"></span>
        </button>
    </span>
</h2>

<link type="text/css" rel="stylesheet" href="/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/dataTables/formatted-numbers.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/activePenalties/activePenaltiesTmpl.css">
<script type="text/javascript" src="/template/additionalFiles/activePenalties/activePenaltiesTmpl.js"></script>

<form id="form-active-penalties">  
    <table id="table-list" class="active-penalties-table" border="0" cellspacing="0">
        <thead>
            <tr>
                <td width="20px"> 
                    <div id="box-input-select-all">
                        <input type="checkbox" id="select-all-checkbox">
                        <div class="box-arrow-down"></div>
                    </div> 
                </td>
                <td></td>
                <td>Сотрудник</td>
                <td>Вид штрафа</td>
                <td>Сумма, грн</td>
                <td>Дата назначения</td>
                <td>Комментарий</td>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</form>

