<h2>FAQ</h2>
<style>
    .faq-title{
        background: #DCE5EF;
        padding: 4px 7px;
        font-weight: bold;
        margin-top: 5px;
    }
    .faq-content{
        display: none;
        border-left: 1px solid #E9EFF8;
        border-right: 1px solid #E9EFF8;
        border-bottom: 1px solid #E9EFF8;
        padding: 6px;        
    }
    .faq-link{
        color: #2F53FF;
        text-decoration: underline;
    }
    .faq-link:hover{        
        color: #599CFF;
        cursor: pointer;
    }
    .p-m{
        color: #2F53FF;
        font-weight: bold;
        float: right;
        background: url(<?=SITE_URL?>/image/glyphicons-halflings.png);
        padding: 0px 7px;        
    }
    .plus{
        background-position: 63px 64px;
    }
    .minus{
        background-position: 38px 64px;
    }
</style>
<script>
function show_question(event){
    t=event.target||event.srcElement;
    $(t).parent().parent().find('.faq-content').toggle();
    $(t).parent().find('i').toggleClass('minus');
}
</script>

<!-- **************************** Заголовок 1 *************************** -->
<h3>Финансовые вопросы</h3>
<!-- **************************** (Вопрос 1) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>1.</b> <span class="faq-link" onclick="show_question(event);">
           Что будет, когда тестовый аккаунт закончится?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
        Ваш пробный аакаунт будет временно приостановлен.<br>
Вам нужно будет обратится в поддержку для покупки одного из платных аккаунтов!
    </div>
</div>
<!-- **************************** (Вопрос 2) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>2.</b> <span class="faq-link" onclick="show_question(event);">
            Сколько стоит LP-CRM?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
        
        LP-CRM система стоит в зависимости от выбраного тарифа. Которые вы можете посметреть <a href="http://lp-crm.biz/">здесь</a>?
    </div>
</div>
<!-- **************************** (Вопрос 3) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>3.</b> <span class="faq-link" onclick="show_question(event);">
Что такое абонплата?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
        Ежегодно за поддержку и обновления берется минимальная сумма, которая указана в каждом тарифе.
    </div>
</div>

<!-- **************************** Заголовок 2 *************************** -->
<h3>Технические вопросы</h3>
<!-- **************************** (Вопрос 2) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>1.</b> <span class="faq-link" onclick="show_question(event);">
            Обзор CRM для лендинг пейдж
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
        В данном видео Вы можете ознакомится с принципами работы 
        CRM системы лендинг пейдж.
        <p style="text-align: center;">
        <iframe width="640" height="360" src="//www.youtube.com/embed/dn1oVE-rJoU?feature=player_detailpage" frameborder="0" allowfullscreen></iframe>
        </p>
        <b>Опубликовано: 3 окт. 2014 г.</b><br>
        CRM для лендинг пейдж.<br>        
        <a href="http://lp-crm.biz/">http://lp-crm.biz/</a><br>
        Полная автоматизация Вашего бизнеса!<br>
    </div>
</div>
<!-- **************************** (Вопрос 1) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>2.</b> <span class="faq-link" onclick="show_question(event);">
            Сколько сотрудников я могу создавать?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">


Вы можете создавать разное количество сотруднков в зависимости от Вашего трифа. <br>
Кажому сотруднику Вы можете давать индивидуальные права в строке Настройки->Доступы.  <br>
Рекомедуем не давать доступы к "Корзине" и "Истории" всем сотрудникам.
    </div>
</div>

<!-- **************************** (Вопрос 3) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>3.</b> <span class="faq-link" onclick="show_question(event);">
           Как мне подключить автоматическую рассылку sms?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
       Вам нужно обратится в любую билинговую компанию. Лучший и быстрый выбор для Вас это будет <a href="http://turbosms.ua/">Турбосмс</a>. <br>
При покупке тарифа бизнес или корпоратив мы Вам подключим все сами!
    </div>
</div>
<!-- **************************** (Вопрос 3) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>4.</b> <span class="faq-link" onclick="show_question(event);">
          Что писать в "Отправители в шаблонах СМС-сообщений"?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
Здесь пишется Ваша подпись в билинговой компании. 
    </div>
</div>
<!-- **************************** (Вопрос 3) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>5.</b> <span class="faq-link" onclick="show_question(event);">
           Как работает интеграция с Новой Почтой?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
       


Вы сможете посмотреть любое отделение Новой Почты и отследить любую посылку.<br> 
Так же можете автоматически узнать статус Вашего клиента. <br>
Если указана ошибка - проверьте правильность номера декларации Новой Почты.
    </div>
</div>
<!-- **************************** (Вопрос 3) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>6.</b> <span class="faq-link" onclick="show_question(event);">
          Для чего создана страница "Отправка"?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
      Страница "отправка" создана для курьера и для быстрой печати всех сегодняшних Ваших заказов!<br> Заказы подтягиваются со статуса заказов "Принято".
    </div>
</div>
<!-- **************************** (Вопрос 3) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>7.</b> <span class="faq-link" onclick="show_question(event);">
          Как часто выходит обновления?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
       
Не реже раза в месяц обновляются и улучшаются функции в crm. <br>Все обновления предупреждаются по электронной почте заранее. <br>
Наша команда ежедневно работает  над производительностью и улучшением даного продукта. <br>Так же прислушиваемся к каждому Вашему пожеланию.

    </div>
</div>
<!-- **************************** (Вопрос 3) **************************** -->
<div class="question-box">
    <div class="faq-title">
        <b>8.</b> <span class="faq-link" onclick="show_question(event);">
Как интегрировать LP-CRM с моим сайтом?
        </span> <i class="p-m plus">&nbsp;</i>
    </div>
    <div class="faq-content">
     По инструкции обратитесь в службу поддержки!
    </div>
</div>










