<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="content form_create">

    <article>

        <header>
            <h1>Cadastrar Cantor:</h1>
        </header>

        <?php
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($data['SendPostForm'])):
            unset($data['SendPostForm']);

            require('_models/AdminCantor.class.php');
            $cadastra = new AdminCantor;
            $cadastra->ExeCreate($data);

            if (!$cadastra->getResultado()):
                WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
            else:
                header('Location: painel.php?exe=cantor/update&create=true&catid=' . $cadastra->getResultado());
            endif;
        endif;
        ?>

        <form name="PostForm" action="" method="post" enctype="multipart/form-data">


            <label class="label">
                <span class="field">Nome:</span>
                <input type="text" name="cantor_title" value="<?php if (isset($data)) echo $data['cantor_title']; ?>" />
            </label>
            
            <div class="btns">
                <input type="submit" class="btn green" value="Cadastrar Cantor" name="SendPostForm" />
            </div>
            
        </form>

    </article>

    <div class="clear"></div>
</div> <!-- content home -->