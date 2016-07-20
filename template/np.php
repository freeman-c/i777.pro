<h2>
    <div class="np-api-logo-conteiner">
        <img src="<?=SITE_URL?>/image/NP_logo.png" style="width: 200px;">
        &nbsp; &nbsp; &nbsp;
        <img src="<?=SITE_URL?>/image/API.png" style="width: 34px;">
    </div>
    <div class="update-warehouses-list-button-container">
        <!-- <span id="panel-button-operation"> -->
            <button class="button-success update-warehouses-list-button">Обновить список отделений</button>
        <!-- </span>   -->
    </div>
</h2>
<script type="text/javascript" src="/template/additionalFiles/np/np.js"></script>
<link rel="stylesheet" type="text/css" href="/template/additionalFiles/np/np.css">

<script type="text/javascript" src="/modules/np/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="/modules/np/chosen/chosen.css">

<table id="table-np" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top" width="40%" style="border-right: 1px solid #E2E2E2;">
            <div style="color: #FF301E; padding: 10px; font-size: 17px;">
                <img src="<?=SITE_URL?>/image/TTH.png" style="width: 45px; margin-bottom: -10px;"> Отследить
            </div>
            <input type="text" id="ttn" placeholder="Номер" style="padding: 3px 6px; margin-right: -3px;">
            <button id="button-np">►</button>
            
            <div style="color: #808080; padding: 1px 2px 8px; font-size: 11px;">Введите номер накладной</div>
            
            <div id="result-np">&nbsp;</div>
        </td>
        
        
        <td valign="top" width="60%">
            <div style="color: #FF301E; padding: 4px; font-size: 17px;">
                <img src="<?=SITE_URL?>/image/warenhouse.png" style="margin-bottom: -6px;"> Список отделений
            </div>
            <!--<input type="text" id="city" placeholder="Назва мiста" size="30" style="padding: 3px 6px; margin-top: 5px;">-->
            <br>
               <select id="city" style="width:340px;">
                    <option value="">Выберите город</option>
               </select>
            <br>
            <div style="color:#808080; padding:2px; font-size:11px; position:relative;">Введите название города (на украинском)</div>
            <div id="city-list"></div>
            
        </td>
    </tr>
</table>