<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        <?= file_get_contents('svelecte.css'); ?>
    </style>
</head>
<body>
    
    <?php if (!empty($_GET)) {
        if ($form->hasErrors()) {
            var_dump($form->getErrors());
        } else {
            var_dump($form->getUntrustedValues()); 
        }
        echo '<hr>';
    }?>
    <?= $form->render(); ?>

    <script>
        const e = document.querySelector('select');
        e.addEventListener('change', ex => console.log(e.selectedOptions));
    </script>
    <script src="/live-form-validation.js"></script>
    <script type="module">
        import Picker, { registerSveltyPicker, config } from '/svelty-picker-full.js';
        registerSveltyPicker('el-picker', Picker, config);
        </script>
    <script type="module">
        import Svelecte, { config as svconfig, registerSvelecte } from '/svelecte.mjs';
        registerSvelecte('el-svelecte', Svelecte, svconfig);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Nette.init();
        });
    </script>
</body>
</html>