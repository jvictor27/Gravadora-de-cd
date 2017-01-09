<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="content form_create">

    <article>

        <header>
            <h1>Atualizar Cantor:</h1>
        </header>

        <?php
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $catid = filter_input(INPUT_GET, 'catid', FILTER_VALIDATE_INT);

        if (!empty($data['SendPostForm'])):
            unset($data['SendPostForm']);

            require('_models/AdminCantor.class.php');
            $cadastra = new AdminCantor;
            $cadastra->ExeUpdate($catid, $data);

            WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
        else:
            $read = new Read;
            $read->ExeRead("ws_cantor", "WHERE cantor_id = :id", "id={$catid}");
            if (!$read->getResultado()):
                header('Location: painel.php?exe=cantor/index&empty=true');
            else:
                $data = $read->getResultado()[0];
            endif;
        endif;
        
        $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
        if($checkCreate && empty($cadastra)):
            $tipo = 'cantor';
            WSErro("O {$tipo} <b>{$data['cantor_title']}</b> foi cadastrado com sucesso no sistema!", WS_ACCEPT);
        endif;
        
        ?>

        <form name="PostForm" action="" method="post" enctype="multipart/form-data">


            <label class="label">
                <span class="field">Nome:</span>
                <input type="text" name="cantor_title" value="<?php if (isset($data)) echo $data['cantor_title']; ?>" />
            </label>

            <div class="btns">
                <input type="submit" class="btn blue" value="Atualizar Cantor" name="SendPostForm" />
            </div>
        </form>

    </article>

    <div class="clear"></div>
</div> <!-- content home -->