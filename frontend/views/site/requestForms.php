<?php
/**
 * teleport.dev
 * Created: 01.03.16 11:23
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */
use kartik\helpers\Html;

/**
 * @var $this \yii\web\View
 */
$this->title = 'Формы заявок';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <div class="panel panel-info">

        <div class="panel-heading"><span class="glyphicon glyphicon-info-sign"></span></div>

        <div class="panel-body">

            <p>По всем вопросам, проблемам, предложениям касающимся:</p>

            <ul>
                <li>Телефонной связи</li>
                <li>Сотовой связи</li>
                <li>Видеоконференций</li>
                <li>Презентаций</li>
            </ul>

            <p>Вы можете позвонить по телефону <b>00-00</b> или обратится по электронной почте, написав сообщение на
                адрес <a href="mailto:oskr@niaep.ru">oskr@niaep.ru</a></p>

        </div>

    </div>

    <p>Ниже представлены основные формы заявок на телефонную связь.</p>

    <ul>

        <li><?= Html::a('Заявка на номер служебной сотовой связи', ['/docx/mobile-request.docx']) ?></li>
        <br>
        <li><?= Html::a('Заявка на перенос телефона(номера)', ['/docx/Форма заявки на перенос номера.docx']) ?></li>
        <li><?= Html::a('Заявка на смену владельца телефона(номера)', ['/docx/Форма заявки на смену владельца телефона.docx']) ?></li>
        <li><?= Html::a('Заявка на выделение нового телефона', ['/docx/Форма заявки на телефон.docx']) ?></li>

    </ul>

</div>
