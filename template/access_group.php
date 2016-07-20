<h2>Доступ (группы)
    <span id="panel-button-operation">
        <button class="button" id="button-operation-add">+ Добавить</button>
        <button class="button-error" id="button-operation-delete">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>

<link type="text/css" rel="stylesheet" href="/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/dataTables/formatted-numbers.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/access/accessTmpl.css">
<script type="text/javascript" src="/template/additionalFiles/access/accessTmpl.js"></script>

<style>
    .acess-button{
        border: 1px solid #FFF; 
        padding: 1px;        
        margin: 0px 0px -4px 0px;
    }
    .acess-button:hover{
        border: 1px solid #CCC;
        background: #FFC;
        cursor: pointer;
    }
</style>

<form id="form-access"> 
    <table id="table-list" class="access-table" border="0" cellspacing="0">
        <thead>
            <tr>
                <td></td>   
                <td align="center" width="24px"></td>
                <td width="16px"></td>
                <td></td>
                <td></td>
                <td width="150px" style="font-weight: bold;"></td>        
                <td style="font-size: 11px; color:#ABABAB;"><?=$access_group['groups']?></td>
                <td width="16px"></td>
            </tr>
        </thead>
    </table>
</form>
<br>
<div style="clear: both;"></div>
<h2>Исключения</h2>
<table id="table-list2" class="exceptions-table" border="0" cellspacing="0">
    <thead>
        <tr>
<!--             <td></td>   
            <td align="center" width="24px"></td> -->
            <td width="16px"></td>
            <td></td>
            <td></td>
            <td width="150px" style="font-weight: bold;"></td>        
            <td style="font-size: 11px; color:#ABABAB;"><?=$access_group['groups']?></td>
            <td width="16px"></td>
        </tr>
    </thead>
</table>