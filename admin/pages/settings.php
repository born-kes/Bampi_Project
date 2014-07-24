<?php
function save_settings($post) {
    if(isset($post)) {
        $save_conf = array();
        foreach($_POST as $name => $val){
            $save_conf[$name]=$val;
        }
        $save_conf['theme']= cat_index('../themes/', (int)@$_GET['theme']);
        //Save:
        if(file_put_contents('../inc/config.php', serialize($save_conf))) {
            success('Ustawienia zostały pomyślnie zapisane!');
            global  $inc; unset($inc['../inc/config.php']);
        } else{ error('Nie udało się zapisać ustawień. Sprawdź chmody pliku <i>config.php</i>');}
    }
}

	save_settings(@$_POST['submit']);
   // $config = fileLoadData('../inc/config.php');
	getMsg();
?>
		<div class="box">
			<h2>Ustawienia</h2>
			<div class="content">
				<form method="POST" action="?go=settings">
				<table class="settings">
					<thead>
						<tr>
							<td width="16%">Nazwa</td> <td>Pole</td>
                            <td>Opis</td>
                            <td>Frazy Skórki</td>
						</tr>
					</thead>
					<tbody>
                        <tr>
                            <td>Przyjazny URL</td> <td><input type="checkbox" name="url" value="true" <?php echo ($config['url']?'checked':''); ?> /></td>
                            <td>Definiuje sposób generowania adresu. Opcja aktywna ukrywa <i>?page</i> w adresie strony</td>
                            <td></td>
                        </tr>
						<tr>
                            <td>Kontroler na głównej</td> <td><input type="checkbox" name="licznik" value="true" <?php echo ($config['licznik']?'checked':''); ?> /></td>
                            <td>Włącza licznik czasu ładowania strony i menu danych</td>
                            <td></td>
                        </tr>
						<tr>
							<td>Nazwa Strony:</td> <td><input type="text" name="title" value="<?php echo $config['title']; ?>" /></td>
							<td>Definiuje tytuł strony. Widoczna w tagu <i>title</i> oraz nagłówku szablonu.</td>
                            <td>{{title}}</td>
						</tr>
						<tr>
							<td>Opis Strony:</td> <td><input type="text" name="description" value="<?php echo $config['description']; ?>" /></td> 
							<td>Krótki opis strony. Będzie widoczny w meta-tagu <i>description</i>.</td>
                            <td>{{description}}</td>
						</tr>
						<tr>
							<td>Słowa Kluczowe:</td> <td><input type="text" name="keywords" value="<?php echo $config['keywords']; ?>" /></td> 
							<td>Słowa kluczowe wymienione po przecinku. Widoczne w meta-tagu <i>keywords</i>.</td>
                            <td>{{keywords}}</td>
                        </tr>
						<tr>
							<td>Szablon:</td> <td><?php
                                    echo input(array(
                                        'type'=>'select',
                                        'value'=> catList('../themes/'),
                                        'name'=>"theme",
                                        'spec'=> cat_index('../themes/', $config['theme'])
                                    )  );
                                    ?></td>
							<td>Określa szablon, który będzie używany na stronie.</td> 
						</tr>
                        <tr>
                            <td>Adres e-Mail:</td> <td><input type="text" name="email" value="<?php echo $config['email']; ?>" /></td>
                            <td>Twój adres e-mail. Używany w formularzu kontaktowym.</td>
                        </tr>
                        <tr>
                            <td>Treść Stopki:</td> <td><input type="text" name="footer" value="<?php echo $config['footer']; ?>" /></td>
                            <td>Definiuje zawrtość stopki widocznej w szablonie.</td>
                            <td>{{footer}}</td>
                        </tr>
					</tbody>
				</table>
				<input type="submit" name="submit" value="Zapisz" />
				</form>
			</div>
		</div>