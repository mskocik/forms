<?php declare(strict_types=1);

use Nette\Utils\ArrayHash;

require './bootstrap.php';

$options = [
    'a1' => 'Item 1',
    'a2' => 'Item 2',
    'a3' => 'Item 3 X',
    'a4' => 'Item 4',
];

$simpleGroups = [
    'Active' => [
        '1' => 'Item 1',
        '2' => 'Item 2',
        '3' => 'Item 3',
    ],
    'Inactive' => [
        'x' => '{inactive} 1',
        'y' => '{inactive} 2',
        'z' => '{inactive} 3',
    ],
    'xz' => 'No group'
];
$simpleObjects = [
    'aa' => ArrayHash::from([
        'id' => 'aa',
        'label' => 'My Label (aa)',
    ]),
    'bb' => ArrayHash::from([
        'id' => 'bb',
        'label' => 'My Label (bb)',
    ]),
];


$form = new TestForm();
$form->setMethod('get');
// $form->addSvelecteSelect('simple', 'Simple')
//     ->setItems(['one', 'two','three'])
//     ->setRequired();
// $form->addSvelecteSelect('key_value', 'Key-value')
//     ->setItems($options);
$form->addSvelecteSelect('groups', 'Groups', $simpleObjects)
    ->setItemMapper(fn(ArrayHash $item) => ['id' => $item->id, 'label' => $item->label]);

$form->addSubmit('send', 'Send')
    ->renderAsButton();

$form->initialize();

include './render.php';