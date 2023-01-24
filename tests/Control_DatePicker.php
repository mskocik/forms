<?php declare(strict_types=1);

require './bootstrap.php';

$form = new TestForm();
$form->setMethod('get');
$form->addSvelecteMultiSelect('svelecte', 'Svelecte', ['one','two']);
$form->addDatePicker('date', 'Date Picker')
    ->useDateTime(new DateTimeZone('Europe/Prague'));

$form->addSubmit('send', 'Send')
    ->renderAsButton();

$form->initialize();

include './render.php';